<?php

namespace App\Http\Livewire;

use App\Models\PathologyTest;
use App\Models\PathologyTestTemplate;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\LabTechnician;
use App\Models\PatientCase;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class PathologyTestTable extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showNewRequestModal = false;
    public $showResultsModal = false;
    public $editingTest = null;
    public $selectedTest = null;
    public $isSubmitting = false;
    public $modalMessage = '';
    public $modalMessageType = '';

    // Form fields
    public $patient_id = '';
    public $doctor_id = '';
    public $template_id = '';
    public $case_id = '';
    public $note = '';
    public $discount_percent = 0; // Added discount percent for request-level discount
    public $expected_date = '';
    public $test_results = [];
    public $diagnosis = ''; // Add diagnosis field
    public $performed_by = ''; // Add performed by field
    public $ipdId = null; // Add IPD ID for context detection

    // Multiple tests support
    public $selected_tests = []; // Array to store multiple test selections
    public $test_details = []; // Array to store details for each test (report days, amount, etc.)
    public $next_test_id = 1; // Counter for generating unique test IDs

    // Edit modal multiple tests support
    public $edit_selected_tests = []; // Array to store multiple test selections for edit
    public $next_edit_test_id = 1; // Counter for generating unique test IDs for edit

    // Dynamic form fields
    public $form_configuration = [];
    public $available_templates = [];
    public $available_cases = []; // Add this for case dropdown
    public $incomingTest = null;

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:doctors,id',
        'template_id' => 'required|exists:pathology_test_templates,id',
        'case_id' => 'required|exists:patient_cases,id',
        'note' => 'nullable|string',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'expected_date' => 'nullable|date',
        'selectedTest.lab_number' => 'nullable|string|max:255',
        'diagnosis' => 'nullable|string|max:500',
    ];

    protected $listeners = [
        'testRequestCreated' => 'onTestRequestCreated',
    ];

    protected $messages = [
        'patient_id.required' => 'Patient is required.',
        'doctor_id.required' => 'Doctor is required.',
        'template_id.required' => 'Test template is required.',
        'case_id.required' => 'Case is required.',
    ];

    public function mount($ipdId = null)
    {
        $this->ipdId = $ipdId;
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->available_templates = PathologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'standard_charge', 'form_configuration')
            ->with(['pathologycategory', 'chargecategory'])
            ->get();
    }

    public function updatedTemplateId($value)
    {
        if (!empty($value)) {
            $template = PathologyTestTemplate::find($value);
            if ($template) {
                $this->form_configuration = $template->form_configuration ?? [];
                // Initialize test_results with empty values for each field
                $this->test_results = [];
                foreach ($this->form_configuration as $field) {
                    $this->test_results[$field['name']] = '';
                }

                // Auto-fill expected_date based on template's report_days
                if ($template->report_days && is_numeric($template->report_days)) {
                    $this->expected_date = now()->addDays($template->report_days)->format('Y-m-d');
                }
            }
        } else {
            $this->form_configuration = [];
            $this->test_results = [];
        }
    }

    public function updatedPatientId($value)
    {
        if (!empty($value)) {
            // Load cases for the selected patient
            $this->available_cases = PatientCase::where('patient_id', $value)
                ->select('id', 'case_id')
                ->get()
                ->pluck('case_id', 'id')
                ->toArray();

            // Reset case_id when patient changes
            $this->case_id = '';
        } else {
            $this->available_cases = [];
            $this->case_id = '';
        }
    }

    public function addTest()
    {
        $testId = $this->next_test_id;
        $this->selected_tests[$testId] = [
            'template_id' => '',
            'report_days' => '',
            'report_date' => '',
            'amount' => ''
        ];
        $this->next_test_id++;
    }

    public function removeTest($testId)
    {
        // Prevent removing the last test - always keep at least 1
        if (count($this->selected_tests) <= 1) {
            session()->flash('error', 'At least one test request is required.');
            return;
        }

        unset($this->selected_tests[$testId]);
        unset($this->test_details[$testId]);
    }

    public function addEditTest()
    {
        $testId = $this->next_edit_test_id;
        $this->edit_selected_tests[$testId] = [
            'template_id' => '',
            'report_days' => '',
            'report_date' => '',
            'amount' => ''
        ];
        $this->next_edit_test_id++;
    }

    public function removeEditTest($testId)
    {
        // Prevent removing the last test - always keep at least 1
        if (count($this->edit_selected_tests) <= 1) {
            session()->flash('error', 'At least one test request is required.');
            return;
        }

        unset($this->edit_selected_tests[$testId]);
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

                    // Calculate report date based on report days
                    if ($template->report_days && is_numeric($template->report_days)) {
                        $this->selected_tests[$testId]['report_date'] = now()->addDays($template->report_days)->format('Y-m-d');
                    }
                }
            }
        }
    }

    public function updatedEditSelectedTests($value, $key)
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
                    $this->edit_selected_tests[$testId]['report_days'] = $template->report_days ?? '';
                    $this->edit_selected_tests[$testId]['amount'] = $template->standard_charge ?? '';

                    // Calculate report date based on report days
                    if ($template->report_days && is_numeric($template->report_days)) {
                        $this->edit_selected_tests[$testId]['report_date'] = now()->addDays($template->report_days)->format('Y-m-d');
                    }
                }
            }
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isSubmitting = false; // Reset the submitting state
        $this->showCreateModal = true;

        // Automatically add one test row
        $this->addTest();
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
                if (!empty($item['report_date'])) {
                    $reportDate = \Carbon\Carbon::parse($item['report_date']);
                    if (!$earliestReportDate || $reportDate->lt($earliestReportDate)) {
                        $earliestReportDate = $reportDate;
                    }
                }
            }

            // Create a single pathology test record
            $pathologyTest = PathologyTest::create([
                'bill_no' => $billNo,
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'template_id' => null, // No specific template for the main record
                'case_id' => $this->case_id,
                'note' => $this->note,
                'expected_date' => $earliestReportDate ? $earliestReportDate->format('Y-m-d') : $this->expected_date,
                'test_results' => [],
                'status' => 0, // Pending
                'total' => $totalAmount,
                'discount' => $totalDiscountAmount,
                'amount_paid' => 0,
                'balance' => $grandTotal,
                'performed_by' => auth()->id(), // Set performed_by to current user
            ]);

            // Create pathology test items for each test
            $createdItems = [];
            foreach ($validTests as $item) {
                $template = $item['template'];

                $pathologyTestItem = \App\Models\PathologyTestItem::create([
                    'pathology_id' => $pathologyTest->id,
                    'report_date' => $item['report_date'] ?? $this->expected_date,
                    'test_name' => $template->id, // Reference to the template
                ]);

                $createdItems[] = $pathologyTestItem;
            }

            Log::info('Pathology test request created successfully', [
                'pathology_test_id' => $pathologyTest->id,
                'items_count' => count($createdItems),
                'total_amount' => $totalAmount,
                'discount_percent' => $discountPercent,
                'total_discount' => $totalDiscountAmount,
                'grand_total' => $grandTotal
            ]);

            session()->flash('message', 'Pathology test request created successfully with ' . count($createdItems) . ' test(s). Total: GHS ' . number_format($grandTotal, 2) . ' (after ' . $discountPercent . '% discount)');
            $this->showCreateModal = false;
            $this->resetForm();
            $this->refreshTests();

        } catch (\Exception $e) {
            Log::error('Failed to create pathology test request: ' . $e->getMessage());
            session()->flash('error', 'Failed to create pathology test request: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    private function generateBillNumber()
    {
        $lastTest = PathologyTest::latest()->first();
        $lastNumber = $lastTest ? intval(substr($lastTest->bill_no, 3)) : 0;
        $newNumber = $lastNumber + 1;

        return 'PT' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        try {
            $this->editingTest = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'patient', 'doctor'])->findOrFail($id);

            // Load the test data
            $this->patient_id = $this->editingTest->patient_id;
            $this->doctor_id = $this->editingTest->doctor_id;
            $this->note = $this->editingTest->note;
            $this->expected_date = $this->editingTest->expected_date;
            $this->test_results = $this->editingTest->test_results ?? [];
            $this->discount_percent = $this->editingTest->discount;
            $this->performed_by = $this->editingTest->performed_by;

            // Load available cases for the selected patient (don't reset case_id)
            $this->loadPatientCases(false);

            // Set case_id after loading available cases
            $this->case_id = $this->editingTest->case_id;

            // Load existing test items into edit_selected_tests
            $this->edit_selected_tests = [];
            $this->next_edit_test_id = 1;

            if ($this->editingTest->pathologyTestItems && $this->editingTest->pathologyTestItems->count() > 0) {
                foreach ($this->editingTest->pathologyTestItems as $testItem) {
                    $template = $testItem->pathologytesttemplate;
                if ($template) {
                        $this->edit_selected_tests[$this->next_edit_test_id] = [
                            'template_id' => $template->id,
                            'report_days' => $template->report_days ?? '',
                            'report_date' => $testItem->report_date ?? '',
                            'amount' => $template->standard_charge ?? '',
                            'item_id' => $testItem->id // Keep track of the original item ID
                        ];
                        $this->next_edit_test_id++;
                    }
                }
            } else {
                // If no test items, add at least one empty test
                $this->addEditTest();
            }

            $this->showEditModal = true;

            Log::info('Pathology test loaded for editing', [
                'test_id' => $id,
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'case_id' => $this->case_id,
                'test_items_count' => count($this->edit_selected_tests)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to load pathology test for editing: ' . $e->getMessage());
            session()->flash('error', 'Failed to load pathology test for editing: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            // Validate the form data
            $this->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'case_id' => 'required|exists:patient_cases,id',
                'note' => 'nullable|string',
            ]);

            // Validate that at least one test is selected
            if (empty($this->edit_selected_tests)) {
                session()->flash('error', 'Please add at least one test to the request.');
                return;
            }

            // Validate that all selected tests have template_id
            $validTests = 0;
            foreach ($this->edit_selected_tests as $testData) {
                if (!empty($testData['template_id'])) {
                    $validTests++;
                }
            }

            if ($validTests === 0) {
                session()->flash('error', 'Please select at least one test template.');
                return;
            }

            $this->isSubmitting = true;

            // Calculate total amount from selected tests
            $totalAmount = 0;
            foreach ($this->edit_selected_tests as $testData) {
                if (!empty($testData['template_id']) && !empty($testData['amount'])) {
                    $totalAmount += (float)$testData['amount'];
                }
            }

            // Calculate discount
            $discountPercent = (float)($this->discount_percent ?? 0);
            $totalDiscountAmount = round(($totalAmount * $discountPercent) / 100, 2);
            $grandTotal = round($totalAmount - $totalDiscountAmount, 2);

            // Update the pathology test
            $this->editingTest->update([
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'case_id' => $this->case_id,
                'note' => $this->note,
                'discount' => $totalDiscountAmount,
                'total' => $totalAmount,
                'balance' => $grandTotal,
                'performed_by' => auth()->id(),
            ]);

            // Update or create pathology test items
            $existingItemIds = [];
            foreach ($this->edit_selected_tests as $testData) {
                if (!empty($testData['template_id'])) {
                    if (isset($testData['item_id'])) {
                        // Update existing item
                        \App\Models\PathologyTestItem::where('id', $testData['item_id'])->update([
                            'test_name' => $testData['template_id'],
                            'report_date' => $testData['report_date'] ?? $this->expected_date,
                        ]);
                        $existingItemIds[] = $testData['item_id'];
                    } else {
                        // Create new item
                        \App\Models\PathologyTestItem::create([
                            'pathology_id' => $this->editingTest->id,
                            'test_name' => $testData['template_id'],
                            'report_date' => $testData['report_date'] ?? $this->expected_date,
                        ]);
                    }
                }
            }

            // Delete items that are no longer selected
            \App\Models\PathologyTestItem::where('pathology_id', $this->editingTest->id)
                ->whereNotIn('id', $existingItemIds)
                ->delete();

            Log::info('Pathology test updated successfully', [
                'test_id' => $this->editingTest->id,
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'case_id' => $this->case_id,
                'total_amount' => $totalAmount,
                'discount' => $totalDiscountAmount,
                'grand_total' => $grandTotal
            ]);

            session()->flash('message', 'Pathology test updated successfully with ' . count($this->edit_selected_tests) . ' test(s). Total: GHS ' . number_format($grandTotal, 2));
            $this->closeEditModal();
            $this->refreshTests();

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for pathology test update: ' . json_encode($e->errors()));
            session()->flash('error', 'Please check the form and try again.');
        } catch (\Exception $e) {
            Log::error('Failed to update pathology test: ' . $e->getMessage());
            session()->flash('error', 'Failed to update pathology test: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $test = PathologyTest::findOrFail($id);
            $test->delete();

            session()->flash('message', 'Pathology test request deleted successfully.');
            $this->refreshTests();
        } catch (\Exception $e) {
            Log::error('Failed to delete pathology test request: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete pathology test request.');
        }
    }

                public function viewResults($id)
    {
        try {
            // Clear any previous modal messages
            $this->modalMessage = '';
            $this->modalMessageType = '';

            $this->selectedTest = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser', 'performed_by_user'])
                ->findOrFail($id);

            // Check if bill is paid (only for individual patients, company patients can view regardless)
            $balance = (float)($this->selectedTest->balance ?? 0);
            $isCompanyPatient = $this->selectedTest->patient && $this->selectedTest->patient->company_id;

            if ($balance > 0 && !$isCompanyPatient) {
                session()->flash('error', 'Cannot view test results. Bill is not fully paid. Outstanding balance: GHS ' . number_format($balance, 2));
                return;
            }

            // Load test results
            $this->test_results = $this->selectedTest->test_results ?? [];
            $this->diagnosis = $this->selectedTest->diagnosis ?? '';

            // Load performed_by if exists, otherwise set to current user's name
            $this->performed_by = $this->selectedTest->performed_by_user->full_name ?? $this->selectedTest->performed_by_user->name ?? auth()->user()->full_name ?? auth()->user()->name ?? '';

            // Ensure each test item has a results section
            if ($this->selectedTest->pathologyTestItems && $this->selectedTest->pathologyTestItems->count() > 0) {
                foreach ($this->selectedTest->pathologyTestItems as $testItem) {
                    if (!isset($this->test_results[$testItem->id])) {
                        $this->test_results[$testItem->id] = [];
                    }
                }
            }

            $this->showResultsModal = true;
        } catch (\Exception $e) {
            Log::error('Failed to load pathology test results: ' . $e->getMessage());
            session()->flash('error', 'Failed to load pathology test results.');
        }
    }

    public function updateResults()
    {
        try {
            $this->isSubmitting = true;

            // Manual validation to avoid global error display
            $errors = [];

            if (empty($this->selectedTest->lab_number)) {
                $errors[] = 'Lab Number is required.';
            }

            if (empty($this->diagnosis)) {
                $errors[] = 'Diagnosis is required.';
            }

            if (!empty($errors)) {
                $this->modalMessage = 'Please fix the following errors: ' . implode(' ', $errors);
                $this->modalMessageType = 'error';
                $this->isSubmitting = false;
                return;
            }

            // Check if bill is paid before allowing updates (only for individual patients, company patients can update regardless)
            $balance = (float)($this->selectedTest->balance ?? 0);
            $isCompanyPatient = $this->selectedTest->patient && $this->selectedTest->patient->company_id;

            if ($balance > 0 && !$isCompanyPatient) {
                $this->modalMessage = 'Cannot update test results. Bill is not fully paid. Outstanding balance: GHS ' . number_format($balance, 2);
                $this->modalMessageType = 'error';
                $this->isSubmitting = false;
                return;
            }

            // Clean up empty test result arrays
            $cleanedResults = [];
            foreach ($this->test_results as $testItemId => $results) {
                if (!empty($results) && is_array($results)) {
                    $cleanedResults[$testItemId] = array_filter($results, function($value) {
                        return $value !== null && $value !== '';
                    });
                }
            }

            $this->selectedTest->test_results = $cleanedResults;
            $this->selectedTest->diagnosis = $this->diagnosis; // Save diagnosis
            $this->selectedTest->performed_by = auth()->id(); // Save current user as performer
            $this->selectedTest->status = 1; // Completed
            $this->selectedTest->save();

            $this->modalMessage = 'Test results updated successfully.';
            $this->modalMessageType = 'success';
            $this->showResultsModal = false;
            $this->refreshTests();
        } catch (\Exception $e) {
            Log::error('Failed to update test results: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->modalMessage = 'Failed to update test results: ' . $e->getMessage();
            $this->modalMessageType = 'error';
        } finally {
            $this->isSubmitting = false;
        }
    }



    public function updated($propertyName)
    {
        // This method is called whenever any property is updated
        if (str_starts_with($propertyName, 'test_results')) {
            // Livewire will automatically re-render when properties change
            // No need to manually trigger refresh
        }
    }

    // Method to clear species and stage when results changes
    public function clearSpeciesAndStage($testItemId)
    {
        if (isset($this->test_results[$testItemId])) {
            $this->test_results[$testItemId]['species'] = '';
            $this->test_results[$testItemId]['stage'] = '';
        }
        // Livewire will automatically re-render due to property changes
    }

    // Method to clear stage when species changes
    public function clearStage($testItemId)
    {
        if (isset($this->test_results[$testItemId])) {
            $this->test_results[$testItemId]['stage'] = '';
        }
        // Livewire will automatically re-render due to property changes
    }

    // Method to get species options based on selected result
    public function getSpeciesOptions($testItemId, $selectedResult)
    {
        if (empty($selectedResult)) {
            return ['N/A'];
        }

        // Get the test item and its form configuration
        $testItem = $this->selectedTest->pathologyTestItems->where('id', $testItemId)->first();
        if (!$testItem) {
            return ['N/A'];
        }

        $formConfig = $testItem->pathologytesttemplate->form_configuration ?? [];
        $speciesConfig = $formConfig['species_config'] ?? [];
        $speciesDependencies = $speciesConfig['species_dependencies'] ?? [];

        // If the selected result has specific species dependencies, return them
        if (isset($speciesDependencies[$selectedResult])) {
            return array_merge(['N/A'], $speciesDependencies[$selectedResult]);
        }

        // Default species options
        return array_merge(['N/A'], $speciesDependencies);
    }

    // Method to get stage options based on selected species
    public function getStageOptions($testItemId, $selectedSpecies)
    {
        if (empty($selectedSpecies) || $selectedSpecies === 'N/A') {
            return ['N/A'];
        }

        // Get the test item and its form configuration
        $testItem = $this->selectedTest->pathologyTestItems->where('id', $testItemId)->first();
        if (!$testItem) {
            return ['N/A'];
        }

        $formConfig = $testItem->pathologytesttemplate->form_configuration ?? [];
        $speciesConfig = $formConfig['species_config'] ?? [];
        $stageDependencies = $speciesConfig['stage_dependencies'] ?? [];

        // If the selected species has specific stage dependencies, return them
        if (isset($stageDependencies[$selectedSpecies])) {
            return array_merge(['N/A'], $stageDependencies[$selectedSpecies]);
        }

        // Default stage options
        return array_merge(['N/A'], $stageDependencies);
    }

    // Computed property for species options
    public function getSpeciesOptionsProperty()
    {
        $options = [];
        if ($this->selectedTest && $this->selectedTest->pathologyTestItems) {
            foreach ($this->selectedTest->pathologyTestItems as $testItem) {
                $formConfig = $testItem->pathologytesttemplate->form_configuration ?? [];
                $tableType = $formConfig['table_type'] ?? '';

                if ($tableType === 'species_dependent') {
                    $speciesConfig = $formConfig['species_config'] ?? [];
                    $speciesDependencies = $speciesConfig['species_dependencies'] ?? [];
                    $currentResults = $this->test_results[$testItem->id]['results'] ?? '';

                    // Debug logging
                    Log::info("Species Options Debug for TestItem {$testItem->id}:", [
                        'currentResults' => $currentResults,
                        'speciesDependencies' => $speciesDependencies,
                        'test_results' => $this->test_results[$testItem->id] ?? 'not set'
                    ]);

                    if (empty($currentResults)) {
                        $options[$testItem->id] = ['N/A'];
                    } else {
                        if (isset($speciesDependencies[$currentResults])) {
                            $dependencies = $speciesDependencies[$currentResults];
                            if (is_string($dependencies)) {
                                // Handle comma-separated string
                                $options[$testItem->id] = array_merge(['N/A'], array_map('trim', explode(',', $dependencies)));
                            } else {
                                $options[$testItem->id] = array_merge(['N/A'], $dependencies);
                            }
                        } else {
                            // If no specific dependency, use all available species
                            $allSpecies = [];
                            foreach ($speciesDependencies as $deps) {
                                if (is_string($deps)) {
                                    $allSpecies = array_merge($allSpecies, array_map('trim', explode(',', $deps)));
                                } else {
                                    $allSpecies = array_merge($allSpecies, $deps);
                                }
                            }
                            $options[$testItem->id] = array_merge(['N/A'], array_unique($allSpecies));
                        }
                    }
                }
            }
        }

        // Debug logging
        Log::info("Final Species Options:", $options);

        return $options;
    }

    // Computed property for stage options
    public function getStageOptionsProperty()
    {
        $options = [];
        if ($this->selectedTest && $this->selectedTest->pathologyTestItems) {
            foreach ($this->selectedTest->pathologyTestItems as $testItem) {
                $formConfig = $testItem->pathologytesttemplate->form_configuration ?? [];
                $tableType = $formConfig['table_type'] ?? '';

                if ($tableType === 'species_dependent') {
                    $speciesConfig = $formConfig['species_config'] ?? [];
                    $stageDependencies = $speciesConfig['stage_dependencies'] ?? [];
                    $currentSpecies = $this->test_results[$testItem->id]['species'] ?? '';

                    if (empty($currentSpecies) || $currentSpecies === 'N/A') {
                        $options[$testItem->id] = ['N/A'];
                    } else {
                        if (isset($stageDependencies[$currentSpecies])) {
                            $dependencies = $stageDependencies[$currentSpecies];
                            if (is_string($dependencies)) {
                                // Handle comma-separated string
                                $options[$testItem->id] = array_merge(['N/A'], array_map('trim', explode(',', $dependencies)));
                            } else {
                                $options[$testItem->id] = array_merge(['N/A'], $dependencies);
                            }
                        } else {
                            // If no specific dependency, use all available stages
                            $allStages = [];
                            foreach ($stageDependencies as $deps) {
                                if (is_string($deps)) {
                                    $allStages = array_merge($allStages, array_map('trim', explode(',', $deps)));
                                } else {
                                    $allStages = array_merge($allStages, $deps);
                                }
                            }
                            $options[$testItem->id] = array_merge(['N/A'], array_unique($allStages));
                        }
                    }
                }
            }
        }
        return $options;
    }

    // Computed property for results options
    public function getResultsOptionsProperty()
    {
        $options = [];
        if ($this->selectedTest && $this->selectedTest->pathologyTestItems) {
            foreach ($this->selectedTest->pathologyTestItems as $testItem) {
                $formConfig = $testItem->pathologytesttemplate->form_configuration ?? [];
                $tableType = $formConfig['table_type'] ?? '';

                if ($tableType === 'species_dependent') {
                    $speciesConfig = $formConfig['species_config'] ?? [];
                    $results = $speciesConfig['results'] ?? '';

                    if (is_array($results)) {
                        $options[$testItem->id] = $results;
                    } elseif (is_string($results) && !empty($results)) {
                        // Handle comma-separated string
                        $options[$testItem->id] = array_map('trim', explode(',', $results));
                    } else {
                        $options[$testItem->id] = [];
                    }
                }
            }
        }
        return $options;
    }

    public function getTestResultProperty($testItemId, $fieldName)
    {
        return $this->test_results[$testItemId][$fieldName] ?? null;
    }

    public function calculateFlag($result, $min, $max)
    {
        if ($result === null || $result === '' || $min === null || $max === null || !is_numeric($result)) {
            return ['flag' => '', 'class' => ''];
        }

        $resultValue = floatval($result);
        $minValue = floatval($min);
        $maxValue = floatval($max);

        if ($resultValue < $minValue) {
            return ['flag' => 'LOW', 'class' => 'flag-low'];
        } elseif ($resultValue > $maxValue) {
            return ['flag' => 'HIGH', 'class' => 'flag-high'];
        } else {
            return ['flag' => 'NORMAL', 'class' => 'flag-normal'];
        }
    }

    private function resetForm()
    {
        $this->patient_id = '';
        $this->doctor_id = '';
        $this->template_id = '';
        $this->case_id = '';
        $this->note = '';
        $this->expected_date = '';
        $this->test_results = [];
        $this->form_configuration = [];
        $this->available_cases = []; // Reset available cases
        $this->discount_percent = 0; // Reset discount percent
        $this->diagnosis = ''; // Reset diagnosis
        $this->performed_by = ''; // Reset performed_by

        // Reset multiple tests
        $this->selected_tests = [];
        $this->test_details = [];
        $this->next_test_id = 1;

        // Reset edit modal properties
        $this->edit_selected_tests = [];
        $this->next_edit_test_id = 1;

        $this->editingTest = null;
        $this->selectedTest = null;
        $this->isSubmitting = false; // Reset the submitting state
    }

    public function acceptTest($testId)
    {
        try {
            $pathologyTest = PathologyTest::findOrFail($testId);

            // Check if user is authorized (Lab Technician or Admin)
            if (!auth()->user()->hasRole(['Lab Technician', 'Admin'])) {
                session()->flash('error', 'You are not authorized to accept test requests.');
                return;
            }

            // Check if test is in pending status
            if ($pathologyTest->status !== PathologyTest::STATUS_PENDING) {
                session()->flash('error', 'Only pending tests can be accepted.');
                return;
            }

            // Accept the test
            $pathologyTest->acceptByLabTechnician();

            // If this was from the incoming modal, close it
            if ($this->showNewRequestModal && $this->incomingTest && $this->incomingTest->id == $testId) {
                $this->showNewRequestModal = false;
                $this->incomingTest = null;
            }

            session()->flash('message', 'Pathology test request accepted successfully. Status changed to In Progress.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error accepting test request: ' . $e->getMessage());
        }
    }

    /**
     * Listener called when a new pathology test is created elsewhere (OPD/IPD).
     * Shows a modal to lab technicians so they can accept the incoming request.
     * Payload expected: ['id' => <testId>, 'bill_no' => 'PT000001']
     */
    public function onTestRequestCreated($payload)
    {
        try {
            // Only show modal to lab technicians and admins
            if (!auth()->user() || !auth()->user()->hasRole(['Lab Technician', 'Admin'])) {
                return;
            }

            $testId = is_array($payload) && isset($payload['id']) ? $payload['id'] : $payload;
            $this->incomingTest = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser'])
                ->find($testId);

            if ($this->incomingTest) {
                $this->showNewRequestModal = true;
            }
        } catch (\Exception $e) {
            \Log::error('Error handling testRequestCreated event: ' . $e->getMessage());
        }
    }

    private function refreshTests()
    {
        $this->emit('$refresh');
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function loadPatientCases($resetCaseId = true)
    {
        if ($this->patient_id) {
            $this->available_cases = PatientCase::where('patient_id', $this->patient_id)
                ->select('id', 'case_id')
                ->get()
                ->pluck('case_id', 'id')
                ->toArray();
        } else {
            $this->available_cases = [];
        }

        // Only reset case_id if requested (for create modal, not edit modal)
        if ($resetCaseId) {
            $this->case_id = '';
        }
    }

            public function saveLabNumber()
    {
        try {
            // Only save if both fields are filled
            if (!empty($this->selectedTest->lab_number) && !empty($this->diagnosis)) {
                if ($this->selectedTest) {
                    // Update the diagnosis field on the selected test
                    $this->selectedTest->diagnosis = $this->diagnosis;
                    $this->selectedTest->save();
                    $this->modalMessage = 'Laboratory results saved successfully.';
                    $this->modalMessageType = 'success';
                }
            }
        } catch (\Exception $e) {
            $this->modalMessage = 'Error saving laboratory results: ' . $e->getMessage();
            $this->modalMessageType = 'error';
        }
    }

    public function render()
    {
        $query = PathologyTest::with(['pathologyTestItems.pathologytesttemplate', 'patient.patientUser', 'doctor.doctorUser'])
            ->orderBy('created_at', 'desc');

        // Filter by IPD patient if ipdId is provided
        if ($this->ipdId) {
            $query->whereHas('patient.ipdPatientDepartments', function ($q) {
                $q->where('id', $this->ipdId);
            });
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('bill_no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('patient.patientUser', function ($subQ) {
                        $subQ->where('full_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('pathologyTestItems.pathologytesttemplate', function ($subQ) {
                        $subQ->where('test_name', 'like', '%' . $this->search . '%');
            })
                    ->orWhereHas('doctor.doctorUser', function ($subQ) {
                        $subQ->where('full_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // For IPD patients, show all results without pagination
        if ($this->ipdId) {
            $tests = $query->get();
            // Debug logging
            Log::info('PathologyTestTable render - IPD ID: ' . $this->ipdId . ', Tests count: ' . $tests->count());
        } else {
            $tests = $query->paginate(10);
        }

        $patients = Patient::with('patientUser')->get()->pluck('patientUser.full_name', 'id');
        $doctors = Doctor::with('doctorUser')->get()->pluck('doctorUser.full_name', 'id');

        return view('livewire.pathology-test-table', compact('tests', 'patients', 'doctors'));
    }
}
