<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdPatientDepartmentRequest;
use App\Http\Requests\UpdateIpdPatientDepartmentRequest;
use App\Models\Antenatal;
use App\Models\ChargeType;
use App\Models\DiagnosisCategory;
use App\Models\IpdCharge;
use App\Models\IpdPatientDepartment;
use App\Models\MaternityPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\IpdPayment;
use App\Repositories\IpdBillRepository;
use App\Repositories\IpdPatientDepartmentRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Vital;
use App\Repositories\IpdAntenatalRepository;
use App\Repositories\PathologyTestTemplateRepository;
use App\Repositories\RadiologyTestTemplateRepository;
use App\Repositories\PathologyTestRepository;
use App\Repositories\RadiologyTestRepository;
use App\Repositories\PatientCaseRepository;

class IpdPatientDepartmentController extends AppBaseController
{
    /** @var IpdPatientDepartmentRepository */
    private $ipdPatientDepartmentRepository;
    private $pathologyTestTemplateRepository;
    private $radiologyTestTemplateRepository;
    private $pathologyTestRepository;
    private $radiologyTestRepository;
    private $patientCaseRepository;



    public function __construct(IpdPatientDepartmentRepository $ipdPatientDepartmentRepo, PathologyTestTemplateRepository $pathologyTestTemplateRepo,
            PathologyTestRepository $pathologyTestRepo, PatientCaseRepository $patientCaseRepository,
            RadiologyTestTemplateRepository $radiologyTestTemplateRepo, RadiologyTestRepository $radiologyTestRepo)
    {
        $this->ipdPatientDepartmentRepository = $ipdPatientDepartmentRepo;
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->pathologyTestTemplateRepository = $pathologyTestTemplateRepo;
        $this->radiologyTestTemplateRepository = $radiologyTestTemplateRepo;
        $this->radiologyTestRepository = $radiologyTestRepo;
        $this->patientCaseRepository = $patientCaseRepository;
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $pageTitle = __('messages.ipd_patient.ipd_patients');

        // Set page title based on filter
        if ($filter === 'current') {
            $pageTitle = __('Current IPD Patients');
        } elseif ($filter === 'old') {
            $pageTitle = __('Old IPD Patients');
        }

        $statusArr = IpdPatientDepartment::STATUS_ARR;

        return view('ipd_patient_departments.index', compact('statusArr', 'filter', 'pageTitle'));
    }

    public function getDoctorCharges(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        // // Get charges specific to this doctor
        // $charges = ConsultationCharge::where('doctor_id', $doctorId)
        //             ->pluck('name', 'id');

        // Get standard charge - default to 0 if not set
        $standardCharge = \DB::table('doctor_opd_charges')->where('doctor_id',$doctorId)->value('standard_charge') ?? 0;

        return response()->json([
            'charges' => [],
            'standard_charge' => number_format($standardCharge, 2)
        ]);
    }

