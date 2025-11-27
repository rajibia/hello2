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
    private $ipdPatientDepartmentRepository;
    private $pathologyTestTemplateRepository;
    private $radiologyTestTemplateRepository;
    private $pathologyTestRepository;
    private $radiologyTestRepository;
    private $patientCaseRepository;

    public function __construct(
        IpdPatientDepartmentRepository $ipdPatientDepartmentRepo,
        PathologyTestTemplateRepository $pathologyTestTemplateRepo,
        PathologyTestRepository $pathologyTestRepo,
        PatientCaseRepository $patientCaseRepository,
        RadiologyTestTemplateRepository $radiologyTestTemplateRepo,
        RadiologyTestRepository $radiologyTestRepo
    ) {
        $this->ipdPatientDepartmentRepository = $ipdPatientDepartmentRepo;
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->pathologyTestTemplateRepository = $pathologyTestTemplateRepo;
        $this->radiologyTestTemplateRepository = $radiologyTestTemplateRepo;
        $this->radiologyTestRepository = $radiologyTestRepo;
        $this->patientCaseRepository = $patientCaseRepository;
    }

    /**
     * Display a listing of IPD patients.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $pageTitle = __('messages.ipd_patient.ipd_patients');

        if ($filter === 'current') {
            $pageTitle = __('Current IPD Patients');
        } elseif ($filter === 'old') {
            $pageTitle = __('Old IPD Patients');
        }

        $statusArr = IpdPatientDepartment::STATUS_ARR;

        return view('ipd_patient_departments.index', compact('statusArr', 'filter', 'pageTitle'));
    }

    /**
     * Get doctor-specific charges (AJAX helper).
     */
    public function getDoctorCharges(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        $standardCharge = \DB::table('doctor_opd_charges')->where('doctor_id', $doctorId)->value('standard_charge') ?? 0;

        return response()->json([
            'charges' => [],
            'standard_charge' => number_format($standardCharge, 2),
        ]);
    }

    /**
     * Show the form for creating a new IPD patient.
     */
    public function create(Request $request)
    {
        $data = $this->ipdPatientDepartmentRepository->getAssociatedData();

        $data['patient_id'] = $request->query('ref_p_id', '');

        $patient = Patient::where('patients.id', $data['patient_id'])
            ->join('users', 'users.id', 'patients.user_id')
            ->first();

        $data['patient_name'] = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));

        $users = null;
        $patients = $data['patients'] ?? [];

        // Optional search implemented in the view
        if ($request->has('search_by') && $request->has('search_value')) {
            $request->validate([
                'search_by' => 'required|string|in:name,phone,location,insurance_number',
                'search_value' => 'required|string|max:255',
            ]);

            $searchBy = $request->input('search_by');
            $searchValue = $request->input('search_value');

            $usersQuery = User::query();

            if ($searchBy === 'name') {
                $usersQuery->where(function ($q) use ($searchValue) {
                    $q->where('first_name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('last_name', 'LIKE', "%{$searchValue}%");
                });
            } elseif ($searchBy === 'phone') {
                $usersQuery->where('phone', 'LIKE', "%{$searchValue}%");
            } elseif ($searchBy === 'location') {
                $usersQuery->where('location', 'LIKE', "%{$searchValue}%");
            } elseif ($searchBy === 'insurance_number') {
                $usersQuery->where('insurance_number', 'LIKE', "%{$searchValue}%");
            }

            $users = $usersQuery->get();
        }

        return view('ipd_patient_departments.create', compact('data', 'users', 'patients'));
    }

    /**
     * AJAX patient live search used on the create page.
     */
    public function patient_search(Request $request)
    {
        try {
            $search = $request->get('query');

            if (empty($search)) {
                return response()->json([]);
            }

            $patients = Patient::with('patientUser')
                ->whereHas('patientUser', function ($query) use ($search) {
                    $query->where('status', 1)
                        ->where(function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'LIKE', "%{$search}%")
                                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                                      ->orWhere('phone', 'LIKE', "%{$search}%")
                                      ->orWhere('email', 'LIKE', "%{$search}%")
                                      ->orWhere('insurance_number', 'LIKE', "%{$search}%");
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
                        ],
                    ];
                });

            return response()->json($patients);
        } catch (\Exception $e) {
            \Log::error('Patient search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Return list of unassigned beds for a given bed type.
     */
    public function getUnassignedBeds($bedTypeId)
    {
        $assignedBedIds = \DB::table('bed_assigns')->where('status', '1')->pluck('bed_id')->toArray();

        $beds = \DB::table('beds')
            ->where('bed_type_id', $bedTypeId)
            ->whereNotIn('id', $assignedBedIds)
            ->where('is_available', '1')
            ->pluck('name', 'id');

        return response()->json($beds);
    }

    /**
     * Generic diagnosis search helper.
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $results = DiagnosisCategory::where('code', 'like', "%$query%")
            ->orWhere('name', 'like', "%$query%")
            ->limit(10)
            ->get(['code', 'name']);

        return response()->json($results);
    }

    /**
     * Store a newly created IPD patient department record.
     *
     * This method:
     * - prevents duplicate active admissions for the same patient,
     * - delegates the actual creation to repository,
     * - supports AJAX and normal form submission responses,
     * - supports optional redirect to pathology creation if form sent create_from_route = 'pathology'.
     *
     * @param CreateIpdPatientDepartmentRequest $request
     */
    public function store(CreateIpdPatientDepartmentRequest $request)
    {
        $input = $request->all();

        // 1) Duplicate active-admission check
        $patientId = $input['patient_id'] ?? null;
        if ($patientId) {
            $existingAdmission = IpdPatientDepartment::where('patient_id', $patientId)
                ->where('discharge', 0)
                ->first();

            if ($existingAdmission) {
                $message = 'Patient is already admitted (Active IPD No: ' . ($existingAdmission->ipd_number ?? 'N/A') . ')';

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'data' => null,
                    ], 409);
                }

                Flash::error($message);
                return redirect(route('ipd.patient.create'))->withInput();
            }
        }

        // 2) Create the IPD using repository
        $ipdPatient = $this->ipdPatientDepartmentRepository->store($input);

        // 3) Optional notification creation
        try {
            $this->ipdPatientDepartmentRepository->createNotification($input);
        } catch (\Exception $ex) {
            // log and continue (notification failure shouldn't block main flow)
            \Log::warning('IPD notification creation failed: ' . $ex->getMessage());
        }

        // 4) AJAX vs normal response
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.ipd_patient.ipd_patient') . ' ' . __('messages.common.saved_successfully'),
                'data' => $ipdPatient,
            ]);
        }

        Flash::success(__('messages.ipd_patient.ipd_patient') . ' ' . __('messages.common.saved_successfully'));

        // 5) Optional redirect target: if caller wanted to continue to pathology creation
        //    (This is convenient when the create form was opened from the pathology flow)
        if (!empty($input['create_from_route']) && $input['create_from_route'] === 'pathology') {
            // Assumes a route like route('pathology.test.create', ['ref_ipd_id' => $ipdPatient->id])
            // Update route name/parameter if your routes differ.
            return redirect()->route('pathology.test.create', ['ref_ipd_id' => $ipdPatient->id])
                ->with('success', __('messages.ipd_patient.ipd_patient') . ' ' . __('messages.common.saved_successfully'));
        }

        // Default redirect to the IPD list (matches your screenshot)
        return redirect(route('ipd.patient.index'));
    }

    /**
     * Display the specified IPD patient department (show page).
     */
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

        return view('ipd_patient_departments.show',
            compact(
                'ipdPatientDepartment',
                'doctors',
                'patientsVitals',
                'parameterList',
                'parameterRadList',
                'case_id',
                'caseIds',
                'doctorsList',
                'chargeTypes',
                'medicineCategories',
                'pathologyTestTemplates',
                'radiologyTestTemplates',
                'medicineCategoriesList',
                'paymentModes',
                'bill',
                'patients',
                'ipds',
                'maternitys',
                'consultantRegister',
                'ipdTimeline',
                'ipdPrescriptions',
                'ipdCharges',
                'ipdPayment',
                'ipdDiagnosis',
                'operationCategory',
                'consultantDoctor',
                'ipdOperation',
                'doseDurationList',
                'doseIntervalList',
                'mealList',
                'diagnosisCategories',
                'patientsAllVitals',
                'ipdProvisionalDiagnosis'
            ));
    }

    public function edit(IpdPatientDepartment $ipdPatientDepartment)
    {
        $data = $this->ipdPatientDepartmentRepository->getAssociatedData();

        return view('ipd_patient_departments.edit', compact('data', 'ipdPatientDepartment'));
    }

    public function update(IpdPatientDepartment $ipdPatientDepartment, UpdateIpdPatientDepartmentRequest $request)
    {
        $input = $request->all();
        $this->ipdPatientDepartmentRepository->updateIpdPatientDepartment($input, $ipdPatientDepartment);

        Flash::success(__('messages.ipd_patient.ipd_patient') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('ipd.patient.index'));
    }

    public function destroy(IpdPatientDepartment $ipdPatientDepartment)
    {
        $this->ipdPatientDepartmentRepository->deleteIpdPatientDepartment($ipdPatientDepartment);

        return $this->sendSuccess(__('messages.ipd_patient.ipd_patient') . ' ' . __('messages.common.deleted_successfully'));
    }

    /**
     * Get the list of patient cases for a given patient ID (for AJAX use).
     */
    public function getPatientCases(Request $request)
    {
        $patientId = $request->get('patientId');

        if (!$patientId) {
            return $this->sendError('Patient ID is required');
        }

        $patientCases = PatientCase::where('patient_id', $patientId)
            ->pluck('case_id', 'id')
            ->toArray();

        return $this->sendResponse($patientCases, 'Retrieved successfully');
    }

    public function getPatientBedsList(Request $request)
    {
        $patientBeds = $this->ipdPatientDepartmentRepository->getPatientBeds(
            $request->get('id'),
            $request->get('isEdit'),
            $request->get('bedId'),
            $request->get('ipdPatientBedTypeId')
        );

        return $this->sendResponse($patientBeds, 'Retrieved successfully');
    }

    /**
     * Get patient details for the IPD modal
     */
    public function getPatientDetails(Request $request)
    {
        $patientId = $request->get('patient_id');

        if (!$patientId) {
            return $this->sendError('Patient ID is required');
        }

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
                'respiration' => $vitals->respiration,
            ];
        }

        return $this->sendResponse(['data' => $data], 'Patient details retrieved successfully');
    }
}
