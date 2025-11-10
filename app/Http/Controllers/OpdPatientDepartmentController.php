<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOpdPatientDepartmentRequest;
use App\Http\Requests\UpdateOpdPatientDepartmentRequest;
use App\Models\Address;
use App\Models\Charge;
use App\Models\DiagnosisCategory;
use App\Models\DoctorOPDCharge;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\Vital;
use App\Repositories\InvoiceRepository;
use App\Repositories\OpdPatientDepartmentRepository;
use App\Repositories\RadiologyTestRepository;
use App\Repositories\PathologyTestRepository;
use App\Repositories\PathologyTestTemplateRepository;
use App\Repositories\RadiologyTestTemplateRepository;
use Flash;
use Illuminate\Http\Request;
use App\Models\User;

class OpdPatientDepartmentController extends AppBaseController
{
    /** @var OpdPatientDepartmentRepository */
    private $opdPatientDepartmentRepository;
    private $invoiceRepository;
    private $pathologyTestRepository;
    private $radiologyTestRepository;
    private $pathologyTestTemplateRepository;
    private $radiologyTestTemplateRepository;


    public function __construct(OpdPatientDepartmentRepository $opdPatientDepartmentRepo, InvoiceRepository $invoiceRepo, 
    PathologyTestRepository $pathologyTestRepo, RadiologyTestRepository $radiologyTestRepo,
    RadiologyTestTemplateRepository $radiologyTestTemplateRepo, PathologyTestTemplateRepository $pathologyTestTemplateRepo)
    {
        $this->opdPatientDepartmentRepository = $opdPatientDepartmentRepo;
        $this->invoiceRepository = $invoiceRepo;
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->radiologyTestRepository = $radiologyTestRepo;
        $this->pathologyTestTemplateRepository = $pathologyTestTemplateRepo;
        $this->radiologyTestTemplateRepository = $radiologyTestTemplateRepo;
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $pageTitle = __('messages.opd_patients');
        
        // Set page title based on filter
        if ($filter === 'upcoming') {
            $pageTitle = __('Upcoming OPD Patients');
        } elseif ($filter === 'today') {
            $pageTitle = __('Today\'s OPD Patients');
        } elseif ($filter === 'old') {
            $pageTitle = __('Old OPD Patients');
        }
        
        return view('opd_patient_departments.index', [
            'filter' => $filter,
            'pageTitle' => $pageTitle
        ]);
    }

    // public function create(Request $request)
    // {
    //     $data = $this->opdPatientDepartmentRepository->getAssociatedData();
    //     $data['revisit'] = ($request->get('revisit')) ? $request->get('revisit') : 0;

    //     if ($data['revisit']) {
    //         $id = $data['revisit'];
    //         $data['last_visit'] = OpdPatientDepartment::find($id);
    //     }

    //     // handle the search functionality
    //     $users = null;
    //     $opdPatientDapartments = null;
        
    //     if($request->has('search_by') && $request->has('search_value')){
    //         // first validate the search input
    //         $request->validate([
    //             'search_by'=> 'required|string|in:name,phone,location,insurance_number',
    //             'search_value'=>'required|string|max:255'
    //         ]);

    //         // now retrieve the search by and search value from the request
    //         $searchBy = $request->input('search_by');
    //         $searchValue = $request->input('search_value');

    //         // let's query the db User model based on the selected search by option
    //         $users = User::query();
    //         if($searchBy == 'name'){
    //             // lets search by both last name and first name
    //             $users->where(function($query) use ($searchValue){
    //                 $query->where('first_name', 'LIKE', "%{$searchValue}%")->orWhere('last_name', 'LIKE', "%{$searchValue}%");
    //             });
    //         } elseif ($searchBy == 'phone'){
    //             $users->where('phone', 'LIKE', "%{$searchValue}%");
    //         } elseif ($searchBy == 'location') {
    //             $users->where('location', 'LIKE', "%{$searchValue}%");
    //         } elseif ($searchBy == 'insurance_number') {
    //             $users->where('insurance_number', 'LIKE', "%{$searchValue}%");
    //         }