    public function create(Request $request)
    {


        $data = $this->ipdPatientDepartmentRepository->getAssociatedData();

        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';

        $patient = \App\Models\Patient::where('patients.id',$data['patient_id'])
                        ->join('users','users.id','patients.user_id')
                        ->first();
        $data['patient_name'] = ($patient->first_name ?? '').' '.($patient->last_name ?? '');


        // Initialize variables
        $users = null;
        $patients = $data['patients'] ?? [];

        // Handle the search functionality
        if ($request->has('search_by') && $request->has('search_value')) {
            // First, validate the search input
            $request->validate([
                'search_by' => 'required|string|in:name,phone,location,insurance_number',
                'search_value' => 'required|string|max:255'
            ]);

            // Retrieve the search by and search value from the request
            $searchBy = $request->input('search_by');
            $searchValue = $request->input('search_value');

            // Let's query the db User model based on the selected search by option
            $users = User::query();
            if ($searchBy == 'name') {
                // Search by both last name and first name
                $users->where(function ($query) use ($searchValue) {
                    $query->where('first_name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('last_name', 'LIKE', "%{$searchValue}%");
                });
            } elseif ($searchBy == 'phone') {
                $users->where('phone', 'LIKE', "%{$searchValue}%");
            } elseif ($searchBy == 'location') {
                $users->where('location', 'LIKE', "%{$searchValue}%");
            } elseif ($searchBy == 'insurance_number') {
                $users->where('insurance_number', 'LIKE', "%{$searchValue}%");
            }

            // Get the results
            $users = $users->get();
        }

        return view('ipd_patient_departments.create', compact('data', 'users', 'patients'));
    }

    public function patient_search(Request $request)
    {
        try {
            $search = $request->get('query');

            if (empty($search)) {
                return response()->json([]);
            }

            // Search for patients using the Patient model with patientUser relationship
            $patients = Patient::with('patientUser')
                ->whereHas('patientUser', function ($query) use ($search) {
                    // Filter by user status (active)
                    $query->where('status', 1);

                    // Apply search conditions on the User model
                    $query->where(function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'LIKE', "%{$search}%")
                                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                                  ->orWhere('phone', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%")
                                  ->orWhere('insurance_number', 'like', "%{$search}%");
                    });
                })
                ->limit(10)
                ->get()
                ->mapWithKeys(function ($patient) {
                    return [
                        $patient->id => [
                            'id' => $patient->id ?? '',
                            'first_name' => $patient->patientUser->first_name ?? '',
                            'last_name' => $patient->patientUser->last_name ?? '',
                            'name' => $patient->patientUser->full_name ?? '',
                            'phone' => $patient->patientUser->phone ?? '',
                            'gender' => $patient->patientUser->gender ?? '',
                        ]
                    ];
                });

            return response()->json($patients);

        } catch (\Exception $e) {
            \Log::error('Patient search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    public function getUnassignedBeds($bedTypeId)
    {
        $assignedBedIds = \DB::table('bed_assigns')->pluck('bed_id')->where('status','1');

        $beds = \DB::table('beds')->where('bed_type_id', $bedTypeId)
               ->whereNotIn('id', $assignedBedIds)
               ->where('is_available','1')
               ->pluck('name', 'id');

        return response()->json($beds);
    }

    public function search(Request $request)
    {

        $query = $request->get('query', '');

        $results = DiagnosisCategory::where('code', 'like', "%$query%")
            ->orWhere('name', 'like', "%$query%")
            ->limit(10)
            ->get(['code', 'name']);

        return response()->json($results);
    }


    public function store(CreateIpdPatientDepartmentRequest $request)
    {
        $input = $request->all();
        $ipdPatient = $this->ipdPatientDepartmentRepository->store($input);
        $this->ipdPatientDepartmentRepository->createNotification($input);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.ipd_patient.ipd_patient').' '.__('messages.common.saved_successfully'),
                'data' => $ipdPatient
            ]);
        }

        Flash::success(__('messages.ipd_patient.ipd_patient').' '.__('messages.common.saved_successfully'));
        return redirect(route('ipd.patient.index'));
    }

    public function show(IpdPatientDepartment $ipdPatientDepartment)
    {

        $doctors = $this->ipdPatientDepartmentRepository->getDoctorsData();

        $consultantRegister = $this->ipdPatientDepartmentRepository->getConsultantRegister($ipdPatientDepartment->id);
        $consultantDoctor = $this->ipdPatientDepartmentRepository->getConsultantDoctor($ipdPatientDepartment->id);
        $ipdTimeline = $this->ipdPatientDepartmentRepository->getIPDTimeline($ipdPatientDepartment->id);
        $ipdPrescriptions = $this->ipdPatientDepartmentRepository->getIPDPrescription($ipdPatientDepartment->id);
        $ipdCharges = $this->ipdPatientDepartmentRepository->getIPDCharges($ipdPatientDepartment->id);
        $ipdPayment = $this->ipdPatientDepartmentRepository->getIPDPayment($ipdPatientDepartment->id);
        $ipdDiagnosis = $this->ipdPatientDepartmentRepository->getIPDDiagnosis($ipdPatientDepartment->id);
        $ipdProvisionalDiagnosis = $this->ipdPatientDepartmentRepository->getProvisionalIPDDiagnosis($ipdPatientDepartment->id);
        $ipdOperation = $this->ipdPatientDepartmentRepository->getIPDOperation($ipdPatientDepartment->id);
        $mealList = $this->ipdPatientDepartmentRepository->getMealList();

        $doctorsList = $this->ipdPatientDepartmentRepository->getDoctorsList();
        $operationCategory = $this->ipdPatientDepartmentRepository->getOperationCategoryList();
        $medicineCategories = $this->ipdPatientDepartmentRepository->getMedicinesCategoriesData();
        $medicineCategoriesList = $this->ipdPatientDepartmentRepository->getMedicineCategoriesList();
        $doseDurationList = $this->ipdPatientDepartmentRepository->getDoseDurationList();
        $doseIntervalList = $this->ipdPatientDepartmentRepository->getDoseIntervalList();
        $ipdPatientDepartmentRepository = App::make(IpdBillRepository::class);
        $bill = $ipdPatientDepartmentRepository->getBillList($ipdPatientDepartment);
        $chargeTypes = ChargeType::where('status', 1)->get()->pluck('name', 'id');
        $paymentModes = IpdPayment::PAYMENT_MODES;
        $diagnosisCategories = DiagnosisCategory::all();
        $patients = $this->ipdPatientDepartmentRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');
        $maternitys = MaternityPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('id', 'id');
        $case_id = $ipdPatientDepartment->patient_id !== '' ? PatientCase::where('patient_id', $ipdPatientDepartment->patient_id)->latest('created_at')->pluck('id')->first() : '';
        $caseIds = $ipdPatientDepartment->patient_id !== '' ? PatientCase::where('patient_id', $ipdPatientDepartment->patient_id)->get()->pluck('case_id', 'id')
                                    : PatientCase::get()->pluck('case_id', 'id');
        $pathologyTestTemplates = $this->pathologyTestTemplateRepository->getPathologyTemplate();
        $radiologyTestTemplates = $this->radiologyTestTemplateRepository->getRadiologyTemplate();
        $parameterList = $this->pathologyTestRepository->getParameterDataList();
        $parameterRadList = $this->radiologyTestRepository->getParameterDataList();
        $patientsVitals = Vital::where('patient_id', $ipdPatientDepartment->patient_id)->orderBy('created_at', 'desc')->first();
        $patientsAllVitals = Vital::where('patient_id', $ipdPatientDepartment->patient_id)->latest()->limit(10)->get();
        // dd($patientsAllVitals);


        return view('ipd_patient_departments.show',
            compact('ipdPatientDepartment', 'doctors', 'patientsVitals', 'parameterList', 'parameterRadList', 'case_id', 'caseIds', 'doctorsList', 'chargeTypes', 'medicineCategories', 'pathologyTestTemplates', 'radiologyTestTemplates',
                'medicineCategoriesList', 'paymentModes', 'bill', 'patients', 'ipds', 'maternitys', 'consultantRegister', 'ipdTimeline', 'ipdPrescriptions', 'ipdCharges', 'ipdPayment', 'ipdDiagnosis', 'operationCategory', 'consultantDoctor', 'ipdOperation', 'doseDurationList', 'doseIntervalList', 'mealList', 'diagnosisCategories','patientsAllVitals','ipdProvisionalDiagnosis'));
    }

