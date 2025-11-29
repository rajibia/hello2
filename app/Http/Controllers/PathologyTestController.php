<?php

namespace App\Http\Controllers;

use App\Exports\PathologyTestExport;
use App\Http\Requests\CreatePathologyTestRequest;
use App\Http\Requests\UpdatePathologyTestRequest;
use App\Models\Charge;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\PatientCase;
use App\Models\Doctor;
use App\Models\PathologyTestItem;
use App\Models\PathologyTest;
use App\Models\Patient;
use App\Models\PathologyTestTemplate;
use App\Models\PathologyCase;
use App\Models\LabTechnician;
use App\Repositories\InvoiceRepository;
use App\Repositories\PathologyTestRepository;
use App\Repositories\PathologyTestTemplateRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;
use Illuminate\Http\Request;

class PathologyTestController extends AppBaseController
{
    /** @var PathologyTestRepository */
    private $pathologyTestRepository;
    private $pathologyTestTemplateRepository;
    private $invoiceRepository;

    public function __construct(PathologyTestRepository $pathologyTestRepo, InvoiceRepository $invoiceRepo, PathologyTestTemplateRepository $pathologyTestTemplateRepo)
    {
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->invoiceRepository = $invoiceRepo;
        $this->pathologyTestTemplateRepository = $pathologyTestTemplateRepo;
    }

    public function index()
    {
        return view('pathology_tests.index');
    }

    public function create(Request $request)
    {
        // Fetch dynamic pathology test templates with detailed information
        $pathologyTestTemplates = PathologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'category_id', 'charge_category_id', 'standard_charge', 'report_days', 'form_configuration')
            ->with(['pathologycategory', 'chargecategory'])
            ->get();

        // Format templates for select dropdown with additional info
        $templatesForSelect = [];
        foreach ($pathologyTestTemplates as $template) {
            $templateType = $template->form_configuration['table_type'] ?? 'standard';
            $templateTypeLabel = $this->getTemplateTypeLabel($templateType);
            $templatesForSelect[$template->id] = $template->test_name . ' (' . $templateTypeLabel . ')';
        }

        $doctors = $this->pathologyTestRepository->getDoctors();
        $patients = $this->pathologyTestRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');
        $labTechnicians = LabTechnician::with('user')->get()->pluck('user.full_name', 'id')->toArray();

        $patient_id = $request->query()['ref_p_id'] ?? '';
        $opd_id = $request->query()['ref_opd_id'] ?? '';
        $ipd_id = $request->query()['ref_ipd_id'] ?? '';
        $maternity_id = $request->query()['ref_maternity_id'] ?? '';

        if($opd_id != '')
        {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '')
        {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }
        if($maternity_id != '')
        {
            $patient_id = \App\Models\MaternityPatient::where('id', $maternity_id)->pluck('patient_id')->first();
        }

        $case_id = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->latest('created_at')->pluck('id')->first() : '';
        $caseIds = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->pluck('case_id', 'id')->toArray()
                                    : PatientCase::pluck('case_id', 'id')->toArray();

