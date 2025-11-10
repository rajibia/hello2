<?php

namespace App\Http\Livewire;

use App\Models\PathologyTestTemplate;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EditDynamicPathologyTemplate extends Component
{
    use WithFileUploads;

    public $templateId;
    public $isUpdating = false;

    // Form fields
    public $test_name = '';
    public $short_name = '';
    public $test_type = '';
    public $category_id = '';
    public $charge_category_id = '';
    public $standard_charge = '';
    public $report_days = '';
    public $icon_class = 'fas fa-flask';
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

    // Species dependent configuration
    public $species_config = [
        'results' => '',
        'units' => '',
        'species_dependencies' => [],
        'stage_dependencies' => []
    ];

    // Field value multi-column configuration
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

    public function mount($templateId)
    {
        $this->templateId = $templateId;
        $this->loadTemplate();
    }

    public function loadTemplate()
    {
        $template = PathologyTestTemplate::find($this->templateId);

        if (!$template) {
            session()->flash('error', 'Template not found.');
            return;
        }

        // Load basic template data
        $this->test_name = $template->test_name ?? '';
        $this->short_name = $template->short_name ?? '';
        $this->test_type = $template->test_type ?? '';
        $this->category_id = $template->category_id;
        $this->charge_category_id = $template->charge_category_id;
        $this->standard_charge = $template->standard_charge;
        $this->report_days = $template->report_days ?? '';
        $this->icon_class = $template->icon_class ?? 'fas fa-flask';
        $this->icon_color = $template->icon_color ?? '#007bff';

        // Load form configuration
        $formConfig = $template->form_configuration ?? [];

        // Load template configuration
        $this->table_type = $formConfig['table_type'] ?? 'standard';
        $this->layout_type = $formConfig['layout_type'] ?? 'single_row';
        $this->columns_per_row = $formConfig['columns_per_row'] ?? 1;
        $this->specimen_name = $formConfig['specimen_name'] ?? '';

        // Load field value configuration if exists
        if (isset($formConfig['field_value_config']) && is_array($formConfig['field_value_config'])) {
            $this->field_value_columns = $formConfig['field_value_config']['columns'] ?? 4;
            $this->field_value_separator = $formConfig['field_value_config']['separator'] ?? ': ';
        } else {
            $this->field_value_columns = 4;
            $this->field_value_separator = ': ';
        }

        // Load form fields
        if (isset($formConfig['fields']) && is_array($formConfig['fields'])) {
            $this->form_fields = [];

            foreach ($formConfig['fields'] as $field) {
                if (!is_array($field)) continue;

                $this->form_fields[] = [
                    'id' => $field['id'] ?? $this->field_counter + 1,
                    'name' => (string) ($field['name'] ?? ''),
                    'label' => (string) ($field['label'] ?? ''),
                    'type' => (string) ($field['type'] ?? 'text'),
                    'placeholder' => (string) ($field['placeholder'] ?? ''),
                    'unit' => (string) ($field['unit'] ?? ''),
                    'options' => is_array($field['options'] ?? []) ? $field['options'] : [],
                    'validation' => (string) ($field['validation'] ?? ''),
                    'reference_min' => $field['reference_min'] ?? null,
                    'reference_max' => $field['reference_max'] ?? null
                ];
            }

            $this->field_counter = count($this->form_fields);
        }

        // Load species configuration if exists
        if (isset($formConfig['species_config'])) {
            $this->species_config = $formConfig['species_config'];
        }
    }

    public function render()
    {
        $categories = PathologyCategory::pluck('name', 'id');
        $chargeCategories = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');

        return view('livewire.edit-dynamic-pathology-template', compact('categories', 'chargeCategories'));
    }

    public function updatedChargeCategoryId($value)
    {
        if ($value) {
            $charge = Charge::where('charge_category_id', $value)->first();
            if ($charge) {
                $this->standard_charge = $charge->standard_charge ?? 0;
            } else {
                $this->standard_charge = '';
            }
        } else {
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

    public function getFieldTypes()
    {
        return [
            'text' => 'Text',
            'number' => 'Number',
            'dropdown' => 'Dropdown',
            'textarea' => 'Text Area',
            'file' => 'File Upload'
        ];
    }

    public function getValidationRules()
    {
        return [
            'required' => 'Required',
            'email' => 'Email',
            'numeric' => 'Numeric',
            'min' => 'Minimum Value',
            'max' => 'Maximum Value'
        ];
    }

    public function getTableTypes()
    {
        $types = [
            'standard' => 'Standard (ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
            'simple' => 'Simple (ANALYTE, RESULTS)',
            'specimen' => 'Specimen (SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
            'species_dependent' => 'Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)',
            'field_value_multi' => 'Field-Value Multi-Column (Field:Value format with multiple columns per row)'
        ];

        // Ensure all values are strings
        foreach ($types as $key => $value) {
            if (!is_string($value)) {
                $types[$key] = (string) $value;
            }
        }

        return $types;
    }

    public function getLayoutTypes()
    {
        $types = [
            'single_row' => 'Single Field per Row',
            'multi_column' => 'Multiple Fields per Row'
        ];

        // Ensure all values are strings
        foreach ($types as $key => $value) {
            if (!is_string($value)) {
                $types[$key] = (string) $value;
            }
        }

        return $types;
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

    public function resetFieldsAndConfiguration()
    {
        $this->form_fields = [];
        $this->field_counter = 0;
        $this->species_config = [
            'results' => '',
            'units' => '',
            'species_dependencies' => [],
            'stage_dependencies' => []
        ];
    }

    public function addField()
    {
        $this->form_fields[] = [
            'id' => $this->field_counter + 1,
            'name' => '',
            'label' => '',
            'type' => 'text',
            'placeholder' => '',
            'unit' => '',
            'options' => [],
            'validation' => '',
            'reference_min' => null,
            'reference_max' => null
        ];
        $this->field_counter++;
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

    public function update()
    {
        // Validate basic template information
        $this->validate([
            'test_name' => 'required|string|max:255',
            'test_type' => 'required|string|max:255',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'standard_charge' => 'required|numeric|min:0',
            'table_type' => 'required|in:standard,simple,specimen,species_dependent,field_value_multi',
        ]);

        try {
            $template = PathologyTestTemplate::findOrFail($this->templateId);

            // Update basic template information
            $template->update([
                'test_name' => $this->test_name,
                'short_name' => $this->short_name,
                'test_type' => $this->test_type,
                'category_id' => $this->category_id,
                'charge_category_id' => $this->charge_category_id,
                'standard_charge' => $this->standard_charge,
                'report_days' => $this->report_days,
                'icon_class' => $this->icon_class,
                'icon_color' => $this->icon_color,
            ]);

            // Update form configuration
            $formConfig = [
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
            ];

            $template->update([
                'form_configuration' => $formConfig
            ]);

            session()->flash('message', 'Dynamic template updated successfully.');
            return redirect()->route('pathology.test.template.index')->with('success', 'Dynamic template updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update dynamic template: ' . $e->getMessage());
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

    private function parseReferenceValue($value)
    {
        // If it's an empty string, return null
        if ($value === '' || $value === null) {
            return null;
        }

        // If it's numeric, return as float
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Otherwise return null
        return null;
    }
}