    public function edit(IpdPatientDepartment $ipdPatientDepartment)
    {
        $data = $this->ipdPatientDepartmentRepository->getAssociatedData();

        return view('ipd_patient_departments.edit', compact('data', 'ipdPatientDepartment'));
    }

    public function update(IpdPatientDepartment $ipdPatientDepartment, UpdateIpdPatientDepartmentRequest $request)

    {
        // dd($request->all());
        // return $ipdPatientDepartment;
        $input = $request->all();
        $this->ipdPatientDepartmentRepository->updateIpdPatientDepartment($input, $ipdPatientDepartment);
        Flash::success(__('messages.ipd_patient.ipd_patient').' '.__('messages.common.updated_successfully'));

        return redirect(route('ipd.patient.index'));
    }

    public function destroy(IpdPatientDepartment $ipdPatientDepartment)
    {
        $this->ipdPatientDepartmentRepository->deleteIpdPatientDepartment($ipdPatientDepartment);

        return $this->sendSuccess(__('messages.ipd_patient.ipd_patient').' '.__('messages.common.deleted_successfully'));
    }

    public function getPatientCasesList(Request $request)
    {

        $input = [];
        $input['patient_id']= $request->get('id');
        $patientId = Patient::with('patientUser')->whereId($request->get('id'))->first();
        $caseDate = date("y-m-d", strtotime("now"));
        $input['date'] = $caseDate;
        $input['fee'] = removeCommaFromNumbers(0);
        $input['status'] = 1;
        $input['phone'] = $patientId->patientUser->phone;


        $this->patientCaseRepository->store($input);
        // $this->patientCaseRepository->createNotification($input);

        $patientCases = $this->ipdPatientDepartmentRepository->getPatientCases($request->get('id'));

        return $this->sendResponse($patientCases, 'Retrieved successfully');
    }

    public function getPatientBedsList(Request $request)
    {
        $patientBeds = $this->ipdPatientDepartmentRepository->getPatientBeds($request->get('id'),
        $request->get('isEdit'), $request->get('bedId'), $request->get('ipdPatientBedTypeId'));

        return $this->sendResponse($patientBeds, 'Retrieved successfully');
    }

    /**
     * Get patient details for the IPD modal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientDetails(Request $request)
    {
        $patientId = $request->get('patient_id');

        if (!$patientId) {
            return $this->sendError('Patient ID is required');
        }

        // Get the patient's latest vital signs
        $vitals = Vital::where('patient_id', $patientId)
                       ->orderBy('created_at', 'desc')
                       ->first();

        $data = [];

        if ($vitals) {
            $data = [
                'height' => $vitals->height,
                'weight' => $vitals->weight,
                'bp' => $vitals->blood_pressure,
                'temperature' => $vitals->temperature,
                'respiration' => $vitals->respiration
            ];
        }

        return $this->sendResponse(['data' => $data], 'Patient details retrieved successfully');
    }
}
