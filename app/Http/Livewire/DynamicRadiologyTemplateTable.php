<?php

namespace App\Http\Livewire;

use App\Models\RadiologyTestTemplate;
use App\Models\RadiologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DynamicRadiologyTemplateTable extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showPreviewModal = false;
    public $editingTemplate = null;
    public $previewTemplate = null;
    public $isSubmitting = false;
    public $isUpdating = false;
    public $loading = false;

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

    // Dynamic form builder
    public $form_fields = [];
    public $field_counter = 0;
    public $field_groups = [];
    public $current_group = '';

    protected $rules = [
        'test_name' => 'required|string|max:160',
        'short_name' => 'required|string',
        'test_type' => 'required|string',
        'category_id' => 'required|exists:radiology_categories,id',
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

    public function render()
    {
        $templates = RadiologyTestTemplate::with('radiologycategory')
            ->where('is_dynamic_form', true)
            ->when($this->search, function($query) {
                $query->where('test_name', 'like', '%' . $this->search . '%')
                      ->orWhere('short_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = RadiologyCategory::pluck('name', 'id');
        $chargeCategories = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');

        return view('livewire.dynamic-radiology-template-table', compact('templates', 'categories', 'chargeCategories'));
    }

    public function refreshTemplates()
    {
        $this->emit('$refresh');
    }

    public function updatedChargeCategoryId($value)
    {
        if (!empty($value)) {
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

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function preview($id)
    {
        $this->loading = true;

        try {
            $this->previewTemplate = RadiologyTestTemplate::with('radiologycategory')
                ->where('id', $id)
                ->first()
                ->fresh();

            $this->previewTemplate->refresh();

            Log::info('Preview template loaded', [
                'template_id' => $this->previewTemplate->id,
                'test_name' => $this->previewTemplate->test_name,
                'form_configuration' => $this->previewTemplate->form_configuration,
                'form_configuration_count' => is_array($this->previewTemplate->form_configuration) ? count($this->previewTemplate->form_configuration) : 0,
                'raw_form_configuration' => $this->previewTemplate->getRawOriginal('form_configuration')
            ]);

            $this->showPreviewModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading template preview: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
        $this->previewTemplate = null;
    }

    public function refreshPreview()
    {
        if ($this->previewTemplate) {
            $this->previewTemplate = RadiologyTestTemplate::with('radiologycategory')
                ->where('id', $this->previewTemplate->id)
                ->first()
                ->fresh();

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
            $template = RadiologyTestTemplate::findOrFail($id);

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
            $this->report_days = $template->report_days;
            $this->icon_class = $template->icon_class;
            $this->icon_color = $template->icon_color;
            $this->form_configuration = $template->form_configuration ?? [];

            $formFields = $template->form_configuration ?? [];
            Log::info('Loading template for edit', [
                'template_id' => $template->id,
                'original_form_configuration' => $formFields,
                'form_fields_count' => count($formFields)
            ]);

            $this->form_fields = array_map(function($field) {
                return [
                    'id' => $field['id'] ?? uniqid(),
                    'name' => $field['name'] ?? '',
                    'label' => $field['label'] ?? '',
                    'type' => $field['type'] ?? 'text',
                    'required' => $field['required'] ?? '0',
                    'options' => $field['options'] ?? [],
                    'placeholder' => $field['placeholder'] ?? '',
                    'default_value' => $field['default_value'] ?? '',
                    'validation' => $field['validation'] ?? '',
                    'group' => $field['group'] ?? 'General',
                    'order' => $field['order'] ?? 0,
                    'reference_min' => $field['reference_min'] ?? null,
                    'reference_max' => $field['reference_max'] ?? null,
                    'unit' => $field['unit'] ?? '',
                ];
            }, $formFields);

            $this->showEditModal = true;

        } catch (\Exception $e) {
            Log::error('Failed to load template for edit', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to load template for editing: ' . $e->getMessage());
        }
    }

    public function store()
    {
        $this->validate();

        try {
            $this->isSubmitting = true;

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
                'form_configuration' => $this->form_fields,
                'is_dynamic_form' => true,
            ];

            $template = RadiologyTestTemplate::create($templateData);

            Log::info('Template created successfully', ['template_id' => $template->id]);

            session()->flash('message', 'Dynamic template created successfully.');
            $this->showCreateModal = false;
            $this->resetForm();
            $this->refreshTemplates();

        } catch (\Exception $e) {
            Log::error('Failed to create template', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to create dynamic template: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $this->isUpdating = true;

            $templateId = $this->editingTemplate->id;

            $updateData = [
                'test_name' => $this->test_name,
                'short_name' => $this->short_name,
                'test_type' => $this->test_type,
                'category_id' => $this->category_id,
                'charge_category_id' => $this->charge_category_id,
                'standard_charge' => $this->standard_charge,
                'report_days' => $this->report_days,
                'icon_class' => $this->icon_class,
                'icon_color' => $this->icon_color,
                'form_configuration' => $this->form_fields,
            ];

            $result = $this->editingTemplate->update($updateData);

            Log::info('Update result', ['result' => $result, 'updated_template' => $this->editingTemplate->fresh()]);
            Log::info('Template updated successfully', ['template_id' => $templateId]);

            $this->editingTemplate->refresh();
            DB::flushQueryLog();

            session()->flash('message', 'Dynamic template updated successfully.');
            $this->showEditModal = false;
            $this->resetForm();
            $this->refreshTemplates();

            if ($this->previewTemplate && $this->previewTemplate->id == $templateId) {
                $this->previewTemplate = null;
            }

            $this->dispatchBrowserEvent('template-updated', ['template_id' => $templateId]);
        } catch (\Exception $e) {
            Log::error('Failed to update template', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Failed to update dynamic template: ' . $e->getMessage());
        } finally {
            $this->isUpdating = false;
        }
    }

    public function delete($id)
    {
        $this->loading = true;

        try {
            $template = RadiologyTestTemplate::findOrFail($id);
            $template->delete();
            session()->flash('message', 'Dynamic template deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting template: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    // Dynamic form builder methods
    public function addField()
    {
        $this->field_counter++;
        $this->form_fields[] = [
            'id' => $this->field_counter,
            'name' => '',
            'label' => '',
            'type' => 'text',
            'group' => 'General', // Default to General, user can change via dropdown
            'required' => '0',
            'placeholder' => '',
            'options' => [],
            'reference_min' => null,
            'reference_max' => null,
            'unit' => '',
            'validation' => '',
            'order' => count($this->form_fields)
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
        // Restricted to the types needed for radiology templates
        return [
            'text' => 'Text Input',
            'number' => 'Number',
            'dropdown' => 'Dropdown/Select',
            'textarea' => 'Text Area',
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

    private function resetForm()
    {
        $this->test_name = '';
        $this->short_name = '';
        $this->test_type = '';
        $this->category_id = '';
        $this->charge_category_id = '';
        $this->standard_charge = '';
        $this->report_days = '';
        $this->icon_class = '';
        $this->icon_color = '#007bff';
        $this->form_configuration = [];
        $this->form_fields = [];
        $this->field_groups = [];
        $this->current_group = '';
        $this->field_counter = 0;
        $this->editingTemplate = null;
        $this->isSubmitting = false;
        $this->isUpdating = false;
    }

    public function testSubmission()
    {
        Log::info('Test submission called', [
            'test_name' => $this->test_name,
            'form_fields_count' => count($this->form_fields),
            'showCreateModal' => $this->showCreateModal
        ]);

        // Test database connection and table structure
        try {
            $tableExists = Schema::hasTable('radiology_test_templates');
            $columns = Schema::getColumnListing('radiology_test_templates');

            Log::info('Database test results', [
                'table_exists' => $tableExists,
                'columns' => $columns,
                'has_form_configuration' => in_array('form_configuration', $columns),
                'has_is_dynamic_form' => in_array('is_dynamic_form', $columns)
            ]);

            // Try to create a minimal test template
            if ($tableExists && in_array('form_configuration', $columns) && in_array('is_dynamic_form', $columns)) {
                $testTemplate = RadiologyTestTemplate::create([
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
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showPreviewModal = false;
        $this->resetForm();
        $this->previewTemplate = null;
    }

    public function manualSubmit()
    {
        $this->store();
    }
}
