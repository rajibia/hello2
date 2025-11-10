<div>
    <!-- Success Messages -->
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Radiology Test Templates</h4>
        <a href="{{ route('radiology.test.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Template
        </a>
                </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search by template name, category, or type...">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Template Name</th>
                            <th>Short Name</th>
                            <th>Test Type</th>
                            <th>Category</th>
                            <th>Template Type</th>
                            <th>Fields</th>
                            <th>Charge</th>
                            <th>Report Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($template->icon_class)
                                        <i class="{{ $template->icon_class }} me-2" style="color: {{ $template->icon_color }}"></i>
                                    @else
                                        <i class="fas fa-x-ray me-2 text-primary"></i>
                                        @endif
                                    <div>
                                        <div class="fw-semibold">{{ $template->test_name }}</div>
                                        <small class="text-muted">{{ $template->short_name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $template->short_name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $template->test_type }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <i class="fas fa-folder fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $template->radiologycategory->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($template->is_dynamic_form && $template->form_configuration)
                                    @php
                                        $tableType = $template->form_configuration['table_type'] ?? 'standard';
                                        $templateType = '';
                                        $templateTypeClass = 'bg-secondary';

                                        switch($tableType) {
                                            case 'standard':
                                                $templateType = 'Standard';
                                                $templateTypeClass = 'bg-primary';
                                                break;
                                            case 'simple':
                                                $templateType = 'Simple';
                                                $templateTypeClass = 'bg-success';
                                                break;
                                            case 'specimen':
                                                $templateType = 'Specimen';
                                                $templateTypeClass = 'bg-warning';
                                                break;
                                            case 'species_dependent':
                                                $templateType = 'Species';
                                                $templateTypeClass = 'bg-info';
                                                break;
                                            case 'field_value_multi':
                                                $templateType = 'Field-Value';
                                                $templateTypeClass = 'bg-secondary';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $templateTypeClass }}">{{ $templateType }}</span>
                                @else
                                    <span class="badge bg-secondary">Static</span>
                                @endif
                                </td>
                            <td>
                                @if($template->is_dynamic_form && $template->form_configuration)
                                    @php
                                        $formConfig = is_array($template->form_configuration) ? $template->form_configuration : [];
                                        // Try different possible structures
                                        if (isset($formConfig['fields']) && is_array($formConfig['fields'])) {
                                            $fieldCount = count($formConfig['fields']);
                                        } elseif (is_array($formConfig) && !isset($formConfig['table_type'])) {
                                            // If form_configuration is directly an array of fields
                                            $fieldCount = count($formConfig);
                                        } else {
                                            $fieldCount = 0;
                                        }
                                    @endphp
                                    <span class="badge bg-info text-dark">
                                        {{ $fieldCount }} Fields
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-primary">{{ number_format($template->standard_charge, 2) }}</span>
                            </td>
                            <td>
                                @if($template->report_days)
                                    <span class="fw-semibold">{{ $template->report_days }} days</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                                </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Preview Button -->
                                    <button wire:click="preview({{ $template->id }})" title="Preview Template" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <a href="{{ route('radiology.test.template.edit', $template->id) }}" title="Edit Template" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <button wire:click="delete({{ $template->id }})" title="Delete Template"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this template?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Radiology Templates Found</h5>
                                    <p class="text-muted">No radiology test templates have been created yet.</p>
                                    <a href="{{ route('radiology.test.templates.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Template
                                    </a>
                                </div>
                            </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($templates->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $templates->links('vendor.pagination.bootstrap-4') }}
                </div>
                            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    @if($loading)
        <div class="position-fixed top-50 start-50 translate-middle">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
        </div>
    </div>
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
                                <p><strong>{{ __('Category') }}:</strong> {{ $previewTemplate->radiologycategory->name ?? 'N/A' }}</p>
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

                            <!-- Field-Value Multi Configuration -->
                            @if($tableType === 'field_value_multi' && isset($formConfig['field_value_config']))
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="text-primary mb-2"><i class="fas fa-columns me-2"></i>{{ __('Field-Value Configuration') }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Columns') }}:</strong> {{ $formConfig['field_value_config']['columns'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Separator') }}:</strong> {{ $formConfig['field_value_config']['separator'] ?? 'N/A' }}</p>
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
                            <i class="fas fa-clipboard-list me-2"></i>{{ __('Form Preview') }}
                                <span class="badge bg-light text-primary ms-2">{{ $fieldCount }} {{ __('Fields') }}</span>
                        </h6>
                    </div>
                    <div class="card-body bg-light">
                            @if($fieldCount > 0)
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

                                @foreach($groupedFields as $groupName => $fields)
                                <div class="field-group mb-4">
                                    <div class="group-header mb-3">
                                        <h6 class="text-primary fw-bold mb-0">
                                            <i class="fas fa-layer-group me-2"></i>{{ $groupName }}
                                            <span class="badge bg-primary ms-2">{{ count($fields) }}</span>
                                        </h6>
                                        <hr class="my-2">
                                    </div>

                                    <div class="row g-3">
                                        @foreach($fields as $index => $field)
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
</div>