        return view('pathology_tests.create', compact('pathologyTestTemplates', 'templatesForSelect', 'doctors', 'patients', 'opds', 'ipds', 'case_id', 'caseIds', 'patient_id', 'opd_id', 'ipd_id', 'maternity_id', 'labTechnicians'));
    }

    private function getTemplateTypeLabel($templateType)
    {
        $labels = [
            'standard' => 'Standard',
            'simple' => 'Simple',
            'specimen' => 'Specimen',
            'species_dependent' => 'Species Dependent',
            'field_value_multi' => 'Field-Value Multi'
        ];

        return $labels[$templateType] ?? 'Standard';
    }

    private function getTemplateTypeBadgeClass($templateType)
    {
        $classes = [
            'standard' => 'bg-primary',
            'simple' => 'bg-success',
            'specimen' => 'bg-warning',
            'species_dependent' => 'bg-info',
            'field_value_multi' => 'bg-secondary'
        ];

        return $classes[$templateType] ?? 'bg-primary';
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Manual validation
        $validator = \Validator::make($input, [
            'patient_id' => 'required|exists:patients,id',
            'opd_id' => 'nullable|exists:opd_patient_departments,id',
            'ipd_id' => 'nullable|exists:ipd_patient_departments,id',
            'maternity_id' => 'nullable|exists:maternity_patients,id',
            'case_id' => 'required|exists:patient_cases,id',
            'template_id' => 'required|array',
            'template_id.*' => 'required|exists:pathology_test_templates,id',
            'report_date' => 'required|array',
            'report_date.*' => 'required|date_format:Y-m-d',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            Flash::error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validate that at least one template is selected
        if (empty($input['template_id']) || !is_array($input['template_id'])) {
            Flash::error('Please select at least one test template.');
            return redirect()->back()->withInput();
        }

        // Filter out empty template selections
        $input['template_id'] = array_filter($input['template_id']);
        if (empty($input['template_id'])) {
            Flash::error('Please select at least one test template.');
            return redirect()->back()->withInput();
        }

        // Ensure report_date array matches template_id array
        if (count($input['template_id']) !== count($input['report_date'])) {
            Flash::error('Report date is required for each selected test.');
            return redirect()->back()->withInput();
        }

        // Filter out empty report dates
        $input['report_date'] = array_filter($input['report_date']);
        if (count($input['template_id']) !== count($input['report_date'])) {
            Flash::error('Report date is required for each selected test.');
            return redirect()->back()->withInput();
        }

        try {
            $this->pathologyTestRepository->store($input);

            Flash::success(__('messages.pathology_tests').' '.__('messages.common.saved_successfully'));

            if(isset($input['create_from_route'])) {
                if ($input['create_from_route'] == 'patient') {
                    return redirect(route('patients.show', $input['patient_id']));
                } elseif ($input['create_from_route'] == 'opd') {
                    // Redirect to the specific OPD patient show page (renders show_fields)
                    $opd_id = $input['opd_id'] ?? null;
                    if ($opd_id) {
                        return redirect(route('opd.patient.show', $opd_id))->with('success', __('messages.pathology_tests').' '.__('messages.common.saved_successfully'));
                    }

                    // Fallback: if no opd_id provided, go to OPD index
                    return redirect(route('opd.patient.index', ['filter' => 'upcoming']))->with('success', __('messages.pathology_tests').' '.__('messages.common.saved_successfully'));
                } else if ($input['create_from_route'] == 'ipd') {
                    // Redirect to the specific IPD patient show page (renders show_fields)
                    $ipd_id = $input['ipd_id'] ?? null;
                    if ($ipd_id) {
                        return redirect(route('ipd.patient.show', $ipd_id))->with('success', __('messages.pathology_tests').' '.__('messages.common.saved_successfully'));
                    }

                    // Fallback to previous redirect if ipd_id is missing
                    return redirect()->back()->with('success', __('messages.pathology_tests').' '.__('messages.common.saved_successfully'));
                } else if ($input['create_from_route'] == 'maternity') {
                    return redirect()->back()->with('success', __('messages.pathology_tests').' '.__('messages.common.saved_successfully'));
                }
            }

            return redirect(route('pathology.test.index'));
        } catch (\Exception $e) {
            Flash::error('Error creating pathology test: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function show(PathologyTest $pathologyTest)
    {
        $pathologyTest->load(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser', 'performed_by_user']);

        return view('pathology_tests.show', compact('pathologyTest'));
    }





    // In App\Http\Controllers\PathologyTestController.php

/**
 * Get all completed pathology tests for a given OPD patient department.
 * @param int $opdId The ID of the OPD Patient Department.
 * @return \Illuminate\Http\JsonResponse
 */
public function getPatientCompletedPathologyTests(int $opdId)
{
    // Find the OPD record
    $opdPatientDepartment = OpdPatientDepartment::find($opdId);

    if (!$opdPatientDepartment) {
        return response()->json([
            'success' => false,
            'message' => 'OPD record not found.'
        ], 404);
    }

    $patientId = $opdPatientDepartment->patient_id;

    // Fetch pathology tests for the patient, only including tests that have results/reports (status is 'completed' or similar)
    // Assuming a STATUS_COMPLETED constant or a field indicating results are available.
    // I'll modify this query to focus on tests that have been updated with results,
    // which usually means the `test_results` field is not null or empty.
    $pathologyTests = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'doctor.doctorUser'])
        ->where('patient_id', $patientId)
        ->whereNotNull('test_results') // Assuming test_results being populated means it's completed
        ->latest()
        ->get();

    $reports = $pathologyTests->map(function ($test) {
        $testNames = $test->pathologyTestItems->pluck('pathologytesttemplate.test_name')->filter()->toArray();

        return [
            'id' => $test->id,
            'bill_no' => $test->bill_no,
            'lab_number' => $test->lab_number ?? 'N/A',
            'test_name' => implode(', ', $testNames),
            'report_date' => $test->updated_at ? $test->updated_at->format('Y-m-d H:i:s') : 'N/A',
            'requested_by' => $test->doctor->doctorUser->full_name ?? 'N/A',
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $reports,
        'message' => 'Completed pathology tests retrieved successfully.'
    ]);
}


    public function showModal(PathologyTest $pathologyTest)
    {
        $pathologyTest->load(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser', 'performed_by_user']);

        // Calculate age
        $age = null;
        if ($pathologyTest->patient && $pathologyTest->patient->patientUser && $pathologyTest->patient->patientUser->dob) {
            $age = \Carbon\Carbon::parse($pathologyTest->patient->patientUser->dob)->age;
        }

        // Get test names for requested tests
        $testNames = [];
        if ($pathologyTest->pathologyTestItems && $pathologyTest->pathologyTestItems->count() > 0) {
            $testNames = $pathologyTest->pathologyTestItems->pluck('pathologytesttemplate.test_name')->filter()->toArray();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pathologyTest->id,
                'bill_no' => $pathologyTest->bill_no,
                'lab_number' => $pathologyTest->lab_number ?? $pathologyTest->bill_no,
                'patient_name' => strtoupper($pathologyTest->patient->patientUser->full_name ?? 'N/A'),
                'age' => $age ?? 0,
                'sex' => $pathologyTest->patient->patientUser->gender ? 'F' : 'M',
                'diagnosis' => strtoupper($pathologyTest->diagnosis ?? 'N/A'),
                'test_requested' => strtoupper(implode(', ', $testNames)),
                'doctor' => strtoupper($pathologyTest->doctor->doctorUser->full_name ?? 'N/A'),
                'performed_by' => strtoupper($pathologyTest->performed_by_user->full_name ?? $pathologyTest->performed_by_user->name ?? 'N/A'),
                'created_on' => $pathologyTest->created_at ? $pathologyTest->created_at->format('d/m/Y') : 'N/A',
                'test_items' => $pathologyTest->pathologyTestItems->map(function($item) use ($pathologyTest) {
                    $template = $item->pathologytesttemplate;

                    // Ensure form_configuration is properly decoded from JSON
                    $formConfig = [];
                    if ($template && $template->form_configuration) {
                        if (is_string($template->form_configuration)) {
                            $formConfig = json_decode($template->form_configuration, true) ?? [];
                        } elseif (is_array($template->form_configuration)) {
                            $formConfig = $template->form_configuration;
                        }
                    }

                    $testResults = $pathologyTest->test_results[$item->id] ?? [];

                    // Get template type information
                    $tableType = $formConfig['table_type'] ?? 'standard';
                    $templateTypeLabel = $this->getTemplateTypeLabel($tableType);
                    $templateTypeClass = $this->getTemplateTypeBadgeClass($tableType);

                    $testItemData = [
                        'test_name' => strtoupper($template->test_name ?? 'N/A'),
                        'test_type' => strtoupper($template->test_type ?? 'N/A'),
                        'template_type' => $tableType,
                        'template_type_badge' => $templateTypeLabel,
                        'template_type_class' => $templateTypeClass,
                        'form_configuration' => $formConfig,
                        'test_results' => $testResults,
                        'result' => null,
                        'reference_range' => null,
                        'flag' => null,
                        'flag_class' => null,
                    ];

                    // If there's form configuration, get the first field's result for backward compatibility
                    if (!empty($formConfig) && isset($formConfig['fields']) && is_array($formConfig['fields']) && count(array_filter($formConfig['fields'])) > 0) {
                        $firstField = array_filter($formConfig['fields'])[0]; // Use array_filter to skip empty fields that might exist from form structure
                        if (is_array($firstField) && isset($firstField['name'])) {
                            $result = $testResults[$firstField['name']] ?? null;

                            if ($result !== null && $result !== '') {
                                $testItemData['result'] = strtoupper($result);

                                // Calculate flag if reference range exists
                                $min = $firstField['reference_min'] ?? null;
                                $max = $firstField['reference_max'] ?? null;

                                if ($min !== null && $max !== null && is_numeric($result)) {
                                    $resultValue = floatval($result);
                                    $minValue = floatval($min);
                                    $maxValue = floatval($max);

                                    $testItemData['reference_range'] = $min . ' - ' . $max;

                                    if ($resultValue < $minValue) {
                                        $testItemData['flag'] = 'LOW';
                                        $testItemData['flag_class'] = 'flag-low';
                                    } elseif ($resultValue > $maxValue) {
                                        $testItemData['flag'] = 'HIGH';
                                        $testItemData['flag_class'] = 'flag-high';
                                    } else {
                                        $testItemData['flag'] = 'NORMAL';
                                        $testItemData['flag_class'] = 'flag-normal';
                                    }
                                }
                            }
                        }
                    }

                    return $testItemData;
                })->toArray(),
            ],
            'message' => 'Pathology test details retrieved successfully.'
        ]);
    }

    public function edit(PathologyTest $pathologyTest, Request $request)
    {
        // Fetch dynamic pathology test templates
        $pathologyTestTemplates = PathologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'category_id', 'charge_category_id', 'standard_charge', 'form_configuration')
            ->with(['pathologycategory', 'chargecategory'])
            ->get();

        $doctors = $this->pathologyTestRepository->getDoctors();
        $patients = $this->pathologyTestRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');
        $labTechnicians = LabTechnician::all()->pluck('name', 'id');

        $doctor_id = $pathologyTest->doctor_id;
        $doctor_name = Doctor::where('id', $pathologyTest->doctor_id)->first();
        $doctor_name = $doctor_name->doctorUser->fullname;

        $patient_id = $request->query()['ref_p_id'] ?? '';
        $opd_id = $request->query()['ref_opd_id'] ?? '';
        $ipd_id = $request->query()['ref_ipd_id'] ?? '';

        // Extract the first number (60.00)
        $amount = (float) filter_var($pathologyTest->discount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Extract the percentage, including optional decimals (e.g., 50.00%)
        preg_match('/\((\d+(\.\d{1,2})?)%\)/', $pathologyTest->discount, $matches);
        $percentage = isset($matches[1]) ? (float) $matches[1] : null;

        if($opd_id != '')
        {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '')
        {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }

        $case_id = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->latest('created_at')->pluck('id')->first() : '';
        $caseIds = $patient_id !== '' ? PatientCase::where('patient_id', $patient_id)->pluck('case_id', 'id')->toArray()
                                    : PatientCase::pluck('case_id', 'id')->toArray();

        return view('pathology_tests.edit', compact('pathologyTest', 'pathologyTestTemplates', 'doctors', 'patients', 'opds', 'ipds', 'case_id', 'caseIds', 'patient_id', 'opd_id', 'ipd_id', 'doctor_name', 'amount', 'percentage', 'labTechnicians'));
    }

    public function update(PathologyTest $pathologyTest, UpdatePathologyTestRequest $request)
    {
        $input = $request->all();

        $this->pathologyTestRepository->update($input, $pathologyTest->id);

        Flash::success(__('messages.pathology_tests').' '.__('messages.common.updated_successfully'));

        return redirect(route('pathology.test.index'));
    }

    public function destroy(PathologyTest $pathologyTest)
    {
        $this->pathologyTestRepository->delete($pathologyTest->id);

        return $this->sendSuccess(__('messages.pathology_tests').' '.__('messages.common.deleted_successfully'));
    }

    public function convertToPDF($id)
    {
        try {
        $pathologyTest = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser', 'performed_by_user'])->findOrFail($id);

        $data = [
            'pathologyTest' => $pathologyTest,
            'patient' => $pathologyTest->patient,
            'doctor' => $pathologyTest->doctor,
            'performedBy' => $pathologyTest->performed_by_user,
            'testResults' => $pathologyTest->test_results ?? [],
        ];

            // Configure PDF options
        $pdf = PDF::loadView('pathology_tests.pathology_test_pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('defaultFont', 'DejaVu Sans');

            $filename = 'pathology_test_' . ($pathologyTest->lab_number ?? $pathologyTest->bill_no ?? $pathologyTest->id) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF generation error: ' . $e->getMessage());
            \Log::error('PDF generation stack trace: ' . $e->getTraceAsString());

            Flash::error('Error generating PDF: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function acceptTest($id)
    {
        try {
            $pathologyTest = PathologyTest::findOrFail($id);

            // Check if user is authorized (Lab Technician or Admin)
            if (!auth()->user()->hasRole(['Lab Technician', 'Admin'])) {
                Flash::error('You are not authorized to accept test requests.');
                return redirect()->back();
            }

            // Check if test is in pending status
            if ($pathologyTest->status !== PathologyTest::STATUS_PENDING) {
                Flash::error('Only pending tests can be accepted.');
                return redirect()->back();
            }

            // Accept the test
            $pathologyTest->acceptByLabTechnician();

            Flash::success('Pathology test request accepted successfully. Status changed to In Progress.');
            return redirect()->back();

        } catch (\Exception $e) {
            Flash::error('Error accepting test request: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function export()
    {
        return Excel::download(new PathologyTestExport, 'pathology_tests.xlsx');
    }

    public function getPathologyTemplate()
    {
        $pathologyTestTemplates = PathologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'category_id', 'charge_category_id', 'standard_charge')
            ->with(['pathologycategory', 'chargecategory'])
            ->get();

        return $this->sendResponse($pathologyTestTemplates, 'Pathology templates retrieved successfully.');
    }

    public function getTemplateFormConfiguration($templateId)
    {
        $template = PathologyTestTemplate::findOrFail($templateId);

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
}