    //         // Get the results
    //         $users = $users->get();

    //         if($users->isNotEmpty()){
    //         // Collect all the user IDs found from the search
    //         $userIds = $users->pluck('id')->toArray();

    //         // Query the OpdPatientDepartment model for each user
    //         $opdPatientDepartments = OpdPatientDepartment::whereIn('patient_id', $userIds)->get();
    //     }
    //     }

    //     return view('opd_patient_departments.create', compact('data', 'users', 'opdPatientDepartments'));
    // }

public function create(Request $request)
{
    // Retrieve initial associated data
    $data = $this->opdPatientDepartmentRepository->getAssociatedData();
    $data['revisit'] = $request->get('revisit', 0);

    // Fetch last visit details if 'revisit' is set
    if ($data['revisit']) {
        $data['last_visit'] = OpdPatientDepartment::find($data['revisit']);
    }

    $patients = $data['patients'];
    
    $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        
    $patient = User::where('id',$data['patient_id'])->first();
    $data['patient_name'] = ($patient->first_name ?? '').' '.($patient->last_name ?? '');
        
    // Initialize search-related variables
    $users = collect(); // Empty collection by default
    $opdPatientDepartments = collect();
    $opdPatientCases = collect();

    // Handle search functionality
    if ($request->has('search_by') && $request->has('search_value')) {
        $request->validate([
            'search_by' => 'required|string|in:name,phone,location,insurance_number',
            'search_value' => 'required|string|max:255',
        ]);

        $searchBy = $request->input('search_by');
        $searchValue = $request->input('search_value');

        // Search users
        $users = User::query();
        if ($searchBy === 'name') {
            $users->where(function ($query) use ($searchValue) {
                $query->where('first_name', 'LIKE', "%$searchValue%")
                      ->orWhere('last_name', 'LIKE', "%$searchValue%");
            });
        } elseif ($searchBy === 'phone') {
            $users->where('phone', 'LIKE', "%$searchValue%");
        } elseif ($searchBy === 'location') {
            $users->where('location', 'LIKE', "%$searchValue%");
        } elseif ($searchBy === 'insurance_number') {
            $users->where('insurance_number', 'LIKE', "%$searchValue%");
        }

        $users = $users->get();

        if ($users->isNotEmpty()) {
            $userIds = $users->pluck('id');

            // Fetch related data for users
            $opdPatientDepartments = OpdPatientDepartment::whereIn('patient_id', $userIds)
                ->with('patient')
                ->get();

            $opdPatientCases = PatientCase::whereIn('patient_id', $userIds)
                ->with('patient')
                ->get();
        }
    }

    // Pass data to the view
    return view('opd_patient_departments.create', [
        'data' => $data,
        'patients' => $patients,
        'users' => $users,
        'opdPatientDepartments' => $opdPatientDepartments,
        'opdPatientCases' => $opdPatientCases,
    ]);
}

