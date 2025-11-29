<?php

namespace App\Http\Controllers;

use App;
use App\Exports\PatientExport;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Address;
use App\Models\AdvancedPayment;
use App\Models\Appointment;
use App\Models\BedAssign;
use App\Models\Bill;
use App\Models\BirthReport;
use App\Models\Charge;
use App\Models\DeathReport;
use App\Models\Department;
use App\Models\InvestigationReport;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\OperationReport;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\PatientCase;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Vaccination;
use App\Models\Vital;
use App\Models\IdSetting;
use App\Repositories\AdvancedPaymentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\PatientCaseRepository;
use App\Repositories\SettingRepository;
use Carbon\Carbon;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;
use DB;
use App\Repositories\InvoiceRepository;
use Arr;

class PatientController extends AppBaseController
{
    /** @var PatientRepository */
    private $patientRepository;

    /** @var PatientCaseRepository */
    private $patientCaseRepository;

    /** @var InvoiceRepository */
    private $invoiceRepository;

    public function __construct(
        PatientRepository $patientRepo, 
        PatientCaseRepository $patientCaseRepo, 
        InvoiceRepository $invoiceRepo
    ) {
        $this->patientRepository = $patientRepo;
        $this->patientCaseRepository = $patientCaseRepo;
        $this->invoiceRepository = $invoiceRepo;
    }


