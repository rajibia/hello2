<?php

namespace App\Http\Controllers;

use App\Exports\RadiologyTestExport;
use App\Http\Requests\CreateRadiologyTestRequest;
use App\Http\Requests\UpdateRadiologyTestRequest;
use App\Models\Charge;
use App\Models\IpdPatientDepartment;
use App\Models\MaternityPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\PatientCase;
use App\Models\Doctor;
use App\Models\RadiologyParameter;
use App\Models\RadiologyParameterItem;
use App\Models\RadiologyTestItem;
use App\Models\RadiologyTest;
use App\Models\Patient;
use App\Models\RadiologyTestTemplate;
use App\Models\RadiologyCase;
use App\Repositories\InvoiceRepository;
use App\Repositories\RadiologyTestRepository;
use App\Repositories\RadiologyTestTemplateRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;
use Illuminate\Http\Request;

class RadiologyTestController extends AppBaseController
{
    /** @var RadiologyTestRepository */
    private $radiologyTestRepository;
    private $radiologyTestTemplateRepository;
    private $invoiceRepository;

    public function __construct(RadiologyTestRepository $radiologyTestRepo, InvoiceRepository $invoiceRepo, RadiologyTestTemplateRepository $radiologyTestTemplateRepo)
    {
        $this->radiologyTestRepository = $radiologyTestRepo;
        $this->invoiceRepository = $invoiceRepo;
        $this->radiologyTestTemplateRepository = $radiologyTestTemplateRepo;
    }

    public function index()
    {
        return view('radiology_tests.index');
    }

    public function create(Request $request)
    {
        // Fetch dynamic radiology test templates
        $radiologyTestTemplates = RadiologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'category_id', 'charge_category_id', 'standard_charge', 'form_configuration')
            ->with(['radiologycategory', 'chargecategory'])
            ->get();

        // Format templates for select dropdown
        $templatesForSelect = $radiologyTestTemplates->pluck('test_name', 'id')->toArray();

        $data = $this->radiologyTestRepository->getRadiologyAssociatedData();
        $doctors = $this->radiologyTestRepository->getDoctors();

        $patient_id = $request->query()['ref_p_id'] ?? '';
        $opd_id = $request->query()['ref_opd_id'] ?? '';
        $ipd_id = $request->query()['ref_ipd_id'] ?? '';
        $maternity_id = $request->query()['ref_maternity_id'] ?? '';

        if($opd_id != '') {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '') {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }
        if($maternity_id != '') {
            $patient_id = MaternityPatientDepartment::where('id', $maternity_id)->pluck('patient_id')->first();
        }

        $case_id = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->latest('created_at')->pluck('id')->first() : '';
        $caseIds = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->pluck('case_id', 'id')->toArray() : PatientCase::pluck('case_id', 'id')->toArray();