    public function store(CreateOpdPatientDepartmentRequest $request)
    {
        // dd($request->all());
        $ipd_record = IpdPatientDepartment::where('patient_id', $request->patient_id)->where('discharge', 0)->first();
        if (isset($ipd_record)) {
            Flash::error('Patient is already in IPD');
            return redirect()->back();
        }
        $input = $request->all();
        if(!$input['standard_charge'] || $input['standard_charge'] == '') {
            $input['standard_charge'] = 0;
        }
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        // $input['paid_amount'] = (float)$input['paid_amount'];
        // $input['change'] = (float)$input['change'];
        // $this->opdPatientDepartmentRepository->store($input);

        $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
        
        $opdPatientDepartment = OpdPatientDepartment::create($input);

        if($opdPatientDepartment->height > 0 || $opdPatientDepartment->weight > 0 || $opdPatientDepartment->bp != '' || $opdPatientDepartment->temperature != '' || $opdPatientDepartment->pulse != '' || $opdPatientDepartment->respiration != '' || $opdPatientDepartment->oxygen_saturation != '') {
            $vitals = new Vital();
            $vitals->patient_id = $opdPatientDepartment->patient_id;
            $vitals->opd_id = $opdPatientDepartment->id;
            $vitals->height = $opdPatientDepartment->height;
            $vitals->weight = $opdPatientDepartment->weight;
            $vitals->bp = $opdPatientDepartment->bp;
            $vitals->temperature = $opdPatientDepartment->temperature;
            $vitals->pulse = $opdPatientDepartment->pulse;
            $vitals->respiration = $opdPatientDepartment->respiration;
            $vitals->oxygen_saturation = $opdPatientDepartment->oxygen_saturation;
            $vitals->save();
        }
        // Retrieve the ID of the created record
        $createdRecordId = $opdPatientDepartment->id;

        $charge = Charge::where('id', $input['charge_id'] ?? NULL)->first();

        if(isset($charge)) {
            // check if an invoice already exists for the patient
            $existingInvoice = Invoice::where('patient_id', $opdPatientDepartment->patient_id)->where('status', 1)->first();
            if($existingInvoice){
                // update the existing invoice by adding a new invoice item
                $input['invoice_id'] = $existingInvoice->invoice_id;
                $this->updateInvoicePatient($existingInvoice, $input, $charge);
            } else{
                $input['invoice_id'] = Invoice::generateUniqueInvoiceId();
                $input['invoice_date'] = date('Y-m-d');
                $input['patient_id'] = $opdPatientDepartment->patient_id;
                $input['description'] = 'Consultation Fee';
                $input['discount'] = 0;
                $input['status'] = 1;
                $input['currency_symbol'] = getCurrencySymbol();
                $input['paid_amount'] = 0;
                
                $bill = $this->invoiceRepository->saveInvoicePatient($input);

                $opdPatientDepartment->invoice_id = $bill->id;
                $opdPatientDepartment->save();
            }
        }
        $this->opdPatientDepartmentRepository->createNotification($input);
        
        if ($request->ajax()) {
            return $this->sendResponse($opdPatientDepartment, __('messages.opd_patient.opd_patient').' '.__('messages.common.saved_successfully'));
        }
        
        Flash::success(__('messages.opd_patient.opd_patient').' '.__('messages.common.saved_successfully'));

        return redirect(route('opd.patient.show', $opdPatientDepartment->id));
    }

     public function updateInvoicePatient($invoice, $input, $charge)
    {
        // Create a new invoice item for the existing invoice
        $invoiceItem = new InvoiceItem();
        $invoiceItem->invoice_id = $invoice->id;
        $invoiceItem->charge_id = $input['charge_id'];
        // $invoiceItem->description = $input['description'];
        $invoiceItem->quantity = 1;
        $invoiceItem->price = $charge->standard_charge;
        $invoiceItem->total = $charge->standard_charge;
        $invoiceItem->currency_symbol = getCurrencySymbol();
        $invoiceItem->save();

        // Update the invoice totals
        $invoice->amount += $charge->standard_charge;
        $invoice->total += $charge->standard_charge;
        
        // Recalculate balance: total amount - discount - paid amount
        $discountAmount = $invoice->discount ?? 0;
        $paidAmount = $invoice->paid_amount ?? 0;
        $invoice->balance = $invoice->amount - $discountAmount - $paidAmount;
        
        $invoice->save();

        return $invoice;
    }
    
