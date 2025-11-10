<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PathologyTest;
use App\Models\PathologyTestTemplate;
use App\Models\PathologyTestItem;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\PatientCase;
use App\Models\OpdPatientDepartment;
use App\Models\IpdPatientDepartment;
use App\Models\LabTechnician;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class CreatePathologyTest extends Component
{
    // Form properties
    public $patient_id = '';
    public $doctor_id = '';
    public $case_id = '';
    public $opd_id = '';
    public $ipd_id = '';
    public $maternity_id = '';
    public $note = '';
    public $expected_date = '';

    // Test selection properties
    public $selected_tests = [];
    public $isSubmitting = false;

    // Data arrays
    public $doctors = [];
    public $patients = [];
    public $opds = [];
    public $ipds = [];
    public $caseIds = [];
    public $templatesForSelect = [];

    // Template details cache
    public $templateDetails = [];

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:doctors,id',
        'case_id' => 'required|exists:patient_cases,id',
        'selected_tests' => 'required|array|min:1',
        'selected_tests.*.template_id' => 'required|exists:pathology_test_templates,id',
        'selected_tests.*.report_date' => 'required|date|after_or_equal:today',
    ];

    protected $messages = [
        'patient_id.required' => 'Please select a patient.',
        'doctor_id.required' => 'Please select a doctor.',
        'case_id.required' => 'Please select a case.',
        'selected_tests.required' => 'Please add at least one test.',
        'selected_tests.min' => 'Please add at least one test.',
        'selected_tests.*.template_id.required' => 'Please select a test template.',
        'selected_tests.*.report_date.required' => 'Please set a report date.',
        'selected_tests.*.report_date.after_or_equal' => 'Report date must be today or later.',
    ];

    public function mount($patient_id = '', $opd_id = '', $ipd_id = '', $maternity_id = '', $case_id = '', $doctors = [], $patients = [], $opds = [], $ipds = [], $caseIds = [], $templatesForSelect = [])
    {
        $this->patient_id = $patient_id;
        $this->opd_id = $opd_id;
        $this->ipd_id = $ipd_id;
        $this->maternity_id = $maternity_id;
        $this->case_id = $case_id;
        $this->doctors = $doctors;
        $this->patients = $patients;
        $this->opds = $opds;
        $this->ipds = $ipds;
        $this->caseIds = $caseIds;
        $this->templatesForSelect = $templatesForSelect;

        // Add initial empty test row
        $this->addTest();
    }

    public function render()
    {
        return view('livewire.create-pathology-test');
    }

    public function addTest()
    {
        $this->selected_tests[] = [
            'template_id' => '',
            'report_days' => '',
            'report_date' => date('Y-m-d'),
            'amount' => '',
            'form_configuration' => '',
            'template_type' => ''
        ];
    }

    public function removeTest($index)
    {
        if (count($this->selected_tests) > 1) {
            unset($this->selected_tests[$index]);
            $this->selected_tests = array_values($this->selected_tests);
        }
    }

    public function updatedSelectedTests($value, $key)
    {
        // Extract the test ID and field name from the key
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $testId = $parts[0];
            $fieldName = $parts[1];

            if ($fieldName === 'template_id' && !empty($value)) {
                // Get template details and auto-fill other fields
                $template = PathologyTestTemplate::find($value);
                if ($template) {
                    $this->selected_tests[$testId]['report_days'] = $template->report_days ?? '';
                    $this->selected_tests[$testId]['amount'] = $template->standard_charge ?? '';
                    $this->selected_tests[$testId]['form_configuration'] = json_encode($template->form_configuration ?? []);

                    // Get template type
                    $formConfig = $template->form_configuration ?? [];
                    $tableType = $formConfig['table_type'] ?? 'standard';
                    $this->selected_tests[$testId]['template_type'] = $this->getTemplateTypeLabel($tableType);

                    // Calculate report date based on report days
                    if ($template->report_days && is_numeric($template->report_days)) {
                        $this->selected_tests[$testId]['report_date'] = now()->addDays($template->report_days)->format('Y-m-d');
                    }
                }
            }
        }
    }

    public function store()
    {
        try {
            // Validate basic fields
            $this->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'case_id' => 'required|exists:patient_cases,id',
                'note' => 'nullable|string',
                'expected_date' => 'nullable|date',
            ]);

            // Validate that at least one test is selected
            if (empty($this->selected_tests)) {
                session()->flash('error', 'Please add at least one test to the request.');
                return;
            }

            // Validate that all selected tests have template_id
            $validTests = 0;
            foreach ($this->selected_tests as $testData) {
                if (!empty($testData['template_id'])) {
                    $validTests++;
                }
            }

            if ($validTests === 0) {
                session()->flash('error', 'Please select at least one test template.');
                return;
            }

            $this->isSubmitting = true;

            // Calculate total amount and gather valid tests
            $totalAmount = 0;
            $validTests = [];

            foreach ($this->selected_tests as $testId => $testData) {
                if (empty($testData['template_id'])) {
                    continue;
                }

                $template = PathologyTestTemplate::find($testData['template_id']);
                if (!$template) {
                    continue;
                }

                $totalAmount += (float)$template->standard_charge;
                $validTests[] = [
                    'id' => $testId,
                    'template' => $template,
                    'report_date' => $testData['report_date'] ?? null,
                ];
            }

            // Calculate discount
            $discountPercent = (float)($this->discount_percent ?? 0);
            $totalDiscountAmount = round(($totalAmount * $discountPercent) / 100, 2);
            $grandTotal = round($totalAmount - $totalDiscountAmount, 2);

            // Generate bill number
            $billNo = $this->generateBillNumber();

            // Calculate expected_date from the earliest report_date of all tests
            $earliestReportDate = null;
            foreach ($validTests as $item) {
                if ($item['report_date']) {
                    $reportDate = \Carbon\Carbon::parse($item['report_date']);
                    if (!$earliestReportDate || $reportDate->lt($earliestReportDate)) {
                        $earliestReportDate = $reportDate;
                    }
                }
            }

            // Create the pathology test
            $pathologyTest = PathologyTest::create([
                'bill_no' => $billNo,
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'case_id' => $this->case_id,
                'opd_id' => $this->opd_id,
                'ipd_id' => $this->ipd_id,
                'maternity_id' => $this->maternity_id,
                'note' => $this->note,
                'expected_date' => $earliestReportDate,
                'total' => $totalAmount,
                'discount' => $totalDiscountAmount,
                'net_amount' => $grandTotal,
                'status' => PathologyTest::STATUS_PENDING,
                'created_by' => Auth::id(),
            ]);

            // Create pathology test items
            foreach ($validTests as $testData) {
                $template = $testData['template'];

                PathologyTestItem::create([
                    'pathology_test_id' => $pathologyTest->id,
                    'pathology_test_template_id' => $template->id,
                    'report_date' => $testData['report_date'],
                    'created_by' => Auth::id(),
                ]);
            }

            $this->isSubmitting = false;

            Flash::success(__('messages.pathology_tests').' '.__('messages.common.created_successfully'));

            // Emit event for success
            $this->emit('testRequestCreated', [
                'id' => $pathologyTest->id,
                'bill_no' => $pathologyTest->bill_no
            ]);

        } catch (\Exception $e) {
            $this->isSubmitting = false;

            // Log the error
            \Log::error('Pathology test creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            session()->flash('error', 'Error creating pathology test request: ' . $e->getMessage());

            // Emit event for failure
            $this->emit('validationFailed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function generateBillNumber()
    {
        $prefix = 'PT';
        $lastTest = PathologyTest::orderBy('id', 'desc')->first();

        if ($lastTest) {
            $lastNumber = (int)substr($lastTest->bill_no, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
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

        return $labels[$templateType] ?? 'Unknown';
    }
}
