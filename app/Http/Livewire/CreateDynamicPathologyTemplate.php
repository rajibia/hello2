<?php

namespace App\Http\Livewire;

use App\Models\PathologyTestTemplate;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDynamicPathologyTemplate extends Component
{
    use WithFileUploads;

    // Form fields
    public $test_name = '';
    public $short_name = '';
    public $test_type = '';
    public $category_id = '';
    public $charge_category_id = '';
    public $standard_charge = '';
    public $report_days = '';
    public $icon_class = '';
    public $icon_color = '#007bff';
    public $form_configuration = [];

    // Enhanced template configuration
    public $table_type = 'standard';
    public $layout_type = 'single_row';
    public $columns_per_row = 1;
    public $specimen_name = '';

    // Dynamic form builder
    public $form_fields = [];
    public $field_counter = 0;
    public $field_groups = [];
    public $current_group = '';

    // Species configuration
    public $species_config = [
        'results' => 'res 1, res 2',
        'units' => 'cells/mL, CFU/mL, %',
        'species_dependencies' => [
            'res 1' => 'N/A, E. coli, Salmonella',
            'res 2' => 'N/A, different options'
        ],
        'stage_dependencies' => [
            'E. coli' => 'Early, Mid, Late',
            'Salmonella' => 'different options'
        ]
    ];

    // Field-Value Multi-Column configuration
    public $field_value_columns = 4;
    public $field_value_separator = ': ';

    protected $rules = [
        'test_name' => 'required|string|max:160',
        'short_name' => 'required|string',
        'test_type' => 'required|string',
        'category_id' => 'required|exists:pathology_categories,id',
        'charge_category_id' => 'required|exists:charge_categories,id',
        'standard_charge' => 'required|numeric|min:0',
        'report_days' => 'nullable|numeric|min:0|max:365',
    ];

    protected function rules()
    {
        $rules = [
            'test_name' => 'required|string|max:160',
            'short_name' => 'required|string',
            'test_type' => 'required|string',
            'category_id' => 'required|exists:pathology_categories,id',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'standard_charge' => 'required|numeric|min:0',
            'report_days' => 'nullable|numeric|min:0|max:365',
        ];

        return $rules;
    }

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

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $categories = PathologyCategory::pluck('name', 'id');
        $chargeCategories = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');

        return view('livewire.create-dynamic-pathology-template', compact('categories', 'chargeCategories'));
    }

    public function updatedChargeCategoryId($value)
    {
        if ($value) {
            // Find the charge record for this category
            $charge = Charge::where('charge_category_id', $value)->first();
            if ($charge) {
                // Set the charge from the charge record
                $this->standard_charge = $charge->standard_charge ?? 0;
            } else {
                // If no charge record found, clear the field
                $this->standard_charge = '';
            }
        } else {
            // Clear charge if no category selected
            $this->standard_charge = '';
        }
    }

    public function updatedTestType($value)
    {
        if ($value) {
            // Auto-populate specimen name based on test type
            $specimenMap = [
                'blood' => 'Blood',
                'urine' => 'Urine',
                'stool' => 'Stool',
                'sputum' => 'Sputum',
                'cerebrospinal' => 'Cerebrospinal Fluid',
                'biochemistry' => 'Biochemistry Sample',
                'hematology' => 'Hematology Sample',
                'coagulation' => 'Coagulation Sample',
                'hormone' => 'Hormone Sample',
                'tumor' => 'Tumor Marker Sample'
            ];

            $testTypeLower = strtolower($value);
            foreach ($specimenMap as $keyword => $specimen) {
                if (strpos($testTypeLower, $keyword) !== false) {
                    $this->specimen_name = $specimen;
                    break;
                }
            }

            // If no match found, set a default based on common patterns
            if (empty($this->specimen_name)) {
                if (strpos($testTypeLower, 'blood') !== false || strpos($testTypeLower, 'cbc') !== false || strpos($testTypeLower, 'hemoglobin') !== false) {
                    $this->specimen_name = 'Blood';
                } elseif (strpos($testTypeLower, 'urine') !== false || strpos($testTypeLower, 'urinalysis') !== false) {
                    $this->specimen_name = 'Urine';
                } elseif (strpos($testTypeLower, 'stool') !== false || strpos($testTypeLower, 'fecal') !== false) {
                    $this->specimen_name = 'Stool';
                } elseif (strpos($testTypeLower, 'culture') !== false || strpos($testTypeLower, 'bacterial') !== false) {
                    $this->specimen_name = 'Culture';
                } else {
                    $this->specimen_name = 'Blood'; // Default fallback
                }
            }
        }
    }

    public function updatedSpeciesConfigResults($value)
    {
        // Reset species dependencies when results change
        $this->species_config['species_dependencies'] = [];
        $this->species_config['stage_dependencies'] = [];
    }

    public function updatedSpeciesConfigSpeciesDependencies($value, $key)
    {
        // Update stage dependencies when species dependencies change
        if (isset($this->species_config['species_dependencies'][$key])) {
            $speciesOptions = $this->species_config['species_dependencies'][$key];
            $speciesArray = array_map('trim', explode(',', $speciesOptions));

            // Remove stage dependencies for species that no longer exist
            foreach ($this->species_config['stage_dependencies'] as $species => $stages) {
                if (!in_array($species, $speciesArray)) {
                    unset($this->species_config['stage_dependencies'][$species]);
                }
            }
        }
    }

    public function addGroup()
    {
        $groupName = trim($this->current_group);
        if ($groupName && !in_array($groupName, $this->field_groups, true)) {
            $this->field_groups[] = $groupName;
            $this->current_group = '';
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
        $newName = trim($newName);
        if ($newName && isset($this->field_groups[$index])) {
            // Check if the new name already exists (except for the current index)
            $existingGroups = array_values(array_filter($this->field_groups, function($group, $i) use ($index) {
                return $i !== $index;
            }, ARRAY_FILTER_USE_BOTH));

            if (!in_array($newName, $existingGroups)) {
                $this->field_groups[$index] = $newName;
            }
        }
    }

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

    public function removeField($index)
    {
        if (isset($this->form_fields[$index])) {
            unset($this->form_fields[$index]);
            $this->form_fields = array_values($this->form_fields);
        }
    }

    public function addOption($fieldIndex)
    {
        if (isset($this->form_fields[$fieldIndex])) {
            if (!isset($this->form_fields[$fieldIndex]['options'])) {
                $this->form_fields[$fieldIndex]['options'] = [];
            }
            $this->form_fields[$fieldIndex]['options'][] = '';
        }
    }

    public function removeOption($fieldIndex, $optionIndex)
    {
        if (isset($this->form_fields[$fieldIndex]['options'][$optionIndex])) {
            unset($this->form_fields[$fieldIndex]['options'][$optionIndex]);
            $this->form_fields[$fieldIndex]['options'] = array_values($this->form_fields[$fieldIndex]['options']);
        }
    }

    public function getFieldTypes()
    {
        return [
            'text' => 'Text Input',
            'number' => 'Number',
            'dropdown' => 'Dropdown/Select',
            'textarea' => 'Text Area',
            'file' => 'File Upload'
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
            'specimen' => 'Specimen (SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
            'species_dependent' => 'Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)',
            'field_value_multi' => 'Field-Value Multi-Column (Field:Value format with multiple columns per row)'
        ];
    }

    public function getLayoutTypes()
    {
        return [
            'single_row' => 'Single Field per Row',
            'multi_column' => 'Multiple Fields per Row'
        ];
    }

    public function updatedLayoutType($value)
    {
        if ($value === 'single_row') {
            $this->columns_per_row = 1;
        }
    }

    public function updatedTableType($value)
    {
        // Reset all fields and configuration when table type changes
        $this->resetFieldsAndConfiguration();

        // Set default values based on new table type
        if ($value === 'species_dependent') {
            $this->specimen_name = '';
            $this->species_config = [
                'results' => 'res 1, res 2',
                'units' => 'cells/mL, CFU/mL, %',
                'species_dependencies' => [
                    'res 1' => 'N/A, E. coli, Salmonella',
                    'res 2' => 'N/A, different options'
                ],
                'stage_dependencies' => [
                    'E. coli' => 'Early, Mid, Late',
                    'Salmonella' => 'different options'
                ]
            ];
        } elseif ($value === 'specimen') {
            $this->specimen_name = '';
        } elseif ($value === 'field_value_multi') {
            $this->field_value_columns = 4;
            $this->field_value_separator = ': ';
        } else {
            $this->specimen_name = '';
            $this->species_config = [
                'results' => 'res 1, res 2',
                'units' => 'cells/mL, CFU/mL, %',
                'species_dependencies' => [],
                'stage_dependencies' => []
            ];
        }
    }

    private function resetFieldsAndConfiguration()
    {
        // Reset form fields
        $this->form_fields = [];
        $this->field_counter = 0;

        // Reset field groups
        $this->field_groups = [];
        $this->current_group = '';

        // Reset layout configuration
        $this->layout_type = 'single_row';
        $this->columns_per_row = 1;
    }

    public function resetForm()
    {
        // Reset basic template information
        $this->test_name = '';
        $this->short_name = '';
        $this->test_type = '';
        $this->category_id = '';
        $this->charge_category_id = '';
        $this->standard_charge = '';
        $this->report_days = '';
        $this->icon_class = 'fas fa-flask';
        $this->icon_color = '#007bff';
        $this->specimen_name = '';

        // Reset form fields
        $this->form_fields = [];
        $this->current_group = '';
        $this->field_counter = 0;
    }

    public function testSubmission()
    {
        Log::info('Test submission called', [
            'test_name' => $this->test_name,
            'form_fields_count' => count($this->form_fields),
        ]);

        try {
            $tableExists = Schema::hasTable('pathology_test_templates');
            $columns = Schema::getColumnListing('pathology_test_templates');

            Log::info('Database test results', [
                'table_exists' => $tableExists,
                'columns' => $columns,
                'has_form_configuration' => in_array('form_configuration', $columns),
                'has_is_dynamic_form' => in_array('is_dynamic_form', $columns)
            ]);

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
                $testTemplate->delete();

                session()->flash('message', 'Database test successful! Template creation works. Fields: ' . count($this->form_fields));
            } else {
                session()->flash('error', 'Database table missing required columns: form_configuration, is_dynamic_form');
            }
        } catch (\Exception $e) {
            Log::error('Database test failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Database test failed: ' . $e->getMessage());
        }
    }

    public function manualSubmit()
    {
        Log::info('Manual submit called - user clicked the button');
        $this->store();
    }

    public function store()
    {
        // Build validation rules dynamically
        $validationRules = [
            'test_name' => 'required|string|max:255',
            'test_type' => 'required|string|max:255',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'standard_charge' => 'required|numeric|min:0',
            'table_type' => 'required|in:standard,simple,specimen,species_dependent,field_value_multi',
        ];

        // Add specimen_name validation only for specific table types
        if (in_array($this->table_type, ['specimen', 'species_dependent'])) {
            $validationRules['specimen_name'] = 'required|string|max:255';
        }

        // Validate basic template information
        $this->validate($validationRules);

        try {

            // Use dynamic rules
            $this->validate($this->rules());

            // Check if form fields are required (not needed for species_dependent templates)
            if ($this->table_type !== 'species_dependent' && empty($this->form_fields)) {
                throw new \Exception('At least one form field is required.');
            }

            // Validate form fields (only for non-species_dependent templates)
            if ($this->table_type !== 'species_dependent') {
                foreach ($this->form_fields as $index => $field) {
                    if (empty($field['label'])) {
                        throw new \Exception('Field #' . ($index + 1) . ' label is required.');
                    }
                    if (empty($field['type'])) {
                        throw new \Exception('Field #' . ($index + 1) . ' type is required.');
                    }
                }
            }

            // Generate field names (only for non-species_dependent templates)
            if ($this->table_type !== 'species_dependent') {
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

                    if (isset($this->form_fields[$idx]['type']) && is_array($this->form_fields[$idx]['type'])) {
                        $this->form_fields[$idx]['type'] = 'text';
                    }
                }
            }

            // Validate reference ranges (only for non-species_dependent templates)
            if ($this->table_type !== 'species_dependent') {
                $this->validateReferenceRanges();
            }

            $template = PathologyTestTemplate::create([
                'test_name' => $this->test_name,
                'short_name' => $this->short_name,
                'test_type' => $this->test_type,
                'category_id' => $this->category_id,
                'charge_category_id' => $this->charge_category_id,
                'standard_charge' => $this->standard_charge,
                'report_days' => $this->report_days,
                'icon_class' => $this->icon_class,
                'icon_color' => $this->icon_color,
                'is_dynamic_form' => true,
                'form_configuration' => [
                    'table_type' => $this->table_type,
                    'layout_type' => $this->layout_type,
                    'columns_per_row' => $this->columns_per_row,
                    'specimen_name' => $this->specimen_name,
                    'species_config' => $this->table_type === 'species_dependent' ? $this->species_config : null,
                    'field_value_config' => $this->table_type === 'field_value_multi' ? [
                        'columns' => $this->field_value_columns,
                        'separator' => $this->field_value_separator
                    ] : null,
                    'fields' => $this->form_fields
                ],
            ]);

            Log::info('Template created successfully', ['template_id' => $template->id]);

            session()->flash('message', 'Dynamic template created successfully.');
            $this->resetForm();

            return redirect()->route('pathology.test.template.index')->with('success', 'Dynamic template created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create template', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to create dynamic template: ' . $e->getMessage());
        }
    }

    private function validateReferenceRanges()
    {
        foreach ($this->form_fields as $index => $field) {
            if (isset($field['type']) && $field['type'] === 'number') {
                $min = $field['reference_min'] ?? null;
                $max = $field['reference_max'] ?? null;
                if ($min !== null || $max !== null) {
                    if ($min === null || $max === null) {
                        throw new \Exception('Field #' . ($index + 1) . ' must have both minimum and maximum reference values.');
                    }
                    if ($min >= $max) {
                        throw new \Exception('Field #' . ($index + 1) . ' minimum reference value must be less than maximum.');
                    }
                }
            }
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
}