        $parameterList = $this->radiologyTestRepository->getParameterDataList();
        $patients = $this->radiologyTestRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        $maternitys = MaternityPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('id', 'id');
        return view('radiology_tests.create', compact('data', 'parameterList', 'opds', 'ipds', 'maternitys', 'case_id', 'caseIds', 'patients', 'patient_id', 'opd_id', 'ipd_id', 'maternity_id', 'radiologyTestTemplates', 'templatesForSelect', 'doctors'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Manual validation
        $validator = \Validator::make($input, [
            'patient_id' => 'required|exists:patients,id',
            'opd_id' => 'nullable|exists:opd_patient_departments,id',
            'ipd_id' => 'nullable|exists:ipd_patient_departments,id',
            'maternity_id' => 'nullable|exists:maternity,id',
            'case_id' => 'required|exists:patient_cases,id',
            'template_id' => 'required|array',
            'template_id.*' => 'required|exists:radiology_test_templates,id',
            'report_date' => 'required|array',
            'report_date.*' => 'required|date_format:Y-m-d',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            Flash::error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';
        $maternity_id = $input['maternity_id'] ?? '';

        // Handle template_id array - take the first one for now
        if (isset($input['template_id']) && is_array($input['template_id'])) {
            $input['template_id'] = $input['template_id'][0] ?? null;
        }

        // Validate template selection
        if (empty($input['template_id'])) {
            Flash::error('Please select a test template.');
            return redirect()->back();
        }

        // Handle report_date array - take the first one for now
        if (isset($input['report_date']) && is_array($input['report_date'])) {
            $input['report_date'] = $input['report_date'][0] ?? null;
        }

        // Debug: Log the report_date value
        \Log::info('Report date received:', ['report_date' => $input['report_date'] ?? 'null']);

        // Validate report date
        if (empty($input['report_date'])) {
            Flash::error(__('messages.new_change.report_date_required'));
            return redirect()->back();
        }

        // Ensure report_date is in proper format
        try {
            $reportDate = \Carbon\Carbon::parse($input['report_date']);
            $input['report_date'] = $reportDate->format('Y-m-d');
            \Log::info('Report date formatted:', ['formatted_date' => $input['report_date']]);
        } catch (\Exception $e) {
            \Log::error('Date parsing error:', ['error' => $e->getMessage(), 'date' => $input['report_date']]);
            Flash::error('Invalid report date format. Please use YYYY-MM-DD format.');
            return redirect()->back();
        }

        // Handle form_configuration array
        if (isset($input['form_configuration']) && is_array($input['form_configuration'])) {
            $input['form_configuration'] = $input['form_configuration'][0] ?? null;
        }

        // Initialize test_results as empty array
        $input['test_results'] = [];

        $this->radiologyTestRepository->store($input);

        Flash::success(__('messages.radiology_tests') . ' ' . __('messages.common.saved_successfully'));

        if(isset($input['create_from_route'])) {
            if ($input['create_from_route'] == 'patient') {
                return redirect(route('patients.show', $input['patient_id']));
            } elseif ($input['create_from_route'] == 'opd') {
                return $this->sendSuccess('message ' . __('messages.common.updated_successfully'));
                // return redirect(route('opd.patient.show', $input['opd_id']));
            } else if ($input['create_from_route'] == 'ipd') {
                return $this->sendSuccess('message ' . __('messages.common.updated_successfully'));
            } else if ($input['create_from_route'] == 'maternity') {
                return $this->sendSuccess('message ' . __('messages.common.updated_successfully'));
            }
        }

        return redirect(route('radiology.test.index'));
    }

    public function show(RadiologyTest $radiologyTest)
    {
        $radiologyParameterItems = RadiologyParameterItem::with('radiologyTest', 'radiologyParameter.radiologyUnit')->whereRadiologyId($radiologyTest->id)->get();

        return view('radiology_tests.show', compact('radiologyTest', 'radiologyParameterItems'));
    }

    public function edit(RadiologyTest $radiologyTest, Request $request)
    {
        $radiologyTestTemplates = $this->radiologyTestRepository->getRadiologyTemplate();
        $doctors = $this->radiologyTestRepository->getDoctors();
        $doctor_id = $radiologyTest->doctor_id;
        $doctor_name = Doctor::where('id', $radiologyTest->doctor_id)->first()->doctorUser->fullname;

        $data = $this->radiologyTestRepository->getRadiologyAssociatedData();
        $patient_id = $request->query()['ref_p_id'] ?? '';
        $opd_id = $request->query()['ref_opd_id'] ?? '';
        $ipd_id = $request->query()['ref_ipd_id'] ?? '';

        $amount = (float) filter_var($radiologyTest->discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // Extract the percentage, including optional decimals (e.g., 50.00%)
        preg_match('/\((\d+(\.\d{1,2})?)%\)/', $radiologyTest->discount, $matches);
        $percentage = isset($matches[1]) ? (float) $matches[1] : null;

        if($opd_id != '') {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '') {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }

        $radiologyTestItems = RadiologyTestItem::with('radiologytesttemplate')->whereRadiologyId($radiologyTest->id)->get();
        $case_id = $radiologyTest->case_id;
        $caseIds = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->pluck('case_id', 'id')->toArray() : PatientCase::pluck('case_id', 'id')->toArray();

        $parameterList = $this->radiologyTestRepository->getParameterDataList();
        $patients = $this->radiologyTestRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('radiology_tests.edit', compact('data', 'parameterList', 'amount', 'radiologyTestItems', 'percentage', 'doctor_id', 'doctor_name', 'opds', 'ipds', 'case_id', 'caseIds', 'radiologyTest', 'patients', 'patient_id', 'opd_id', 'ipd_id', 'radiologyTestTemplates', 'doctors'));
    }

    public function update(RadiologyTest $radiologyTest, Request $request)
    {
        $input = $request->all();

        $this->radiologyTestRepository->update($input, $radiologyTest);

        Flash::success(__('messages.radiology_tests') . ' ' . __('messages.common.updated_successfully'));

        if(isset($input['create_from_route'])) {
            if ($input['create_from_route'] == 'patient') {
                return redirect(route('patients.show', $input['patient_id']));
            } elseif ($input['create_from_route'] == 'opd') {
                // return redirect(route('opd.patient.show', $input['opd_id']));
                return $this->sendSuccess('message ' . __('messages.common.updated_successfully'));
            } else if ($input['create_from_route'] == 'ipd') {
                return $this->sendSuccess('message ' . __('messages.common.updated_successfully'));
            }
        }

        return redirect(route('radiology.test.index'));
    }

    public function destroy(RadiologyTest $radiologyTest)
    {
        $radiologyTest->parameterItems()->delete();
        $radiologyTest->delete();

        return $this->sendSuccess(__('messages.radiology_tests') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getStandardCharge($id)
    {
        $standardCharges = Charge::where('charge_category_id', $id)->value('standard_charge');

        return $this->sendResponse($standardCharges, 'StandardCharge retrieved successfully.');
    }

    public function radiologyTestExport()
    {
        return Excel::download(new RadiologyTestExport, 'radiology-tests-' . time() . '.xlsx');
    }





    public function showModal(RadiologyTest $radiologyTest)
{
    $radiologyTest->load(['radiologycategory', 'chargecategory', 'opd', 'ipd', 'patientcase', 'patient.patientUser', 'doctor.doctorUser', 'radiologyitem']);

    $currency = $radiologyTest->currency_symbol ? strtoupper($radiologyTest->currency_symbol) : strtoupper(getCurrentCurrency());
    $radiologyTestItems = RadiologyTestItem::with('radiologytest', 'radiologytesttemplate', 'lab_technician.user', 'approved_by.user')->whereRadiologyId($radiologyTest->id)->get();
    // Extract the first number (60.00)
    $amount = (float) filter_var($radiologyTest->discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Extract the percentage, including optional decimals (e.g., 50.00%)
    preg_match('/\((\d+(\.\d{1,2})?)%\)/', $radiologyTest->discount, $matches);
    $percentage = isset($matches[1]) ? (float) $matches[1] : null;



    $radiologyTestTemplates = $this->radiologyTestTemplateRepository->getRadiologyTemplate();

    $radiologyTest = [
        'bill_no' => $radiologyTest->bill_no,
        'note' => $radiologyTest->note,
        'previous_report_value' => $radiologyTest->previous_report_value,
        'total' => number_format(floatval($radiologyTest->total), 2),
        'amount_paid' => number_format(floatval($radiologyTest->amount_paid), 2),
        'balance' => number_format(floatval($radiologyTest->balance), 2),
        'discount' => $radiologyTest->discount,
        'discount_amount' => $amount,
        'discount_percent' => $percentage,
        'status' => $radiologyTest->status,
        'ipd' => $radiologyTest->ipd->ipd_number ?? 'N/A',
        'opd' => $radiologyTest->opd->opd_number ?? 'N/A',
        'radiologyTestItems' => $radiologyTestItems,
        'radiologyTestTemplates' => $radiologyTestTemplates,
        'patient' => $radiologyTest->patient->patientUser->full_name,
        'patient_id' => $radiologyTest->patient_id,
        'case_id' => $radiologyTest->case_id,
        'case' => $radiologyTest->patientcase->case_id,
        'opd_id' => $radiologyTest->opd_id ?? '',
        'ipd_id' => $radiologyTest->ipd_id ?? '',
        'doctor_id' => $radiologyTest->doctor_id ?? '',
        'doctor' => $radiologyTest->doctor->doctorUser->full_name,
        'created_at' => $radiologyTest->created_at,
        'updated_at' => $radiologyTest->updated_at,
    ];

    return $this->sendResponse($radiologyTest, 'Radiology Test Retrieved Successfully.');
}

public function getRadiologyParameter($id)
{
    $data = [];
    $data['parameter'] = RadiologyParameter::with('radiologyUnit')->whereId($id)->first();

    return $this->sendResponse($data, 'retrieved');
}

public function getPatientCaseDetails($patientId)
{
    $data = [];
    $case_id = PatientCase::where('patient_id', $patientId)->latest('created_at')->pluck('id')->first();
    $caseIds = PatientCase::where('patient_id', $patientId)->pluck('case_id', 'id')->toArray();

    $data['case_id'] = $case_id;
    $data['caseIds'] = $caseIds;

    return $this->sendResponse($data, 'retrieved');
}

public function convertToPDF($id)
{
    $radiologyTest = RadiologyTest::with(['radiologyTestItems.radiologytesttemplate', 'patient.patientUser', 'doctor.doctorUser', 'performed_by_user'])->findOrFail($id);
    $data = [
        'radiologyTest' => $radiologyTest,
        'patient' => $radiologyTest->patient,
        'doctor' => $radiologyTest->doctor,
        'performedBy' => $radiologyTest->performed_by_user,
        'testResults' => $radiologyTest->test_results ?? [],
    ];
    $pdf = PDF::loadView('radiology_tests.radiology_test_pdf', $data);
    return $pdf->download('radiology_test_'.$radiologyTest->bill_no.'.pdf');
}

public function getTemplateFormConfiguration($templateId)
{
    $template = RadiologyTestTemplate::findOrFail($templateId);

    $data = [
        'id' => $template->id,
        'test_name' => $template->test_name,
        'short_name' => $template->short_name,
        'test_type' => $template->test_type,
        'standard_charge' => $template->standard_charge,
        'report_days' => $template->report_days,
        'form_configuration' => $template->form_configuration ?? [],
    ];

    return response()->json([
        'success' => true,
        'data' => $data,
        'message' => 'Template form configuration retrieved successfully.'
    ]);
}

public function acceptTest($id)
{
    try {
        $radiologyTest = RadiologyTest::findOrFail($id);

        // Check if user is authorized (Lab Technician or Admin)
        if (!auth()->user()->hasRole(['Lab Technician', 'Admin'])) {
            Flash::error('You are not authorized to accept test requests.');
            return redirect()->back();
        }

        // Check if test is in pending status
        if ($radiologyTest->status !== RadiologyTest::STATUS_PENDING) {
            Flash::error('Only pending tests can be accepted.');
            return redirect()->back();
        }

        // Accept the test
        $radiologyTest->acceptByLabTechnician();

        Flash::success('Radiology test request accepted successfully. Status changed to In Progress.');
        return redirect()->back();

    } catch (\Exception $e) {
        Flash::error('Error accepting test request: ' . $e->getMessage());
        return redirect()->back();
    }
}
}
