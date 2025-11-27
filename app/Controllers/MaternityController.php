<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaternityRequest;
use App\Http\Requests\UpdateMaternityRequest;
use App\Models\ChargeType;
use App\Models\DiagnosisCategory;
use App\Models\Maternity;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\Vital;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Repositories\InvoiceRepository;
use App\Repositories\MaternityRepository;
use App\Repositories\PathologyTestRepository;
use App\Repositories\PathologyTestTemplateRepository;
use App\Repositories\PatientCaseRepository;
use App\Repositories\RadiologyTestRepository;
use App\Repositories\RadiologyTestTemplateRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MaternityController extends AppBaseController
{
    /** @var MaternityRepository */
    private $maternityRepository;
    private $invoiceRepository;
    private $pathologyTestRepository;
    private $radiologyTestRepository;
    private $pathologyTestTemplateRepository;
    private $radiologyTestTemplateRepository;
    private $patientCaseRepository;

    public function __construct(
        MaternityRepository $maternityRepo,
        InvoiceRepository $invoiceRepo,
        PathologyTestRepository $pathologyTestRepo,
        RadiologyTestRepository $radiologyTestRepo,
        RadiologyTestTemplateRepository $radiologyTestTemplateRepo,
        PathologyTestTemplateRepository $pathologyTestTemplateRepo,
        PatientCaseRepository $patientCaseRepository
    ) {
        $this->maternityRepository = $maternityRepo;
        $this->invoiceRepository = $invoiceRepo;
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->radiologyTestRepository = $radiologyTestRepo;
        $this->pathologyTestTemplateRepository = $pathologyTestTemplateRepo;
        $this->radiologyTestTemplateRepository = $radiologyTestTemplateRepo;
        $this->patientCaseRepository = $patientCaseRepository;
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $pageTitle = __('messages.maternity_patient.maternity_patients');

        // Set page title based on filter
        if ($filter === 'upcoming') {
            $pageTitle = __('Upcoming Maternity Patients');
        } elseif ($filter === 'today') {
            $pageTitle = __('Today\'s Maternity Patients');
        } elseif ($filter === 'old') {
            $pageTitle = __('Old Maternity Patients');
        } else {
            $pageTitle = __('All Maternity Patients');
        }

        return view('maternity.index', [
            'filter' => $filter,
            'pageTitle' => $pageTitle
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->maternityRepository->getAssociatedData();
        $data['revisit'] = ($request->get('revisit')) ? $request->get('revisit') : 0;

        if ($data['revisit']) {
            $id = $data['revisit'];
            $data['last_visit'] = Maternity::find($id);
        }

        return view('maternity.create', compact('data'));
    }

    public function store(CreateMaternityRequest $request)
    {
        $input = $request->all();

        try {
            $maternity = $this->maternityRepository->storeWithInvoice($input);

            Flash::success(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.saved_successfully'));

            return $this->sendResponse($maternity, __('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.saved_successfully'));
        } catch (\Exception $e) {
            Flash::error('Error creating maternity patient: ' . $e->getMessage());
            return $this->sendError('Error creating maternity patient: ' . $e->getMessage());
        }
    }

    public function show(Maternity $maternityPatient)
    {
        $maternityPatient->load(['patient.patientUser', 'doctor.doctorUser', 'patientCase']);

        // Ensure patient relationship is loaded
        if (!$maternityPatient->patient) {
            Flash::error('Patient not found for this maternity record.');
            return redirect()->route('maternity.index');
        }

        // Get patient vitals for the vitals indicator
        $patientsVitals = Vital::where('patient_id', $maternityPatient->patient_id)
            ->whereNotNull('bp')
            ->where('bp', '!=', '')
            ->orderBy('created_at', 'desc')
            ->first();

        $patientsAllVitals = Vital::where('patient_id', $maternityPatient->patient_id)
            ->whereNotNull('bp')
            ->where('bp', '!=', '')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get doctors list for any dropdowns
        $doctorsList = $this->maternityRepository->getDoctorsList();

        // Convert doctors list to proper format for dropdown
        $doctors = [];
        if (is_array($doctorsList) && count($doctorsList) > 0) {
            foreach ($doctorsList as $doctor) {
                if (isset($doctor['key']) && isset($doctor['value'])) {
                    $doctors[$doctor['key']] = $doctor['value'];
                }
            }
        }

        // Fallback if no doctors found
        if (empty($doctors)) {
            $doctors = \App\Models\Doctor::with('doctorUser')->get()->pluck('doctorUser.full_name', 'id')->toArray();
        }

        // Debug: Log doctors for debugging
        \Log::info('Doctors loaded for dropdown:', $doctors);

        // Get medicine categories and other lists (empty for now)
        $medicineCategoriesList = [];
        $medicineCategories = []; // Add for IPD modal compatibility
        $doseDurationList = [];
        $doseIntervalList = [];
        $mealList = [];

        // Add charge types for IPD modal compatibility
        $chargeTypes = collect([]);

        // Add other common variables for IPD modal compatibility
        $beds = collect([]);
        $bedTypes = collect([]);
        $departments = collect([]);
        $patients = collect([]);
        $operationCategory = collect([]);
        $parameterList = collect([]);
        $radiologyCategories = collect([]);
        $pathologyCategories = collect([]);
        $paymentModes = collect([]);
        $currencies = collect([]);
        $ipds = collect([]);
        $opds = collect([]);
        $caseIds = collect([]);
        $case_id = null;
        $pathologyTestTemplates = collect([]);
        $parameterRadList = collect([]);
        $maternitys = collect([]);
        $radiologyTestTemplates = collect([]);

        // Get consultant doctors
        $consultantDoctor = $maternityPatient->consultantRegisters()->with('doctor.doctorUser')->get();

        // Get medicines for prescription modal
        $medicines = Medicine::all();

        // Get timeline (empty for now)
        $ipdTimeline = collect([]);

        // Get bill information
        $bill = [
            'total_payment' => 0,
            'total_charges' => $maternityPatient->standard_charge ?? 0
        ];

        // Add ipdPatientDepartment for IPD modal compatibility
        $ipdPatientDepartment = $maternityPatient;

        return view('maternity.show', compact(
            'maternityPatient',
            'ipdPatientDepartment',
            'patientsVitals',
            'patientsAllVitals',
            'doctorsList',
            'doctors',
            'medicineCategoriesList',
            'medicineCategories',
            'doseDurationList',
            'doseIntervalList',
            'mealList',
            'consultantDoctor',
            'ipdTimeline',
            'bill',
            'chargeTypes',
            'beds',
            'bedTypes',
            'departments',
            'patients',
            'operationCategory',
            'parameterList',
            'radiologyCategories',
            'pathologyCategories',
            'paymentModes',
            'currencies',
            'ipds',
            'opds',
            'caseIds',
            'case_id',
            'pathologyTestTemplates',
            'parameterRadList',
            'maternitys',
            'radiologyTestTemplates',
            'medicines'
        ));
    }

    public function storeConsultant(Request $request)
    {
        $input = $request->all();

        // Debug: Log the input data
        \Log::info('Consultant form data:', $input);

        try {
            // Validate required fields
            if (!isset($input['applied_date']) || !isset($input['doctor_id']) || !isset($input['instruction_date']) || !isset($input['instruction'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'All fields are required'
                ]);
            }

            // Save consultant instructions
            for ($i = 0; $i < count($input['applied_date']); $i++) {
                if (empty($input['applied_date'][$i]) || empty($input['instruction_date'][$i]) || empty($input['instruction'][$i])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Applied date, instruction date, and instruction are required for each row'
                    ]);
                }

                if (empty($input['doctor_id'][$i]) || $input['doctor_id'][$i] == '0') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select a doctor for each row'
                    ]);
                }

                \App\Models\MaternityConsultantRegister::create([
                    'maternity_id' => $input['maternity_patient_id'],
                    'applied_date' => $input['applied_date'][$i],
                    'doctor_id' => $input['doctor_id'][$i],
                    'instruction_date' => $input['instruction_date'][$i],
                    'instruction' => $input['instruction'][$i],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Consultant instruction saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving consultant instruction: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Maternity $maternity)
    {
        $data = $this->maternityRepository->getAssociatedData($maternity->patient_id);
        $data['maternity'] = $maternity;

        return view('maternity.edit', compact('data'));
    }

    public function update(Maternity $maternity, UpdateMaternityRequest $request)
    {
        $input = $request->all();

        try {
            $maternity = $this->maternityRepository->updateMaternity($input, $maternity);

            Flash::success(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.updated_successfully'));

            return $this->sendResponse($maternity, __('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.updated_successfully'));
        } catch (\Exception $e) {
            Flash::error('Error updating maternity patient: ' . $e->getMessage());
            return $this->sendError('Error updating maternity patient: ' . $e->getMessage());
        }
    }

    public function destroy(Maternity $maternity)
    {
        try {
            $maternity->delete();

            Flash::success(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.deleted_successfully'));

            return $this->sendSuccess(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.deleted_successfully'));
        } catch (\Exception $e) {
            Flash::error('Error deleting maternity patient: ' . $e->getMessage());
            return $this->sendError('Error deleting maternity patient: ' . $e->getMessage());
        }
    }

    public function discharge(Request $request)
    {
        \Log::info('Maternity discharge request received', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'user_roles' => auth()->user()->roles->pluck('name')->toArray()
        ]);

        $request->validate([
            'patient_id' => 'required|exists:maternity,id',
            'discharge_reason' => 'required|string',
            'discharge_notes' => 'nullable|string',
        ]);

        try {
            $maternity = Maternity::findOrFail($request->patient_id);

            \Log::info('Found maternity record', [
                'maternity_id' => $maternity->id,
                'patient_id' => $maternity->patient_id,
                'current_doctor_discharge' => $maternity->doctor_discharge
            ]);

            $maternity->update([
                'doctor_discharge' => true,
                'discharge_status' => $request->discharge_reason,
                'discharge_notes' => $request->discharge_notes,
                'discharge_date' => now(),
                'doctor_incharge' => auth()->id(),
            ]);

            \Log::info('Maternity discharge successful', [
                'maternity_id' => $maternity->id,
                'discharge_status' => $request->discharge_reason
            ]);

            Flash::success('Maternity patient discharged successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Maternity patient discharged successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Maternity discharge error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            Flash::error('Error discharging maternity patient: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error discharging maternity patient: ' . $e->getMessage()
            ]);
        }
    }

    public function markServed(Request $request)
    {
        $maternity = Maternity::findOrFail($request->id);
        $maternity->update(['served' => true]);

        Flash::success(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.updated_successfully'));

        return $this->sendSuccess(__('messages.maternity_patient.maternity_patient') . ' ' . __('messages.common.updated_successfully'));
    }

    public function getDoctorMaternityCharge(Request $request)
    {
        $doctorId = $request->get('doctor_id');

        if (empty($doctorId)) {
            return $this->sendError('Doctor ID is required');
        }

        try {
            $doctor = \App\Models\Doctor::with('doctorUser')->findOrFail($doctorId);

            // Get the doctor's maternity charge
            $doctorCharge = \App\Models\DoctorMaternityCharge::where('doctor_id', $doctorId)->first();
            $standardCharge = $doctorCharge ? $doctorCharge->standard_charge : 0;

            return $this->sendResponse([
                'standard_charge' => $standardCharge,
                'doctor_name' => $doctor->doctorUser->full_name
            ], 'Doctor charge retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving doctor charge: ' . $e->getMessage());
        }
    }

    public function getChargeMaternityCharge(Request $request)
    {
        $chargeId = $request->get('charge_id');

        if (empty($chargeId)) {
            return $this->sendError('Charge ID is required');
        }

        try {
            $charge = \App\Models\Charge::findOrFail($chargeId);

            return $this->sendResponse([
                'amount' => $charge->standard_charge
            ], 'Charge amount retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving charge amount: ' . $e->getMessage());
        }
    }

    public function createAntenatal(Request $request)
    {
        $patientId = $request->get('patient_id');
        return view('antenatal.create', compact('patientId'));
    }

    public function storeAntenatal(Request $request)
    {
        try {
            $input = $request->all();
            $input['patient_id'] = $request->get('patient_id');

            \App\Models\Antenatal::create($input);

            Flash::success('Antenatal record created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Flash::error('Error creating antenatal record: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function createPostnatal(Request $request)
    {
        $patientId = $request->get('patient_id');
        return view('postnatal.create', compact('patientId'));
    }

    public function storePostnatal(Request $request)
    {
        try {
            $input = $request->all();
            $input['patient_id'] = $request->get('patient_id');

            \App\Models\PostnatalHistory::create($input);

            Flash::success('Postnatal record created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Flash::error('Error creating postnatal record: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function createObstetric(Request $request)
    {
        $patientId = $request->get('patient_id');
        return view('maternity.obstetric.create', compact('patientId'));
    }

    public function storeObstetric(Request $request)
    {
        try {
            $input = $request->all();
            $input['patient_id'] = $request->get('patient_id');

            \App\Models\PreviousObstetricHistory::create($input);

            Flash::success('Previous Obstetric History record created successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Flash::error('Error creating obstetric history record: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function storeNursingNote(Request $request)
    {
        try {
            $input = $request->all();
            $input['patient_id'] = $request->get('patient_id');
            $input['user_id'] = $request->get('user_id', auth()->id());

            if (empty($input['patient_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient ID is required'
                ]);
            }

            \App\Models\NursingProgressNote::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Nursing progress note added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating nursing progress note: ' . $e->getMessage()
            ]);
        }
    }

    public function storePrescription(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'problem_description' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'health_insurance' => 'nullable|string',
            'low_income' => 'nullable|string',
            'reference' => 'nullable|string',
            'test' => 'nullable|string',
            'advice' => 'nullable|string',
            'medicine' => 'nullable|array',
            'dosage' => 'nullable|array',
            'day' => 'nullable|array',
            'time' => 'nullable|array',
            'dose_interval' => 'nullable|array',
            'instruction' => 'nullable|array',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'problem_description' => $request->problem_description,
            'current_medication' => $request->current_medication,
            'health_insurance' => $request->health_insurance,
            'low_income' => $request->low_income,
            'reference' => $request->reference,
            'test' => $request->test,
            'advice' => $request->advice,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        // Add medicine items if provided
        if ($request->has('medicine') && is_array($request->medicine)) {
            foreach ($request->medicine as $key => $medicineId) {
                if (!empty($medicineId)) {
                    \App\Models\PrescriptionMedicineModal::create([
                        'prescription_id' => $prescription->id,
                        'medicine' => $medicineId,
                        'dosage' => $request->dosage[$key] ?? '',
                        'day' => $request->day[$key] ?? '',
                        'time' => $request->time[$key] ?? 0,
                        'dose_interval' => $request->dose_interval[$key] ?? '',
                        'instruction' => $request->instruction[$key] ?? '',
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Prescription added successfully!'
        ]);
    }

    public function getPatientDetails(Request $request)
    {
        $patientId = $request->get('patient_id');

        if (empty($patientId)) {
            return $this->sendError('Patient ID is required');
        }

        try {
            $patient = Patient::with('patientUser')->findOrFail($patientId);
            $lastMaternity = Maternity::where('patient_id', $patientId)
                ->orderBy('created_at', 'desc')
                ->first();

            $data = [
                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->patientUser->full_name,
                    'gender' => $patient->patientUser->gender,
                ]
            ];

            if ($lastMaternity) {
                $data['last_visit'] = [
                    'height' => $lastMaternity->height,
                    'weight' => $lastMaternity->weight,
                    'bp' => $lastMaternity->bp,
                    'temperature' => $lastMaternity->temperature,
                    'respiration' => $lastMaternity->respiration,
                ];
            }

            return $this->sendResponse($data, 'Patient details retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving patient details: ' . $e->getMessage());
        }
    }
}
