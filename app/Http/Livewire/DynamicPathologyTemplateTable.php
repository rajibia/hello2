<?php

namespace App\Http\Livewire;

use App\Models\PathologyTestTemplate;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DynamicPathologyTemplateTable extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showEditModal = false;
    public $showPreviewModal = false;
    public $editingTemplate = null;
    public $previewTemplate = null;
    public $isUpdating = false;

    // Form fields
    public $test_name = '';
    public $short_name = '';
    public $test_type = '';
    public $category_id = '';
    public $charge_category_id = '';
    public $standard_charge = '';
    public $report_days = ''; // Add Report Days field
    public $icon_class = '';
    public $icon_color = '#007bff';
    public $form_configuration = [];

    // Enhanced template configuration
    public $table_type = 'standard'; // 'standard', 'simple', 'species_dependent'
    public $layout_type = 'single_row'; // 'single_row', 'multi_column'
    public $columns_per_row = 1; // 1, 2, 3, 4
    public $specimen_name = ''; // For species dependent templates

    // Dynamic form builder
    public $form_fields = [];
    public $field_counter = 0;
    public $field_groups = [];
    public $current_group = '';

    protected $rules = [
        'test_name' => 'required|string|max:160',
        'short_name' => 'required|string',
        'test_type' => 'required|string',
        'category_id' => 'required|exists:pathology_categories,id',
        'charge_category_id' => 'required|exists:charge_categories,id',
        'standard_charge' => 'required|numeric|min:0',
        'report_days' => 'nullable|numeric|min:0|max:365', // Add validation for report days
    ];

    protected $messages = [
        'test_name.required' => 'Test name is required.',
        'short_name.required' => 'Short name is required.',
        'test_type.required' => 'Test type is required.',
        'category_id.required' => 'Category is required.',
        'charge_category_id.required' => 'Charge category is required.',
        'standard_charge.required' => 'Standard charge is required.',
        'standard_charge.numeric' => 'Standard charge must be a number.',
        'report_days.numeric' => 'Report days must be a number.',
        'report_days.min' => 'Report days must be at least 0.',
        'report_days.max' => 'Report days cannot exceed 365.',
    ];

    public function render()
    {
        $templates = PathologyTestTemplate::with('pathologycategory')
            ->where('is_dynamic_form', true)
            ->when($this->search, function($query) {
                $query->where('test_name', 'like', '%' . $this->search . '%')
                      ->orWhere('short_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = PathologyCategory::pluck('name', 'id');
        $chargeCategories = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');

        return view('livewire.dynamic-pathology-template-table', compact('templates', 'categories', 'chargeCategories'));
    }

    public function paginationView()
    {
        return 'vendor.pagination.livewire-bootstrap-4';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function goToPage($page)
    {
        $this->setPage($page);
        Log::info("Navigated to page: " . $page);
    }

    public function previousPage()
    {
        $currentPage = $this->getPage();
        $this->setPage($currentPage - 1);
        Log::info("Previous page: " . ($currentPage - 1));
    }

    public function nextPage()
    {
        $currentPage = $this->getPage();
        $this->setPage($currentPage + 1);
        Log::info("Next page: " . ($currentPage + 1));
    }

    public function refreshTemplates()
    {
        // Force refresh the component to get latest data
        $this->emit('$refresh');
    }

    public function updatedChargeCategoryId($value)
    {
        if (!empty($value)) {
            // Get the average standard charge from this category to auto-fill
            $charges = Charge::where('charge_category_id', $value)->get();
            if ($charges->count() > 0) {
                $averageCharge = $charges->avg('standard_charge');
                $this->standard_charge = number_format($averageCharge, 2);
            } else {
                $this->standard_charge = '';
            }
        } else {
            $this->standard_charge = '';
        }
    }

        public function preview($id)
    {
        // Force fresh data from database and clear any cached relationships
        $this->previewTemplate = PathologyTestTemplate::with('pathologycategory')
            ->where('id', $id)
            ->first()
            ->fresh();

        // Force reload the form_configuration to ensure we get the latest data
        $this->previewTemplate->refresh();

        Log::info('Preview template loaded', [
            'template_id' => $this->previewTemplate->id,
            'test_name' => $this->previewTemplate->test_name,
            'form_configuration' => $this->previewTemplate->form_configuration,
            'form_configuration_count' => is_array($this->previewTemplate->form_configuration) ? count($this->previewTemplate->form_configuration) : 0,
            'raw_form_configuration' => $this->previewTemplate->getRawOriginal('form_configuration')
        ]);

        $this->showPreviewModal = true;
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
        $this->previewTemplate = null;
    }

        public function refreshPreview()
    {
        if ($this->previewTemplate) {
            // Reload the preview template with fresh data
            $this->previewTemplate = PathologyTestTemplate::with('pathologycategory')
                ->where('id', $this->previewTemplate->id)
                ->first()
                ->fresh();

            // Force reload the form_configuration
            $this->previewTemplate->refresh();

            Log::info('Preview refreshed', [
                'template_id' => $this->previewTemplate->id,
                'test_name' => $this->previewTemplate->test_name,
                'form_configuration' => $this->previewTemplate->form_configuration,
                'form_configuration_count' => is_array($this->previewTemplate->form_configuration) ? count($this->previewTemplate->form_configuration) : 0,
                'raw_form_configuration' => $this->previewTemplate->getRawOriginal('form_configuration')
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $template = PathologyTestTemplate::findOrFail($id);

            if (!$template) {
                session()->flash('error', 'Template not found.');
                return;
            }

            $this->editingTemplate = $template;
            $this->test_name = $template->test_name;
            $this->short_name = $template->short_name;
            $this->test_type = $template->test_type;
            $this->category_id = $template->category_id;
            $this->charge_category_id = $template->charge_category_id;
            $this->standard_charge = $template->standard_charge;
            $this->report_days = $template->report_days; // Add report days
            $this->icon_class = $template->icon_class;
            $this->icon_color = $template->icon_color;
            $this->form_configuration = $template->form_configuration ?? [];

            // Load enhanced template configuration
            if (is_array($template->form_configuration)) {
                $this->table_type = $template->form_configuration['table_type'] ?? 'standard';
                $this->layout_type = $template->form_configuration['layout_type'] ?? 'single_row';
                $this->columns_per_row = $template->form_configuration['columns_per_row'] ?? 1;
                $this->specimen_name = $template->form_configuration['specimen_name'] ?? '';

                // Load form fields from the new structure
                $formFields = $template->form_configuration['fields'] ?? $template->form_configuration;
            } else {
                $formFields = $template->form_configuration ?? [];
            }

        // Ensure form_fields are properly formatted
        Log::info('Loading template for edit', [
            'template_id' => $template->id,
            'original_form_configuration' => $formFields,
            'form_fields_count' => count($formFields)
        ]);

        $this->form_fields = array_map(function($field) {
            return [
                'id' => $field['id'] ?? 0,
                'name' => $field['name'] ?? '',
                'label' => $field['label'] ?? '',
                'type' => is_string($field['type'] ?? '') ? $field['type'] : 'text',
                'group' => $field['group'] ?? 'General',
                'required' => $field['required'] ?? false,
                'placeholder' => $field['placeholder'] ?? '',
                'options' => is_array($field['options'] ?? []) ? $field['options'] : [],
                'reference_min' => isset($field['reference_min']) ? $field['reference_min'] : null,
                'reference_max' => isset($field['reference_max']) ? $field['reference_max'] : null,
                'unit' => $field['unit'] ?? '',
                'validation' => $field['validation'] ?? '',
                'order' => $field['order'] ?? 0
            ];
        }, $formFields);

        Log::info('Form fields loaded for edit', [
            'processed_form_fields' => $this->form_fields,
            'processed_count' => count($this->form_fields)
        ]);

        // Extract unique groups from form fields
        $this->field_groups = [];
        foreach ($this->form_fields as $field) {
            $group = $field['group'] ?? 'General';
            if (!in_array($group, $this->field_groups)) {
                $this->field_groups[] = $group;
            }
        }

        $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Failed to load template for editing', ['error' => $e->getMessage(), 'template_id' => $id]);
            session()->flash('error', 'Failed to load template for editing: ' . $e->getMessage());
        }
    }

    private function generateFieldNameFromLabel(string $label, array $existingNames): string
    {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $label), '_'));
        if ($base === '') {
            $base = 'field';
        }
        $name = $base;
        $suffix = 1;
        while (in_array($name, $existingNames, true)) {
            $suffix++;
            $name = $base . '_' . $suffix;
        }
        return $name;
    }

    public function manualUpdate()
    {
        Log::info('Manual update called - user clicked the button');
        $this->isUpdating = true;
        $this->update();
        $this->isUpdating = false;
    }

    public function update()
    {
        try {
            // Check if editingTemplate exists
            if (!$this->editingTemplate) {
                throw new \Exception('No template selected for editing. Please try again.');
            }

            // Store the template ID for later use
            $templateId = $this->editingTemplate->id;

            // Verify the template still exists in the database
            $templateExists = PathologyTestTemplate::where('id', $templateId)->exists();
            if (!$templateExists) {
                throw new \Exception('Template not found in database. It may have been deleted.');
            }

            Log::info('Update method called', [
                'template_id' => $templateId,
                'test_name' => $this->test_name,
                'form_fields_count' => count($this->form_fields)
            ]);

            // Basic validation first
            $this->validate();

            // Ensure at least one field is added
            if (empty($this->form_fields)) {
                throw new \Exception('At least one form field is required.');
            }

            // Validate form fields manually
            foreach ($this->form_fields as $index => $field) {
                if (empty($field['label'])) {
                    throw new \Exception('Field #' . ($index + 1) . ' label is required.');
                }
                if (empty($field['type'])) {
                    throw new \Exception('Field #' . ($index + 1) . ' type is required.');
                }
            }

            // Normalize form fields: generate names from labels if missing
            $usedNames = [];
            foreach ($this->form_fields as $idx => $field) {
                $label = isset($field['label']) ? (string) $field['label'] : '';
                $name = isset($field['name']) ? (string) $field['name'] : '';

                if ($name === '' && $label !== '') {
                    $generated = $this->generateFieldNameFromLabel($label, $usedNames);
                    $this->form_fields[$idx]['name'] = $generated;
                    $usedNames[] = $generated;
                } else if ($name !== '') {
                    $usedNames[] = $name;
                }

                // Ensure type is a scalar string
                if (isset($this->form_fields[$idx]['type']) && is_array($this->form_fields[$idx]['type'])) {
                    $this->form_fields[$idx]['type'] = 'text';
                }
            }

            // Validate reference ranges for number fields
            $this->validateReferenceRanges();

            Log::info('About to update template with data', [
                'template_id' => $templateId,
                'test_name' => $this->test_name,
                'form_fields' => $this->form_fields,
                'form_fields_count' => count($this->form_fields)
            ]);

            $updateData = [
                'test_name' => $this->test_name,
                'short_name' => $this->short_name,
                'test_type' => $this->test_type,
                'category_id' => $this->category_id,
                'charge_category_id' => $this->charge_category_id,
                'standard_charge' => $this->standard_charge,
                'report_days' => $this->report_days, // Add report days
                'icon_class' => $this->icon_class,
                'icon_color' => $this->icon_color,
                'form_configuration' => $this->form_fields,
            ];

            $result = $this->editingTemplate->update($updateData);

            Log::info('Update result', ['result' => $result, 'updated_template' => $this->editingTemplate->fresh()]);

            Log::info('Template updated successfully', ['template_id' => $templateId]);

                                    // Force refresh the template data
            $this->editingTemplate->refresh();

            // Clear query cache to ensure fresh data
            DB::flushQueryLog();

            session()->flash('message', 'Dynamic template updated successfully.');
            $this->showEditModal = false;
            $this->resetForm();

            // Force refresh the component to show updated data
            $this->refreshTemplates();

            // Clear any cached data for this template
            if ($this->previewTemplate && $this->previewTemplate->id == $templateId) {
                $this->previewTemplate = null;
            }

            // Dispatch event to force preview refresh if it's open
            $this->dispatchBrowserEvent('template-updated', ['template_id' => $templateId]);
        } catch (\Exception $e) {
            Log::error('Failed to update template', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to update dynamic template: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $template = PathologyTestTemplate::findOrFail($id);
        $template->delete();
        session()->flash('message', 'Dynamic template deleted successfully.');
    }

    // Dynamic form builder methods
    public function addField()
    {
        $fieldId = $this->field_counter++;
        $this->form_fields[] = [
            'id' => $fieldId,
            'name' => 'field_' . $fieldId,
            'label' => 'Field ' . ($fieldId + 1),
            'type' => 'text',
            'group' => 'General',
            'required' => 0,
            'placeholder' => '',
            'unit' => '',
            'validation' => '',
            'reference_min' => '',
            'reference_max' => '',
            'options' => [],
            'column_position' => 1,
            'row_group' => 1,
            'dependencies' => [
                'parent_field' => '',
                'options_map' => []
            ],
            'species_config' => [
                'results' => '',
                'species' => '',
                'stages' => '',
                'counts' => '',
                'units' => ''
            ]
        ];
    }

    public function updatedFormFields($value, $key)
    {
        // Ensure field type is always a string
        if (str_contains($key, '.type') && is_array($value)) {
            $this->form_fields = array_map(function($field) {
                if (isset($field['type']) && is_array($field['type'])) {
                    $field['type'] = 'text'; // Default to text if type is somehow an array
                }
                return $field;
            }, $this->form_fields);
        }

        // Force re-render when group changes
        if (str_contains($key, '.group')) {
            $this->dispatchBrowserEvent('field-group-updated');
        }

        // Clear reference range when type changes away from number
        if (str_contains($key, '.type')) {
            $index = intval(explode('.', $key)[0]);
            if (isset($this->form_fields[$index]['type']) && $this->form_fields[$index]['type'] !== 'number') {
                $this->form_fields[$index]['reference_min'] = null;
                $this->form_fields[$index]['reference_max'] = null;
            }
        }
    }

    public function removeField($index)
    {
        unset($this->form_fields[$index]);
        $this->form_fields = array_values($this->form_fields);
    }

    public function addOption($fieldIndex)
    {
        if (!isset($this->form_fields[$fieldIndex]['options'])) {
            $this->form_fields[$fieldIndex]['options'] = [];
        }
        $this->form_fields[$fieldIndex]['options'][] = '';
    }

    public function removeOption($fieldIndex, $optionIndex)
    {
        unset($this->form_fields[$fieldIndex]['options'][$optionIndex]);
        $this->form_fields[$fieldIndex]['options'] = array_values($this->form_fields[$fieldIndex]['options']);
    }

    public function addGroup()
    {
        $group = trim((string) $this->current_group);
        if ($group !== '' && !in_array($group, $this->field_groups, true)) {
            $this->field_groups[] = $group;
            $this->current_group = '';
            // Force a complete re-render
            $this->dispatchBrowserEvent('group-added', ['group' => $group]);
        }
    }

    public function removeGroup($index)
    {
        if (isset($this->field_groups[$index])) {
            unset($this->field_groups[$index]);
            $this->field_groups = array_values($this->field_groups);
        }
    }

    public function editGroup($index, $newName)
    {
        $newName = trim((string) $newName);
        if ($newName !== '' && !in_array($newName, $this->field_groups, true)) {
            $this->field_groups[$index] = $newName;
        }
    }

    public function getFieldTypes()
    {
        // Enhanced field types for lab templates
        return [
            'text' => 'Text Input',
            'number' => 'Number',
            'dropdown' => 'Dropdown/Select',
            'textarea' => 'Text Area',
            'species_dependent' => 'Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)',
            'date' => 'Date',
            'time' => 'Time',
            'datetime' => 'Date & Time',
            'email' => 'Email',
            'url' => 'URL',
            'phone' => 'Phone Number',
        ];
    }

    public function getValidationRules()
    {
        return [
            'required' => 'Required',
            'email' => 'Valid Email',
            'numeric' => 'Numeric Only',
            'alpha' => 'Alphabetic Only',
            'alphanumeric' => 'Alphanumeric',
            'min:3' => 'Minimum 3 characters',
            'max:50' => 'Maximum 50 characters',
            'min:0' => 'Minimum 0',
            'max:100' => 'Maximum 100',
            'regex:/^[0-9]+$/' => 'Numbers Only',
            'regex:/^[a-zA-Z]+$/' => 'Letters Only'
        ];
    }

    public function getTableTypes()
    {
        return [
            'standard' => 'Standard (ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
            'simple' => 'Simple (ANALYTE, RESULTS)',
            'species_dependent' => 'Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)'
        ];
    }

    public function getLayoutTypes()
    {
        return [
            'single_row' => 'Single Field per Row',
            'multi_column' => 'Multiple Fields per Row'
        ];
    }

    public function updatedTableType($value)
    {
        // Reset specimen name when table type changes
        if ($value !== 'species_dependent') {
            $this->specimen_name = '';
        }

        // Update form fields if switching to species dependent
        if ($value === 'species_dependent') {
            $this->updateFieldsForSpeciesDependent();
        }
    }

    public function updatedLayoutType($value)
    {
        // Reset columns per row when switching to single row
        if ($value === 'single_row') {
            $this->columns_per_row = 1;
        }
    }

    private function updateFieldsForSpeciesDependent()
    {
        // Clear existing fields and add species dependent structure
        $this->form_fields = [];
        $this->addSpeciesDependentField();
    }

    public function addSpeciesDependentField()
    {
        $fieldId = $this->field_counter++;
        $this->form_fields[] = [
            'id' => $fieldId,
            'name' => 'species_field_' . $fieldId,
            'label' => 'Species Analysis',
            'type' => 'species_dependent',
            'group' => 'Species Analysis',
            'required' => 1,
            'placeholder' => '',
            'unit' => '',
            'validation' => '',
            'reference_min' => '',
            'reference_max' => '',
            'options' => [],
            'column_position' => 1,
            'row_group' => 1,
            'dependencies' => [
                'parent_field' => '',
                'options_map' => []
            ],
            'species_config' => [
                'results' => 'Positive, Negative, Trace',
                'species' => 'E. coli, Salmonella, Shigella',
                'stages' => 'Early, Mid, Late',
                'counts' => '1-10, 11-50, 51-100',
                'units' => 'cells/mL, CFU/mL, %'
            ]
        ];
    }

    public function addSpeciesOption($fieldIndex, $optionType)
    {
        if (isset($this->form_fields[$fieldIndex])) {
            if (!isset($this->form_fields[$fieldIndex]['species_config'])) {
                $this->form_fields[$fieldIndex]['species_config'] = [];
            }

            // Add a default option if the field is empty
            if (empty($this->form_fields[$fieldIndex]['species_config'][$optionType])) {
                $defaultOptions = [
                    'results' => 'Positive, Negative, Trace',
                    'species' => 'E. coli, Salmonella, Shigella',
                    'stages' => 'Early, Mid, Late',
                    'counts' => '1-10, 11-50, 51-100',
                    'units' => 'cells/mL, CFU/mL, %'
                ];

                $this->form_fields[$fieldIndex]['species_config'][$optionType] = $defaultOptions[$optionType] ?? '';
            }
        }
    }

    private function resetForm()
    {
        $this->test_name = '';
        $this->short_name = '';
        $this->test_type = '';
        $this->category_id = '';
        $this->charge_category_id = '';
        $this->standard_charge = '';
        $this->report_days = ''; // Reset report days
        $this->icon_class = '';
        $this->icon_color = '#007bff';
        $this->form_configuration = [];
        $this->form_fields = [];
        $this->field_groups = [];
        $this->current_group = '';
        $this->field_counter = 0;
        $this->editingTemplate = null;
        $this->isUpdating = false;

        // Reset enhanced template configuration
        $this->table_type = 'standard';
        $this->layout_type = 'single_row';
        $this->columns_per_row = 1;
        $this->specimen_name = '';
    }

    public function testSubmission()
    {
        Log::info('Test submission called', [
            'test_name' => $this->test_name,
            'form_fields_count' => count($this->form_fields),
            'showCreateModal' => false // No longer using showCreateModal
        ]);

        // Test database connection and table structure
        try {
            $tableExists = Schema::hasTable('pathology_test_templates');
            $columns = Schema::getColumnListing('pathology_test_templates');

            Log::info('Database test results', [
                'table_exists' => $tableExists,
                'columns' => $columns,
                'has_form_configuration' => in_array('form_configuration', $columns),
                'has_is_dynamic_form' => in_array('is_dynamic_form', $columns)
            ]);

            // Try to create a minimal test template
            if ($tableExists && in_array('form_configuration', $columns) && in_array('is_dynamic_form', $columns)) {
                $testTemplate = PathologyTestTemplate::create([
                    'test_name' => 'Test Template ' . time(),
                    'short_name' => 'TEST',
                    'test_type' => 'Test',
                    'category_id' => 1,
                    'charge_category_id' => 1,
                    'standard_charge' => 100,
                    'is_dynamic_form' => true,
                    'form_configuration' => [['name' => 'test_field', 'label' => 'Test Field', 'type' => 'text']],
                ]);

                Log::info('Test template created successfully', ['template_id' => $testTemplate->id]);
                $testTemplate->delete(); // Clean up

                session()->flash('message', 'Database test successful! Template creation works. Fields: ' . count($this->form_fields));
            } else {
                session()->flash('error', 'Database table missing required columns: form_configuration, is_dynamic_form');
            }
        } catch (\Exception $e) {
            Log::error('Database test failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Database test failed: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->showPreviewModal = false;
        $this->resetForm();
        $this->previewTemplate = null;
    }
}
