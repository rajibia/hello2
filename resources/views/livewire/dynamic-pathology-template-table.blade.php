<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h4 class="mb-0 me-3">{{ __('Dynamic Pathology Templates') }}</h4>
            <span class="badge bg-primary">{{ $templates->total() }} {{ __('Templates') }}</span>
                </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pathology-tests-templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>{{ __('Create Template') }}
            </a>
                </div>
            </div>

    <div class="card">

        <div class="card-body">
            <!-- Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search templates...">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small">
                            <th style="width:20%">{{ __('Template Name') }}</th>
                            <th style="width:15%">{{ __('Template Type') }}</th>
                            <th style="width:12%">{{ __('Test Type') }}</th>
                            <th style="width:12%">{{ __('Category') }}</th>
                            <th style="width:10%" class="text-center">{{ __('Fields') }}</th>
                            <th style="width:10%" class="text-end">{{ __('Charge') }}</th>
                            <th style="width:8%" class="text-center">{{ __('Report Days') }}</th>
                            <th style="width:13%" class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            @php
                                $formConfig = is_array($template->form_configuration) ? $template->form_configuration : [];
                                $tableType = $formConfig['table_type'] ?? 'standard';
                                $tableTypeLabels = [
                                    'standard' => 'Standard',
                                    'simple' => 'Simple',
                                    'specimen' => 'Specimen',
                                    'species_dependent' => 'Species Dependent',
                                    'field_value_multi' => 'Field-Value Multi-Column'
                                ];
                                $templateTypeLabel = $tableTypeLabels[$tableType] ?? 'Standard';
                                $fieldCount = isset($formConfig['fields']) ? count($formConfig['fields']) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($template->icon_class)
                                            <i class="{{ $template->icon_class }} mr-2" style="color: {{ $template->icon_color }}"></i>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $template->test_name }}</div>
                                            @if($template->short_name)
                                                <small class="text-muted">{{ $template->short_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $templateTypeLabel }}</span>
                                </td>
                                <td>{{ $template->test_type }}</td>
                                <td>{{ $template->pathologycategory->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark" style="font-weight:600;">
                                        {{ $fieldCount }} {{ __('Fields') }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($template->standard_charge, 2) }}</td>
                                <td class="text-center">{{ $template->report_days ? $template->report_days . ' days' : 'Not specified' }}</td>
                                <td class="text-center">
                                    <button wire:click="preview({{ $template->id }})" class="btn btn-sm btn-info me-1" title="Preview Template">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('pathology-tests-templates.edit-dynamic', $template->id) }}" class="btn btn-sm btn-primary me-1" title="Edit Template">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete({{ $template->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete Template">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('No dynamic templates found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                @if($templates->hasPages())
                    {{ $templates->links('vendor.pagination.livewire-bootstrap-4') }}
                                                @endif
                                        </div>
                                    </div>
                                </div>



    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif



    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Dynamic Pathology Template') }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editTemplateForm">
                        <!-- Same form fields as create modal -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_test_name">{{ __('Test Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_test_name" wire:model="test_name" wire:keydown.enter.prevent required>
                                    @error('test_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_short_name">{{ __('Short Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_short_name" wire:model="short_name" wire:keydown.enter.prevent required>
                                    @error('short_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_test_type">{{ __('Test Type') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_test_type" wire:model="test_type" wire:keydown.enter.prevent required>
                                    @error('test_type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id">{{ __('Category') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_category_id" wire:model="category_id" required>
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach($categories as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_charge_category_id">{{ __('Charge Category') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_charge_category_id" wire:model="charge_category_id" required>
                                        <option value="">{{ __('Select Charge Category') }}</option>
                                        @foreach($chargeCategories as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('charge_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_standard_charge">
                                        {{ __('Standard Charge') }} <span class="text-danger">*</span>
                                        @if($charge_category_id)
                                            <small class="text-info ms-1">
                                                <i class="fas fa-magic me-1"></i>{{ __('Auto-filled from category') }}
                                            </small>
                                        @endif
                                    </label>
                                    <input type="number" step="0.01" class="form-control" id="edit_standard_charge" wire:model="standard_charge" required readonly>
                                    @error('standard_charge') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Report Days for Edit -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_report_days">{{ __('Report Days') }}</label>
                                    <input type="number" min="0" max="365" class="form-control" id="edit_report_days" wire:model="report_days" placeholder="Enter number of days for report delivery">
                                    <small class="text-muted">{{ __('Number of days required to deliver the test report') }}</small>
                                    @error('report_days') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_icon_class">{{ __('Icon Class') }}</label>
                                    <input type="text" class="form-control" id="edit_icon_class" wire:model="icon_class" wire:keydown.enter.prevent placeholder="fas fa-flask">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_icon_color">{{ __('Icon Color') }}</label>
                                    <input type="color" class="form-control" id="edit_icon_color" wire:model="icon_color">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Dynamic Form Builder for Edit -->
                        <div class="row">
                            <div class="col-12">
                                <h5>{{ __('Form Builder') }}</h5>

                                <!-- Group Management -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" wire:model.defer="current_group" wire:keydown.enter.prevent="addGroup" placeholder="Enter field group name">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" wire:click.prevent="addGroup">
                                                    <i class="fas fa-plus"></i> {{ __('Add Group') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" wire:click="addField">
                                            <i class="fas fa-plus"></i> {{ __('Add Field') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Available Groups Display -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-cog me-2"></i>{{ __('Available Groups') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @if(count($field_groups) > 0)
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($field_groups as $index => $group)
                                                            <span class="badge bg-primary text-white" style="cursor: pointer; font-size: 12px; padding: 6px 12px;">
                                                                {{ $group }}
                                                                <i class="fas fa-times ms-1" style="font-size: 10px;" wire:click="removeGroup({{ $index }})"></i>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-0">{{ __('No groups added yet. Add groups to organize your form fields.') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Fields for Edit -->
                                @if(count($form_fields) > 0)
                                <div class="form-fields-container">
                                    @foreach($form_fields as $index => $field)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 mr-2">{{ __('Field') }} #{{ $index + 1 }}</h6>
                                                    @if(isset($field['group']) && $field['group'] !== 'General')
                                                        <span class="badge bg-info text-white" style="font-size:10px; padding:4px 8px;">{{ $field['group'] }}</span>
                                                    @endif
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger" wire:click="removeField({{ $index }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Same field structure as create modal -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Field Name') }}</label>
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.name" placeholder="e.g., patient_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Field Label') }}</label>
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.label" placeholder="e.g., Patient Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ __('Field Type') }}</label>
                                                        <select class="form-control" wire:model="form_fields.{{ $index }}.type">
                                                            @foreach($this->getFieldTypes() as $value => $label)
                                                                <option value="{{ $value }}">{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ __('Field Group') }}</label>
                                                        <select class="form-control" wire:model="form_fields.{{ $index }}.group">
                                                            <option value="General">General</option>
                                                            @foreach($field_groups as $group)
                                                                <option value="{{ $group }}">{{ $group }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ __('Required') }}</label>
                                                        <select class="form-control" wire:model="form_fields.{{ $index }}.required">
                                                            <option value="0">{{ __('No') }}</option>
                                                            <option value="1">{{ __('Yes') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Placeholder') }}</label>
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.placeholder" placeholder="Enter placeholder text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Unit') }}</label>
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.unit" placeholder="e.g., mg/dL">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @php
                                                    $fieldType = isset($field['type']) ? (is_string($field['type']) ? $field['type'] : 'text') : 'text';
                                                    $isNumberField = $fieldType === 'number';
                                                @endphp

                                                @if($isNumberField)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ __('Ref. Min') }}</label>
                                                        <input type="number" step="any" class="form-control" wire:model="form_fields.{{ $index }}.reference_min" placeholder="e.g., 4.5">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ __('Ref. Max') }}</label>
                                                        <input type="number" step="any" class="form-control" wire:model="form_fields.{{ $index }}.reference_max" placeholder="e.g., 11.0">
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Validation</label>
                                                        <select class="form-control" wire:model="form_fields.{{ $index }}.validation">
                                                            <option value="">{{ __('None') }}</option>
                                                            @foreach($this->getValidationRules() as $value => $label)
                                                                <option value="{{ $value }}">{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Options for dropdown, radio, checkbox -->
                                            @php
                                                $fieldType = isset($field['type']) ? (is_string($field['type']) ? $field['type'] : 'text') : 'text';
                                                $showOptions = in_array($fieldType, ['dropdown', 'radio', 'checkbox']);
                                            @endphp
                                            @if($showOptions)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>{{ __('Options') }}</label>
                                                        <div class="options-container">
                                                            @if(isset($field['options']) && is_array($field['options']) && count($field['options']) > 0)
                                                                @foreach($field['options'] as $optionIndex => $option)
                                                                <div class="input-group mb-2">
                                                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.options.{{ $optionIndex }}" placeholder="Option {{ $optionIndex + 1 }}">
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-outline-danger" wire:click="removeOption({{ $index }}, {{ $optionIndex }})">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addOption({{ $index }})">
                                                                <i class="fas fa-plus"></i> {{ __('Add Option') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('No fields added yet. Click "Add Field" to start building your form.') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-primary" wire:click="manualUpdate" {{ $isUpdating ? 'disabled' : '' }}>
                                @if($isUpdating)
                                    <i class="fas fa-spinner fa-spin me-1"></i>{{ __('Updating...') }}
                                @else
                                    <i class="fas fa-save me-1"></i>{{ __('Update Template') }}
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScript for Edit Modal -->
    @if($showEditModal)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for group-added events
            window.addEventListener('group-added', function(event) {
                Livewire.rescan();
            });

            // Listen for field-group-updated events
            window.addEventListener('field-group-updated', function(event) {
                Livewire.rescan();
            });

            // Listen for template-updated events
            window.addEventListener('template-updated', function(event) {
                // If preview modal is open, refresh it
                if (document.querySelector('.modal.show[style*="display: block"]')) {
                    Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('refreshPreview');
                }
            });

            // Prevent form submission on Enter key
            const editForm = document.getElementById('editTemplateForm');
            if (editForm) {
                editForm.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
    @endif

<!-- Backdrop cleanup for preview -->
@if($showPreviewModal)
    <style>.modal-backdrop{display:none !important;}</style>
@endif

<!-- Preview Modal -->
@if($showPreviewModal)
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>{{ __('Template Preview') }}
                </h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-light me-2" wire:click="refreshPreview" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button type="button" class="btn-close btn-close-white" wire:click="closePreviewModal">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                @if($previewTemplate)
                <!-- Template Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>{{ __('Template Information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Test Name') }}:</strong> {{ $previewTemplate->test_name }}</p>
                                <p><strong>{{ __('Short Name') }}:</strong> {{ $previewTemplate->short_name }}</p>
                                <p><strong>{{ __('Test Type') }}:</strong> {{ $previewTemplate->test_type }}</p>
                                <p><strong>{{ __('Table Header Type') }}:</strong>
                                    @php
                                        $formConfig = $previewTemplate->form_configuration ?? [];
                                        $tableType = $formConfig['table_type'] ?? 'standard';
                                        $tableTypeLabels = [
                                            'standard' => 'Standard (ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
                                            'simple' => 'Simple (ANALYTE, RESULTS)',
                                            'specimen' => 'Specimen (SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT)',
                                            'species_dependent' => 'Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)',
                                            'field_value_multi' => 'Field-Value Multi-Column (Field:Value format with multiple columns per row)'
                                        ];
                                    @endphp
                                    <span class="badge bg-primary">{{ $tableTypeLabels[$tableType] ?? ucfirst($tableType) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Category') }}:</strong> {{ $previewTemplate->pathologycategory->name ?? 'N/A' }}</p>
                                <p><strong>{{ __('Charge') }}:</strong> {{ number_format($previewTemplate->standard_charge, 2) }}</p>
                                <p><strong>{{ __('Report Days') }}:</strong> {{ $previewTemplate->report_days ? $previewTemplate->report_days . ' days' : 'Not specified' }}</p>
                                <p><strong>{{ __('Fields Count') }}:</strong>
                                    @php
                                        $fields = $formConfig['fields'] ?? [];
                                        $fieldCount = count($fields);
                                    @endphp
                                    <span class="badge bg-info">{{ $fieldCount }} {{ __('Fields') }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Field Types Summary -->
                        @if($fieldCount > 0)
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-primary mb-2"><i class="fas fa-list me-2"></i>{{ __('Field Types Summary') }}</h6>
                            <div class="row">
                                @php
                                    $fieldTypes = [];
                                    foreach($fields as $field) {
                                        $type = $field['type'] ?? 'text';
                                        $fieldTypes[$type] = ($fieldTypes[$type] ?? 0) + 1;
                                    }
                                @endphp
                                @foreach($fieldTypes as $type => $count)
                                <div class="col-md-3 mb-2">
                                    <span class="badge bg-secondary me-1">{{ ucfirst($type) }}</span>
                                    <span class="text-muted">{{ $count }} {{ __('field(s)') }}</span>
                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Template Configuration Details -->
                        @if($tableType === 'species_dependent' && isset($formConfig['species_config']))
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-primary mb-2"><i class="fas fa-dna me-2"></i>{{ __('Species Configuration') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('Results') }}:</strong> {{ $formConfig['species_config']['results'] ?? 'N/A' }}</p>
                                    <p><strong>{{ __('Units') }}:</strong> {{ $formConfig['species_config']['units'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('Species Dependencies') }}:</strong> {{ count($formConfig['species_config']['species_dependencies'] ?? []) }} {{ __('configured') }}</p>
                                    <p><strong>{{ __('Stage Dependencies') }}:</strong> {{ count($formConfig['species_config']['stage_dependencies'] ?? []) }} {{ __('configured') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($tableType === 'specimen' && isset($formConfig['specimen_name']))
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-primary mb-2"><i class="fas fa-flask me-2"></i>{{ __('Specimen Configuration') }}</h6>
                            <p><strong>{{ __('Specimen Name') }}:</strong> <span class="badge bg-warning">{{ $formConfig['specimen_name'] }}</span></p>
                        </div>
                        @endif

                        @if($tableType === 'field_value_multi' && isset($formConfig['field_value_config']))
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-primary mb-2"><i class="fas fa-columns me-2"></i>{{ __('Field-Value Multi-Column Configuration') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('Columns') }}:</strong> {{ $formConfig['field_value_config']['columns'] ?? 'N/A' }}</p>
                                    <p><strong>{{ __('Separator') }}:</strong> {{ $formConfig['field_value_config']['separator'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('Fields Count') }}:</strong>
                                        @php
                                            $fieldValueFields = $formConfig['field_value_config']['fields'] ?? [];
                                            $fieldValueFieldCount = count($fieldValueFields);
                                        @endphp
                                        <span class="badge bg-info">{{ $fieldValueFieldCount }} {{ __('Fields') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Form Preview -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>{{ __('Template Preview') }}
                            @php
                                $formConfig = $previewTemplate->form_configuration ?? [];
                                $tableType = $formConfig['table_type'] ?? 'standard';
                                $fields = $formConfig['fields'] ?? [];
                            @endphp
                            <span class="badge bg-light text-primary ms-2">{{ count($fields) }} {{ __('Fields') }}</span>
                            <span class="badge bg-warning text-dark ms-2">{{ ucfirst($tableType) }}</span>
                        </h6>
                    </div>
                    <div class="card-body bg-light">
                        @php
                            $formConfig = $previewTemplate->form_configuration ?? [];
                            $tableType = $formConfig['table_type'] ?? 'standard';
                            $fields = $formConfig['fields'] ?? [];
                            $speciesConfig = $formConfig['species_config'] ?? null;
                        @endphp

                        <!-- Template Type Specific Preview -->
                        @if($tableType === 'standard')
                            <!-- Standard Template Preview -->
                            <div class="template-preview standard-template">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="fas fa-table me-2"></i>{{ __('Standard Template') }}</h6>
                                    <p class="mb-0">{{ __('Columns: ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT') }}</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-warning">
                                            <tr>
                                                <th class="text-center">ANALYTE</th>
                                                <th class="text-center">RESULTS</th>
                                                <th class="text-center">REFERENCE RANGE</th>
                                                <th class="text-center">FLAG</th>
                                                <th class="text-center">UNIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @foreach($fields as $field)
                                                <tr>
                                                    <td class="fw-bold">
                                                        {{ $field['label'] ?? 'Test Parameter' }}
                                                        <br><small class="text-muted">Type: {{ ucfirst($field['type'] ?? 'text') }}</small>
                                                    </td>
                                                    <td class="text-muted">_________________</td>
                                                    <td>
                                                        @if(isset($field['reference_min']) && isset($field['reference_max']))
                                                            {{ $field['reference_min'] }} - {{ $field['reference_max'] }}
                                                        @else
                                                            Normal Range
                                                        @endif
                                                    </td>
                                                    <td class="text-center">N</td>
                                                    <td>{{ $field['unit'] ?? 'mg/dL' }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>{{ __('No fields configured for this template') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @elseif($tableType === 'simple')
                            <!-- Simple Template Preview -->
                            <div class="template-preview simple-template">
                                <div class="alert alert-success">
                                    <h6 class="alert-heading"><i class="fas fa-list me-2"></i>{{ __('Simple Template') }}</h6>
                                    <p class="mb-0">{{ __('Columns: ANALYTE, RESULTS') }}</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-success">
                                            <tr>
                                                <th class="text-center">ANALYTE</th>
                                                <th class="text-center">RESULTS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @foreach($fields as $field)
                                                <tr>
                                                    <td class="fw-bold">
                                                        {{ $field['label'] ?? 'Test Parameter' }}
                                                        <br><small class="text-muted">Type: {{ ucfirst($field['type'] ?? 'text') }}</small>
                                                    </td>
                                                    <td>
                                                        @if(isset($field['options']) && is_array($field['options']) && count($field['options']) > 0)
                                                            @foreach($field['options'] as $option)
                                                                <span class="badge bg-light text-dark me-1">{{ $option }}</span>
                                                            @endforeach
                                                        @else
                                                            NEGATIVE | POSITIVE
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>{{ __('No fields configured for this template') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @elseif($tableType === 'specimen')
                            <!-- Specimen Template Preview -->
                            <div class="template-preview specimen-template">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading"><i class="fas fa-flask me-2"></i>{{ __('Specimen Template') }}</h6>
                                    <p class="mb-0">{{ __('Columns: SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT') }}</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-warning">
                                            <tr>
                                                <th class="text-center">SPECIMEN</th>
                                                <th class="text-center">RESULTS</th>
                                                <th class="text-center">REFERENCE RANGE</th>
                                                <th class="text-center">FLAG</th>
                                                <th class="text-center">UNIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @foreach($fields as $field)
                                                <tr>
                                                    <td class="fw-bold">{{ $formConfig['specimen_name'] ?? 'SPECIMEN' }}</td>
                                                    <td class="fw-bold">
                                                        {{ $field['label'] ?? 'Test Parameter' }}
                                                        <br><small class="text-muted">Type: {{ ucfirst($field['type'] ?? 'text') }}</small>
                                                    </td>
                                                    <td>
                                                        @if(isset($field['reference_min']) && isset($field['reference_max']))
                                                            {{ $field['reference_min'] }} - {{ $field['reference_max'] }}
                                                        @else
                                                            Normal Range
                                                        @endif
                                                    </td>
                                                    <td class="text-center">N</td>
                                                    <td>{{ $field['unit'] ?? 'mg/dL' }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>{{ __('No fields configured for this template') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @elseif($tableType === 'species_dependent')
                            <!-- Species Dependent Template Preview -->
                            <div class="template-preview species-template">
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading"><i class="fas fa-dna me-2"></i>{{ __('Species Dependent Template') }}</h6>
                                    <p class="mb-0">{{ __('Columns: RESULTS, SPECIES, STAGE, COUNT, UNIT') }}</p>
                                </div>

                                @if($speciesConfig)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-danger">
                                            <tr>
                                                <th class="text-center">RESULTS</th>
                                                <th class="text-center">SPECIES</th>
                                                <th class="text-center">STAGE</th>
                                                <th class="text-center">COUNT</th>
                                                <th class="text-center">UNIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $results = explode(',', $speciesConfig['results'] ?? 'res 1, res 2');
                                                $units = explode(',', $speciesConfig['units'] ?? 'cells/mL, CFU/mL, %');
                                                $speciesDependencies = $speciesConfig['species_dependencies'] ?? [];
                                                $stageDependencies = $speciesConfig['stage_dependencies'] ?? [];
                                            @endphp

                                            @foreach($results as $index => $result)
                                                @php
                                                    $result = trim($result);
                                                    $unit = isset($units[$index]) ? trim($units[$index]) : 'cells/mL';
                                                    $speciesOptions = isset($speciesDependencies[$result]) ? explode(',', $speciesDependencies[$result]) : ['N/A'];
                                                @endphp

                                                @if($index === 0)
                                                    <!-- First result row -->
                                                    <tr>
                                                        <td class="fw-bold">{{ $result }}</td>
                                                        <td>
                                                            @foreach($speciesOptions as $species)
                                                                @php $species = trim($species); @endphp
                                                                @if($species === 'N/A')
                                                                    <span class="badge bg-secondary me-1">{{ $species }}</span>
                                                                @else
                                                                    <span class="badge bg-primary me-1">{{ $species }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach($speciesOptions as $species)
                                                                @php
                                                                    $species = trim($species);
                                                                    $stages = isset($stageDependencies[$species]) ? explode(',', $stageDependencies[$species]) : ['N/A'];
                                                                @endphp
                                                                @if($species !== 'N/A')
                                                                    @foreach($stages as $stage)
                                                                        <span class="badge bg-info me-1">{{ trim($stage) }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary me-1">N/A</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td class="text-muted">_________________</td>
                                                        <td>{{ $unit }}</td>
                                                    </tr>
                                                @else
                                                    <!-- Additional result rows -->
                                                    <tr>
                                                        <td class="fw-bold">{{ $result }}</td>
                                                        <td>
                                                            @foreach($speciesOptions as $species)
                                                                @php $species = trim($species); @endphp
                                                                @if($species === 'N/A')
                                                                    <span class="badge bg-secondary me-1">{{ $species }}</span>
                                                                @else
                                                                    <span class="badge bg-primary me-1">{{ $species }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach($speciesOptions as $species)
                                                                @php
                                                                    $species = trim($species);
                                                                    $stages = isset($stageDependencies[$species]) ? explode(',', $stageDependencies[$species]) : ['N/A'];
                                                                @endphp
                                                                @if($species !== 'N/A')
                                                                    @foreach($stages as $stage)
                                                                        <span class="badge bg-info me-1">{{ trim($stage) }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary me-1">N/A</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td class="text-muted">_________________</td>
                                                        <td>{{ $unit }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ __('Species configuration not available') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                        @elseif($tableType === 'field_value_multi')
                            <!-- Field-Value Multi-Column Template Preview -->
                            @php
                                $fieldValueConfig = $formConfig['field_value_config'] ?? [];
                                $columnsPerRow = $fieldValueConfig['columns'] ?? 4;
                                $separator = $fieldValueConfig['separator'] ?? ': ';
                            @endphp

                            <div class="template-preview field-value-template">
                                <div class="alert alert-primary">
                                    <h6 class="alert-heading"><i class="fas fa-columns me-2"></i>{{ __('Field-Value Multi-Column Template') }}</h6>
                                    <p class="mb-0">{{ __('Field:Value format with multiple columns per row') }}</p>
                                    <div class="mt-2">
                                        <small class="text-white">
                                            <strong>{{ __('Configuration:') }}</strong>
                                            {{ $columnsPerRow }} {{ __('columns per row') }} |
                                            <strong>{{ __('Separator:') }}</strong>
                                            @if($separator === ': ')
                                                Colon (Field: Value)
                                            @elseif($separator === ' = ')
                                                Equals (Field = Value)
                                            @elseif($separator === ' - ')
                                                Dash (Field - Value)
                                            @elseif($separator === ' | ')
                                                Pipe (Field | Value)
                                            @else
                                                {{ $separator }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                                        <thead>
                                            <tr style="background-color: #fff3cd;">
                                                <th colspan="{{ $columnsPerRow }}" class="text-center" style="font-weight: bold; color: #2c3e50; font-size: 14px;">
                                                    TEST RESULTS FOR {{ strtoupper($previewTemplate->test_name ?? 'ROUTINE EXAMINATION') }}
                                                </th>
                                            </tr>
                                            <tr style="background-color: #fff3cd;">
                                                <th colspan="{{ $columnsPerRow }}" class="text-center" style="font-weight: bold; color: #2c3e50; font-size: 12px;">
                                                    SPECIMEN: {{ strtoupper($previewTemplate->test_type ?? 'SPECIMEN') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @php
                                                    $fieldChunks = array_chunk($fields, $columnsPerRow);
                                                @endphp
                                                @foreach($fieldChunks as $rowIndex => $row)
                                                <tr style="background-color: {{ $rowIndex % 2 == 0 ? '#fff3cd' : 'white' }};">
                                                    @foreach($row as $fieldIndex => $field)
                                                    <td style="padding: 8px; border: 1px solid #ddd; vertical-align: top;">
                                                        <div class="field-value-pair">
                                                            <div style="font-weight: bold; color: #2c3e50; margin-bottom: 4px;">
                                                                {{ strtoupper($field['label'] ?? 'PARAMETER') }}{{ $separator }}
                                                                <span style="color: #34495e; font-weight: normal;">
                                                                @if(isset($field['options']) && is_array($field['options']) && count($field['options']) > 0)
                                                                    @foreach($field['options'] as $optionIndex => $option)
                                                                        @if($optionIndex > 0), @endif{{ $option }}
                                                                    @endforeach
                                                                @elseif($field['type'] === 'number')
                                                                    @if(isset($field['reference_min']) && isset($field['reference_max']))
                                                                        {{ $field['reference_min'] }}, {{ $field['reference_max'] }}
                                                                    @else
                                                                        Numerical value
                                                                    @endif
                                                                @else
                                                                    <span style="color: #7f8c8d;">_________________</span>
                                                                @endif
                                                                </span>
                                                            </div>
                                                            @if(isset($field['unit']) && $field['unit'])
                                                                <div style="color: #7f8c8d; font-size: 10px; margin-top: 2px;">
                                                                    Unit: {{ $field['unit'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @endforeach
                                                    @for($i = count($row); $i < $columnsPerRow; $i++)
                                                    <td style="padding: 8px; border: 1px solid #ddd; vertical-align: top; background-color: #f8f9fa;">
                                                        <div class="field-value-pair">
                                                            <div style="font-weight: bold; color: #95a5a6; margin-bottom: 4px;">
                                                                EMPTY
                                                            </div>
                                                            <div style="color: #bdc3c7; font-size: 11px;">
                                                                <em>No field configured</em>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endfor
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr style="background-color: #fff3cd;">
                                                    <td colspan="{{ $columnsPerRow }}" class="text-center" style="padding: 20px; color: #7f8c8d;">
                                                        <i class="fas fa-info-circle me-2"></i>{{ __('No fields configured for this template') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <!-- Legacy Form Preview (for backward compatibility) -->
                            <div class="template-preview legacy-template">
                                <div class="alert alert-secondary">
                                    <h6 class="alert-heading"><i class="fas fa-clipboard me-2"></i>{{ __('Legacy Template') }}</h6>
                                    <p class="mb-0">{{ __('Using legacy form configuration') }}</p>
                                </div>

                                @if(count($fields) > 0)
                            <form class="form-preview">
                                @php
                                    $groupedFields = [];
                                            foreach($fields as $field) {
                                        if (is_array($field)) {
                                            $group = $field['group'] ?? 'General';
                                            $groupedFields[$group][] = $field;
                                        }
                                    }
                                @endphp

                                        @foreach($groupedFields as $groupName => $groupFields)
                                <div class="field-group mb-4">
                                    <div class="group-header mb-3">
                                        <h6 class="text-primary fw-bold mb-0">
                                            <i class="fas fa-layer-group me-2"></i>{{ $groupName }}
                                                    <span class="badge bg-primary ms-2">{{ count($groupFields) }}</span>
                                        </h6>
                                        <hr class="my-2">
                                    </div>

                                    <div class="row g-3">
                                                @foreach($groupFields as $index => $field)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="field-card p-3 bg-white rounded shadow-sm border">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <label class="form-label fw-semibold text-dark mb-1">
                                                            {{ $field['label'] ?? 'Field Label' }}
                                                            @if(isset($field['required']) && $field['required'])
                                                                <span class="text-danger ms-1">*</span>
                                                            @endif
                                                        </label>
                                                        <span class="badge bg-info text-white">
                                                            <i class="fas fa-{{ isset($field['type']) && $field['type'] === 'text' ? 'font' : (isset($field['type']) && $field['type'] === 'number' ? 'calculator' : (isset($field['type']) && $field['type'] === 'textarea' ? 'align-left' : 'list')) }} me-1"></i>
                                                            {{ ucfirst($field['type'] ?? 'text') }}
                                                        </span>
                                                    </div>

                                                    @php
                                                        $fieldType = $field['type'] ?? 'text';
                                                        $placeholder = $field['placeholder'] ?? '';
                                                        $unit = $field['unit'] ?? '';
                                                    @endphp

                                                    @if($fieldType === 'text')
                                                        <input type="text" class="form-control form-control-sm" placeholder="{{ $placeholder }}" readonly>
                                                        @if($unit)
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-tag me-1"></i>{{ $unit }}
                                                            </small>
                                                        @endif

                                                    @elseif($fieldType === 'number')
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" class="form-control" placeholder="{{ $placeholder }}" readonly>
                                                            @if($unit)
                                                                <span class="input-group-text bg-light">{{ $unit }}</span>
                                                            @endif
                                                        </div>
                                                        @if(isset($field['reference_min']) || isset($field['reference_max']))
                                                            <div class="reference-range mt-2 p-2 bg-info bg-opacity-10 rounded">
                                                                <small class="text-info fw-semibold">
                                                                    <i class="fas fa-chart-line me-1"></i>
                                                                    Reference Range:
                                                                    <span class="badge bg-info text-white ms-1">
                                                                        {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                                                    </span>
                                                                </small>
                                                            </div>
                                                        @endif

                                                    @elseif($fieldType === 'textarea')
                                                        <textarea class="form-control form-control-sm" rows="2" placeholder="{{ $placeholder }}" readonly></textarea>
                                                        @if($unit)
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-tag me-1"></i>{{ $unit }}
                                                            </small>
                                                        @endif

                                                    @elseif($fieldType === 'dropdown')
                                                        <select class="form-select form-select-sm" disabled>
                                                            <option selected class="text-muted">{{ __('Select an option') }}</option>
                                                            @if(isset($field['options']) && is_array($field['options']))
                                                                @foreach($field['options'] as $option)
                                                                    <option>{{ $option }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if($unit)
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-tag me-1"></i>{{ $unit }}
                                                            </small>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </form>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">{{ __('No Form Fields') }}</h5>
                                    <p class="text-muted">{{ __('This template has no form fields configured yet.') }}</p>
                                </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closePreviewModal">
                    <i class="fas fa-times me-1"></i>{{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    // Listen for group added event
    window.addEventListener('group-added', event => {
        console.log('Group added:', event.detail.group);
        // Force Livewire to re-render
        if (typeof Livewire !== 'undefined') {
            Livewire.rescan();
        }
    });

    // Function to edit group names
    function editGroupName(index, currentName) {
        const newName = prompt('Edit group name:', currentName);
        if (newName && newName.trim() !== '' && newName !== currentName) {
            // Call Livewire method to update group name
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('editGroup', index, newName);
        }
    }

    // Listen for field group updates
    window.addEventListener('field-group-updated', event => {
        console.log('Field group updated');
        // Force Livewire to re-render
        if (typeof Livewire !== 'undefined') {
            Livewire.rescan();
        }
    });

    // Prevent automatic form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent form submission on Enter key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        });

        // Prevent form submission on form submit event
        const forms = document.querySelectorAll('form[wire\\:submit\\.prevent]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Only allow submission if it's triggered by the actual submit button
                if (!e.submitter || !e.submitter.matches('button[type="submit"]')) {
                    e.preventDefault();
                    console.log('Form submission prevented - not triggered by submit button');
                    return false;
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('livewire:load', function () {
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-in-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 3000);
    });
</script>

<script>
    // Handle dynamic form behavior
    document.addEventListener('livewire:load', function () {
        // Handle table type changes
        Livewire.on('tableTypeChanged', function (data) {
            console.log('Table type changed:', data);
        });

        // Handle layout type changes
        Livewire.on('layoutTypeChanged', function (data) {
            console.log('Layout type changed:', data);
        });

        // Handle species dependent field configuration
        function updateSpeciesFieldConfig(fieldIndex, optionType) {
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                .call('addSpeciesOption', fieldIndex, optionType);
        }

        // Make function globally available
        window.updateSpeciesFieldConfig = updateSpeciesFieldConfig;
    });

    // Handle form field type changes
    function handleFieldTypeChange(fieldIndex, newType) {
        if (newType === 'species_dependent') {
            // Auto-populate species configuration
            setTimeout(() => {
                const resultsInput = document.querySelector(`[wire\\:model="form_fields.${fieldIndex}.species_config.results"]`);
                const speciesInput = document.querySelector(`[wire\\:model="form_fields.${fieldIndex}.species_config.species"]`);
                const stagesInput = document.querySelector(`[wire\\:model="form_fields.${fieldIndex}.species_config.stages"]`);
                const countsInput = document.querySelector(`[wire\\:model="form_fields.${fieldIndex}.species_config.counts"]`);
                const unitsInput = document.querySelector(`[wire\\:model="form_fields.${fieldIndex}.species_config.units"]`);

                if (resultsInput && !resultsInput.value) {
                    resultsInput.value = 'Positive, Negative, Trace';
                    resultsInput.dispatchEvent(new Event('input'));
                }
                if (speciesInput && !speciesInput.value) {
                    speciesInput.value = 'E. coli, Salmonella, Shigella';
                    speciesInput.dispatchEvent(new Event('input'));
                }
                if (stagesInput && !stagesInput.value) {
                    stagesInput.value = 'Early, Mid, Late';
                    stagesInput.dispatchEvent(new Event('input'));
                }
                if (countsInput && !countsInput.value) {
                    countsInput.value = '1-10, 11-50, 51-100';
                    countsInput.dispatchEvent(new Event('input'));
                }
                if (unitsInput && !unitsInput.value) {
                    unitsInput.value = 'cells/mL, CFU/mL, %';
                    unitsInput.dispatchEvent(new Event('input'));
                }
            }, 100);
        }
    }

    // Make function globally available
    window.handleFieldTypeChange = handleFieldTypeChange;
</script>