    public function index()
    {
        $data['statusArr'] = Patient::STATUS_ARR;

         // Fetch the ID settings for 'patient'
        $idSetting = IdSetting::where('scope', 'patient')->first();

        // Generate next ID (optional)
        $nextPatientId = null;
        if ($idSetting) {
            $nextPatientId = $idSetting->prefix . str_pad($idSetting->current_counter, $idSetting->digits, '0', STR_PAD_LEFT);
        }

        // $data['idPrefix'] = $nextPatientId;

        return view('patients.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();
        $doctors = $this->patientCaseRepository->getDoctors();
        $charges = Charge::with('chargeCategory')->where('charge_type', 7)->get()->pluck('chargeCategory.name', 'id')->sort();

        return view('patients.create', compact('bloodGroup', 'charges', 'doctors'));
    }

    public static function generateNewUniquePatientId(): string
    {
        return \DB::transaction(function () {
            $setting = \App\Models\IdSetting::where('scope', 'patient')
                ->lockForUpdate()
                ->firstOrFail();

            $prefix = $setting->prefix ?? 'P-';
            $digits = $setting->digits ?? 5;
            $counter = $setting->current_counter ?? 0;

            $nextNumber = $counter + 1;
            $padded = str_pad($nextNumber, $digits, '0', STR_PAD_LEFT);
            $uniqueId = $prefix . $padded;

            $setting->current_counter = $nextNumber;
            $setting->save();

            return $uniqueId;
        });
    }


    public function store(CreatePatientRequest $request)
    {
        
        $input = $request->all();
        // Validate the request
        $request->validate([
            'phone' => 'required|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'guardian_relation' => 'nullable|string',
            // 'charge_id' => 'required|exists:charges,id',
            'date' => 'required|date',
        ]);
        $settings = App::make(SettingRepository::class)->getSyncList();
        // Prepare phone number using the custom function
        $phone = preparePhoneNumber($request->all(), 'phone');
        try {
            DB::beginTransaction();
            
            
            // Existing patient registration logic
            $input['patient_unique_id'] =  $this::generateNewUniquePatientId();
            $input['status'] = isset($input['status']) ? 1 : 0;
            $input['phone'] = $phone;
            $input['department_id'] = Department::whereName('Patient')->first()->id;
            
            // Setting password logic
            if (!$input['password'] || $input['password'] == '') {
                $input['password'] = $input['patient_unique_id'];
                $input['password_confirmation'] = $input['patient_unique_id'];
            }
            
            $input['password'] = Hash::make($input['password']);
            $input['language'] = $settings['default_lang'];
            $input['location'] = $input['location'];
            $input['guardian_name'] = $input['guardian_name'];
            $input['guardian_phone'] = $input['guardian_phone'];
            $input['guardian_relation'] = $input['guardian_relation'];
            $input['insurance_number'] = $input['insurance_number'];
            $input['dob'] = (!empty($input['dob'])) ? $input['dob'] : null;
            
            // Set email logic
            if (!$input['email'] || $input['email'] == '' || User::where('email',$input['email'])->count()>0) {
                $input['email'] = $input['patient_unique_id'] . '@hms.com';
            }
            
            // Create user and patient
            $user = User::create($input);
            if (isset($input['image']) && !empty($input['image'])) {
                $mediaId = storeProfileImage($user, $input['image']);
            }

            $patient = Patient::create(['user_id' => $user->id, 'patient_unique_id' => $input['patient_unique_id']]);

            $update = Patient::find($patient->id);
            $update->nationality = $input['nationality'];
            $update->occupation = $input['occupation'];
            $update->company_id = $input['company_id'] ?? null;
            $update->staff_id = $input['staff_id'] ?? null;
            $update->save();
            // Handle address
            $ownerId = $patient->id;
            $ownerType = Patient::class;
            if (!empty($address = Address::prepareAddressArray($input))) {
                Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
            }

            $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
            $user->assignRole($input['department_id']);

            // Handle invoice
            $charge = Charge::where('id', $input['charge_id'])->first();
            // $charge->standard_charge = $charge->standard_charge ?? 0;
            
            $input['invoice_id'] = Invoice::generateUniqueInvoiceId();
            $input['invoice_date'] = date('Y-m-d');
            $input['patient_id'] = $ownerId;
            $input['description'] = 'Registration Fee';
            $input['discount'] = 0;
            $input['status'] = 1;
            $input['currency_symbol'] = getCurrencySymbol();
            $input['paid_amount'] = 0;
            $input['change'] = 0;
            $input['amount'] = $charge->standard_charge ?? 0;
            $input['total'] = $charge->standard_charge ?? 0;
            $input['balance'] = $charge->standard_charge ?? 0;
            

            $this->patientRepository->createNotification($input);
            $bill = $this->invoiceRepository->saveInvoicePatient($input);

            // Prepare case input data
            $caseInput = [
                'patient_id' => $ownerId, // The ID of the patient
                'phone' => $phone, // Patient's phone number from the request
                // 'doctor_id' => $request->input('doctor_id'), // The ID of the doctor from the request
                'charge_id' => $request->input('charge_id'), // Charge ID from the request
                'date' => $request->input('date'), // Date from the request
                'status' => $request->input('status') ?? 1, // Use null coalescing to set a default value
            ];

            // You might want to add more fields to $caseInput based on your case requirements
            $patientCase = $this->patientCaseRepository->store($caseInput);

            // Check if the patient case was created successfully
            if (!$patientCase) {
                throw new Exception('Failed to create patient case.');
            }

            Flash::success(__('messages.advanced_payment.patient') . ' ' . __('messages.common.saved_successfully'));
            DB::commit();
            
            return redirect(route('patients.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Flash::error(__('messages.common.error') . ': ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    
    public function show($patientId)
    {
        // Fetch patient data
        $data = $this->patientRepository->getPatientAssociatedData($patientId);
        // $data['patient_unique_id'] = 

        // Check if data exists
        if (!$data) {
            return view('errors.404');
        }

        // Check record access for logged-in patient
        if (getLoggedinPatient() && checkRecordAccess($data->id)) {
            return view('errors.404');
        } else {
            // Get advanced payment repository
            $advancedPaymentRepo = App::make(AdvancedPaymentRepository::class);
            $patients = $advancedPaymentRepo->getPatients();
            $user = Auth::user();

            // Get vaccination patients based on user role
            if ($user->hasRole('Doctor')) {
                $vaccinationPatients = getPatientsList($user->owner_id);
            } else {
                $vaccinationPatients = Patient::getActivePatientNames();
            }

            // Fetch vaccinations
            $vaccinations = Vaccination::toBase()->pluck('name', 'id')->toArray();
            natcasesort($vaccinations);

            // Fetch latest vitals for the patient (retrieve all columns)
            $latestVitals = Vital::where('patient_id', $patientId)
                                ->orderBy('created_at', 'desc')
                                ->first(); 
            $patientAddress = Address::where('owner_id', $patientId)->orderBy('created_at', 'desc')->first();

            // Fetch the ID settings for 'patient'
            $idSetting = IdSetting::where('scope', 'patient')->first();

            // Generate next ID (optional)
            // $nextPatientId = null;
            // if ($idSetting) {
            //     $nextPatientId = $idSetting->prefix . str_pad($idSetting->current_counter, $idSetting->digits, '0', STR_PAD_LEFT);
            // }

            


            // Query the OpdPatientDepartment model to find the corresponding record
            $opdPatientDepartment = OpdPatientDepartment::where('patient_id', $patientId)->first();
            // Pass data to view
            return view('patients.show', [
                'data' => $data,
                'patients' => $patients,
                'vaccinations' => $vaccinations,
                'vaccinationPatients' => $vaccinationPatients,
                'vitals' => $latestVitals,
                'patientAddress' => $patientAddress,
                'opdPatientDepartment' => $opdPatientDepartment,
                // 'idPrefix' => $nextPatientId,
                
            ]);
        }
    }

    public function edit(Patient $patient)
    {
        $bloodGroup = getBloodGroups();
        $charges = Charge::with('chargeCategory')->where('charge_type', 7)->get()->pluck('chargeCategory.name', 'id')->sort();

        return view('patients.edit', compact('patient', 'bloodGroup', 'charges'));
    }

    public function update(Patient $patient, UpdatePatientRequest $request)
    {
        if ($patient->is_default == 1) {
            Flash::error(__('messages.common.this_action_is_not_allowed_for_default_record'));

            return redirect(route('patients.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $this->patientRepository->update($input, $patient);

        $update = Patient::find($patient->id);
        $update->nationality = $input['nationality'];
        $update->occupation = $input['occupation'];
        $update->company_id = $input['company_id'] ?? null;
        $update->staff_id = $input['staff_id'] ?? null;
        $update->save();

        Flash::success(__('messages.advanced_payment.patient').' '.__('messages.common.updated_successfully'));

        return redirect(route('patients.index'));
    }

    public function destroy(Patient $patient)
    {
        if ($patient->is_default == 1) {
            return $this->sendError(__('messages.common.this_action_is_not_allowed_for_default_record'));
        }

        $patientModels = [
            BirthReport::class, DeathReport::class, InvestigationReport::class, OperationReport::class,
            Appointment::class, BedAssign::class, PatientAdmission::class, PatientCase::class, Bill::class,
            Invoice::class, AdvancedPayment::class, Prescription::class, IpdPatientDepartment::class,
        ];
        $result = canDelete($patientModels, 'patient_id', $patient->id);

        if ($result) {
            return $this->sendError(__('messages.advanced_payment.patient').' '.__('messages.common.cant_be_deleted'));
        }

        $patient->patientUser()->delete();
        $patient->address()->delete();
        $patient->delete();

        return $this->sendSuccess(__('messages.advanced_payment.patient').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $patient = Patient::find($id);
        $status = ! $patient->patientUser->status;
        $patient->patientUser()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function patientExport()
    {
        return Excel::download(new PatientExport, 'patients-'.time().'.xlsx');
    }

    public function getBirthDate($id)
    {
        return Patient::whereId($id)->with('user')->first();
    }

    public function getPatientDetails($id)
    {
        $patient = Patient::with('user')->where('id',$id)->first();

        return response()->json(['phone' => $patient->user->phone]);
    }
}