    // public function show(OpdPatientDepartment $opdPatientDepartment)
    // {
    //     $doctors = $this->opdPatientDepartmentRepository->getDoctorsData();
    //     $users = User::where()
    //     return view('opd_patient_departments.show', compact('opdPatientDepartment', 'doctors'));
    // }
    public function show(OpdPatientDepartment $opdPatientDepartment)
    {

        // $consultantDoctor = $this->opdPatientDepartmentRepository->getConsultantDoctor($opdPatientDepartment->id);
        // Retrieve the list of doctors
        $doctors = $this->opdPatientDepartmentRepository->getDoctorsData();
        // Get the patient ID from opdPatientDepartment
        $patientId = $opdPatientDepartment->patient_id;
        $patientAddress = Address::where('owner_id', $patientId)->first();
        // Retrieve the patient using the patient_id
        $patient = Patient::where('id', $patientId)->first(); 
        // Check if patient exists and get user_id
        $userId = $patient ? $patient->user_id : null; 
        // Retrieve user information using user_id
        $user = User::where('id', $userId)->first(); 
        $patientsVitals = Vital::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
        $patientsAllVitals = Vital::where('patient_id', $patientId)->latest()->limit(10)->get();
        $diagnosisCategories = DiagnosisCategory::all();

        $parameterList = $this->pathologyTestRepository->getParameterDataList();
        $parameterRadList = $this->radiologyTestRepository->getParameterDataList();
        // isset($ipdPatientDepartment) && $ipdPatientDepartment->patient_id) || (isset($opdPatientDepartment) && $opdPatientDepartment->patient_id)

        $patients = $this->opdPatientDepartmentRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');
        $case_id = $opdPatientDepartment->patient_id !== '' ? PatientCase::where('patient_id', $opdPatientDepartment->patient_id)->latest('created_at')->pluck('id')->first() : '';
        $caseIds = $opdPatientDepartment->patient_id !== '' ? PatientCase::where('patient_id', $opdPatientDepartment->patient_id)->get()->pluck('case_id', 'id')
                                    : PatientCase::get()->pluck('case_id', 'id');
        $pathologyTestTemplates = $this->pathologyTestTemplateRepository->getPathologyTemplate();
        $radiologyTestTemplates = $this->radiologyTestTemplateRepository->getRadiologyTemplate();
        $opdTimeline = $this->opdPatientDepartmentRepository->getOPDTimeline($opdPatientDepartment->id);

        // $bill = $opdPatientDepartmentRepository->getBillList($opdPatientDepartment);





        // Pass the required variables to the view
        // return view('opd_patient_departments.show', compact('opdPatientDepartment', 'consultantDoctor', 'patients', 'opds', 'ipds', 'case_id', 'caseIds', 'pathologyTestTemplates', 'radiologyTestTemplates', 'parameterList', 'parameterRadList', 'doctors', 'patient', 'patientAddress', 'user', 'patientsVitals', 'diagnosisCategories'));
        return view('opd_patient_departments.show', compact('opdPatientDepartment', 'opdTimeline', 'patients', 'opds', 'ipds', 'case_id', 'caseIds', 'pathologyTestTemplates', 'radiologyTestTemplates', 'parameterList', 'parameterRadList', 'doctors', 'patient', 'patientAddress', 'user', 'patientsVitals', 'diagnosisCategories','patientsAllVitals'));
    }

    public function edit(OpdPatientDepartment $opdPatientDepartment)
    {
        $data = $this->opdPatientDepartmentRepository->getAssociatedData();

        return view('opd_patient_departments.edit', compact('data', 'opdPatientDepartment'));
    }

    public function update(OpdPatientDepartment $opdPatientDepartment, UpdateOpdPatientDepartmentRequest $request)
    {
        $ipd_record = IpdPatientDepartment::where('patient_id', $request->patient_id)->where('discharge', 0)->first();
        if (isset($ipd_record)) {
            Flash::error('Patient is already in IPD');
            return redirect()->back();
        }
        $input = $request->all();
        $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
        $opdPatientDepartment->update($input);

        $invoice = Invoice::find($opdPatientDepartment->invoice_id);
        if(isset($invoice)) {
            $charge = Charge::where('id', $input['charge_id'])->first();
            $invoice->currency_symbol = getCurrencySymbol();
            $invoice->amount = $charge->standard_charge;
            $invoice->total = $charge->standard_charge;
            
            // Calculate balance: total amount - discount - paid amount
            $discountAmount = $invoice->discount ?? 0;
            $paidAmount = $invoice->paid_amount ?? 0;
            $invoice->balance = $invoice->amount - $discountAmount - $paidAmount;
            
            $invoice->save();

            $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->first();
            $invoiceItem->charge_id = $input['charge_id'];
            $invoiceItem->quantity = 1;
            $invoiceItem->price = $charge->standard_charge;
            $invoiceItem->total = $charge->standard_charge;
            // $invoiceItem->created_at = Carbon::now();
            // $invoiceItem->updated_at = Carbon::now();
            $invoiceItem->currency_symbol = getCurrencySymbol();
            $invoiceItem->save();

        }
        
        // $this->opdPatientDepartmentRepository->updateOpdPatientDepartment($input, $opdPatientDepartment);
        Flash::success(__('messages.opd_patient.opd_patient').' '.__('messages.common.updated_successfully'));

        return redirect(route('opd.patient.index'));
    }

