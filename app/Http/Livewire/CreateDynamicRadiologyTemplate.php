<?php

namespace App\Http\Livewire;

use App\Models\RadiologyTestTemplate;
use App\Models\RadiologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDynamicRadiologyTemplate extends Component
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

    // Data arrays for dropdowns
    public $categories = [];
    public $chargeCategories = [];

    protected $rules = [
        'test_name' => 'required|string|max:160',
        'short_name' => 'required|string',
        'test_type' => 'required|string',
        'category_id' => 'required|exists:radiology_categories,id',
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
            'category_id' => 'required|exists:radiology_categories,id',
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
        $this->loadCategories();
        $this->loadChargeCategories();
    }

    public function render()
    {
        return view('livewire.create-dynamic-radiology-template');
    }

    public function loadCategories()
    {
        $this->categories = RadiologyCategory::pluck('name', 'id')->toArray();
    }

    public function loadChargeCategories()
    {
        $this->chargeCategories = ChargeCategory::where('charge_type', 1)->pluck('name', 'id')->toArray();
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
        if (!empty($value)) {
            $this->specimen_name = $value;
        }
    }

    public function updatedSpeciesConfigResults($value)
    {
        // Clear species dependencies when results change
        if (!empty($value)) {
            $this->species_config['species_dependencies'] = [];
            $this->species_config['stage_dependencies'] = [];
        }
    }

    public function updatedSpeciesConfigSpeciesDependencies($value, $key)
    {
        // Clear stage dependencies when species dependencies change
        if (!empty($value)) {
            $this->species_config['stage_dependencies'] = [];
        }
    }

    public function editGroup($index, $newName)
    {
        if (isset($this->field_groups[$index]) && !empty($newName)) {
            $this->field_groups[$index] = $newName;
        }
    }

    public function updatedLayoutType($value)
    {
        if ($value === 'single_row') {
            $this->columns_per_row = 1;
        }
    }

    public function updatedTableType($value)
    {
        // Reset form fields when table type changes
        $this->form_fields = [];
        $this->field_groups = [];
        $this->current_group = '';

        // Set default values based on table type
        if ($value === 'field_value_multi') {
            $this->layout_type = 'single_row';
            $this->columns_per_row = 1;
        } elseif ($value === 'species_dependent') {
            $this->layout_type = 'single_row';
            $this->columns_per_row = 1;
        }
    }

    public function addField()
    {
        $this->form_fields[] = [
            'name' => '',
            'label' => '',
            'type' => 'text',
            'required' => '0',
            'placeholder' => '',
            'unit' => '',
            'group' => 'General',
            'validation' => '',
            'reference_min' => '',
            'reference_max' => '',
            'options' => []
        ];
        $this->field_counter++;
    }

    public function removeField($index)
    {
        unset($this->form_fields[$index]);
        $this->form_fields = array_values($this->form_fields);
    }

    public function addGroup()
    {
        if (!empty($this->current_group) && !in_array($this->current_group, $this->field_groups)) {
            $this->field_groups[] = $this->current_group;
            $this->current_group = '';
        }
    }

    public function removeGroup($index)
    {
        unset($this->field_groups[$index]);
        $this->field_groups = array_values($this->field_groups);
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

    public function getFieldTypes()
    {
        return [
            'text' => 'Text Input',
            'number' => 'Number Input',
            'textarea' => 'Text Area',
            'dropdown' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkbox',
            'date' => 'Date',
            'time' => 'Time',
            'datetime' => 'Date & Time',
            'file' => 'File Upload',
            'image' => 'Image Upload'
        ];
    }

    public function getValidationRules()
    {
        return [
            'required' => 'Required',
            'email' => 'Email',
            'numeric' => 'Numeric',
            'alpha' => 'Alphabetic',
            'alphanumeric' => 'Alphanumeric',
            'min:3' => 'Minimum 3 characters',
            'max:50' => 'Maximum 50 characters',
            'min:0' => 'Minimum 0',
            'max:100' => 'Maximum 100'
        ];
    }

    public function getTableTypes()
    {
        return [
            'standard' => 'Standard',
            'simple' => 'Simple',
            'specimen' => 'Specimen',
            'species_dependent' => 'Species Dependent',
            'field_value_multi' => 'Field-Value Multi-Column'
        ];
    }

    public function getLayoutTypes()
    {
        return [
            'single_row' => 'Single Row',
            'multi_column' => 'Multi Column'
        ];
    }

    public function previewTemplate()
    {
        // Validate basic fields for preview
        $this->validate([
            'test_name' => 'required|string|max:160',
            'short_name' => 'required|string',
            'test_type' => 'required|string',
            'category_id' => 'required|exists:radiology_categories,id',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'standard_charge' => 'required|numeric|min:0',
        ]);

        // Build form configuration for preview
        $formConfig = $this->buildFormConfiguration();

        // Store preview data in session for the preview page
        session([
            'radiology_template_preview' => [
                'test_name' => $this->test_name,
                'short_name' => $this->short_name,
                'test_type' => $this->test_type,
                'category_id' => $this->category_id,
                'charge_category_id' => $this->charge_category_id,
                'standard_charge' => $this->standard_charge,
                'report_days' => $this->report_days,
                'icon_class' => $this->icon_class,
                'icon_color' => $this->icon_color,
                'form_configuration' => $formConfig
            ]
        ]);

        // Redirect to preview page
        return redirect()->route('radiology.test.template.preview');
    }

    public function store()
    {
        try {
            // Log the validation attempt
            Log::info('Starting validation for radiology template creation');

            $this->validate();

            Log::info('Validation passed, starting database transaction');

            DB::beginTransaction();

            // Build form configuration based on table type
            $formConfig = $this->buildFormConfiguration();

            Log::info('Form configuration built', ['config' => $formConfig]);

            $templateData = [
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
                'form_configuration' => $formConfig
            ];

            Log::info('Template data prepared', ['data' => $templateData]);

            $template = RadiologyTestTemplate::create($templateData);

            Log::info('Template created successfully', ['template_id' => $template->id]);

            DB::commit();

            session()->flash('message', 'Dynamic radiology template created successfully!');
            return redirect()->route('radiology.test.template.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for radiology template', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating dynamic radiology template: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating template: ' . $e->getMessage());
        }
    }

    private function buildFormConfiguration()
    {
        $config = [
            'table_type' => $this->table_type,
            'layout_type' => $this->layout_type,
            'columns_per_row' => $this->columns_per_row,
            'specimen_name' => $this->specimen_name,
            'fields' => $this->form_fields,
            'field_groups' => $this->field_groups
        ];

        // Add specific configurations based on table type
        switch ($this->table_type) {
            case 'species_dependent':
                $config['species_config'] = $this->species_config;
                break;
            case 'field_value_multi':
                $config['field_value_config'] = [
                    'columns' => $this->field_value_columns,
                    'separator' => $this->field_value_separator
                ];
                break;
        }

        return $config;
    }

}