    public function destroy($id)
    {
        $opdPatientDepartment = OpdPatientDepartment::find($id);
        $opdPatientDepartment->delete();

        return $this->sendSuccess(__('messages.opd_patient.opd_patient').' '.__('messages.common.deleted_successfully'));
    }

    public function markServed(Request $request)
    {
        $opdPatientDepartment = OpdPatientDepartment::find($request->id);
        if($opdPatientDepartment->served == 0) {
            $opdPatientDepartment->served = 1;
        } else {
            $opdPatientDepartment->served = 0;
        }
        $opdPatientDepartment->save();

        Flash::success('Status changed successfully');

        return redirect('/opds?filter=upcoming');

        // return $this->sendSuccess(__('messages.opd_patient.opd_patient').' '.__('messages.common.deleted_successfully'));
    }

    public function getPatientCasesList(Request $request)
    {
        $patientCases = $this->opdPatientDepartmentRepository->getPatientCases($request->get('id'));

        return $this->sendResponse($patientCases, 'Retrieved successfully');
    }

    public function getDoctorOPDCharge(Request $request)
    {
        $doctorOPDCharge = DoctorOPDCharge::whereDoctorId($request->get('id'))->get();

        return $this->sendResponse($doctorOPDCharge, 'Doctor OPD Charge retrieved successfully.');
    }

    public function getChargeOPDCharge(Request $request)
    {
        $chargeOPDCharge = Charge::whereId($request->get('id'))->get();

        return $this->sendResponse($chargeOPDCharge, 'OPD Charge retrieved successfully.');
    }
    
    public function discharge(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|exists:ipd_patient_departments,id',
                'discharge_reason' => 'required|string',
                'discharge_notes' => 'nullable|string',
            ]);

            $ipdPatientDepartment = IpdPatientDepartment::findOrFail($validatedData['patient_id']);

            $ipdPatientDepartment->discharge_status = $validatedData['discharge_reason'];
            $ipdPatientDepartment->discharge_notes = $validatedData['discharge_notes'] ?? null;
            $ipdPatientDepartment->discharge_date = now(); 
            $ipdPatientDepartment->doctor_discharge = 1;
            $ipdPatientDepartment->doctor_incharge = \Auth::user()->id;

            $ipdPatientDepartment->save();

            Flash::success('Patient has been successfully discharged.');
            return redirect()->back();

        } catch (ValidationException $e) {
            Flash::error('Patient not discharged.');

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Handle any other errors that might occur
            Flash::error('Patient not discharged.');
            return redirect()->back()->with('error', 'An error occurred while discharging the patient. Please try again.');
        }
    }
    
    /**
     * Get patient details including vitals for OPD form
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPatientDetails(Request $request)
    {
        $patientId = $request->get('patient_id');
        
        if (!$patientId) {
            return $this->sendError('Patient ID is required');
        }
        
        // Get the most recent OPD record for this patient to retrieve vitals
        $opdRecord = OpdPatientDepartment::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->first();
            
        $data = [];
        
        if ($opdRecord) {
            $data = [
                'height' => $opdRecord->height,
                'weight' => $opdRecord->weight,
                'bp' => $opdRecord->bp,
                'temperature' => $opdRecord->temperature,
                'respiration' => $opdRecord->respiration,
            ];
        }
        
        return $this->sendResponse(['data' => $data], 'Patient details retrieved successfully');
    }
}
