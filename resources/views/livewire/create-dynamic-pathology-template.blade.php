<div>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>{{ __('Create Dynamic Pathology Template') }}
        </h4>
        <a href="{{ route('pathology.test.template.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>{{ __('Back to Templates') }}
        </a>
    </div>

    <form id="createTemplateForm">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>{{ __('Basic Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Template Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="test_name" wire:keydown.enter.prevent placeholder="Enter template name">
                            @error('test_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Short Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="short_name" wire:keydown.enter.prevent placeholder="Enter short name">
                            @error('short_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Test Type') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="test_type" wire:keydown.enter.prevent placeholder="Enter test type">
                            @error('test_type') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Category') }} <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="category_id">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Charge Category') }} <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="charge_category_id">
                                <option value="">{{ __('Select Charge Category') }}</option>
                                @foreach($chargeCategories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('charge_category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Charge') }} <span class="text-danger">*</span>
                                @if($charge_category_id)
                                    <small class="text-info ms-1">
                                        <i class="fas fa-magic me-1"></i>{{ __('Auto-filled from category') }}
                                    </small>
                                @endif
                            </label>
                            <input type="number" step="0.01" class="form-control" wire:model="standard_charge" wire:keydown.enter.prevent placeholder="Enter charge" readonly>
                            <small class="text-muted">{{ __('Charge amount is automatically set from the selected charge category') }}</small>
                            @error('standard_charge') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Report Days') }}</label>
                            <input type="number" min="0" max="365" class="form-control" wire:model="report_days" placeholder="Enter number of days for report delivery">
                            <small class="text-muted">{{ __('Number of days required to deliver the test report') }}</small>
                            @error('report_days') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Icon Class') }}</label>
                            <input type="text" class="form-control" wire:model="icon_class" placeholder="fas fa-flask">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Configuration -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>{{ __('Template Configuration') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Table Header Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="table_type">
                                @foreach($this->getTableTypes() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Select the type of table headers for your test results') }}</small>
                        </div>
                    </div>
                    @if($table_type !== 'field_value_multi')
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Field Layout') }}</label>
                            <select class="form-select" wire:model="layout_type">
                                @foreach($this->getLayoutTypes() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Choose how fields are displayed in the results') }}</small>
                        </div>
                    </div>
                    @endif
                </div>

                @if($table_type !== 'field_value_multi')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Columns per Row') }}</label>
                            <select class="form-select" wire:model="columns_per_row" {{ $layout_type === 'single_row' ? 'disabled' : '' }}>
                                <option value="2">2 Columns</option>
                                <option value="3">3 Columns</option>
                                <option value="4">4 Columns</option>
                            </select>
                            <small class="text-muted">{{ __('Number of fields per row (only for multi-column layout)') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Specimen Name') }}</label>
                            <input type="text" class="form-control" wire:model="specimen_name" placeholder="e.g., Blood, Urine, Stool" {{ !in_array($table_type, ['specimen', 'species_dependent']) ? 'disabled' : '' }} readonly>
                            <small class="text-muted">{{ __('Auto-populated from Test Type. Edit Test Type to change specimen.') }}</small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Species Dependent Configuration -->
                @if($table_type === 'species_dependent')
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('Species Dependent Configuration') }}</h6>
                            <p class="mb-2"><strong>{{ __('Dependency Chain:') }}</strong></p>
                            <ul class="mb-2">
                                <li><strong>RESULTS</strong> → <strong>SPECIES</strong> (shows N/A or dependency options)</li>
                                <li><strong>SPECIES</strong> → <strong>STAGE</strong> (shows N/A or dependency options)</li>
                                <li><strong>COUNT</strong> is a regular input field</li>
                                <li><strong>UNIT</strong> is a regular dropdown</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Results Options (Main dropdown) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Results Options') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="species_config.results" placeholder="e.g., res 1, res 2" wire:keydown.enter.prevent>
                            </div>
                            <small class="text-muted">{{ __('Main RESULTS dropdown options (comma separated)') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Unit Options') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="species_config.units" placeholder="e.g., cells/mL, CFU/mL, %" wire:keydown.enter.prevent>
                            </div>
                            <small class="text-muted">{{ __('UNIT dropdown options (comma separated)') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Species Dependencies -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Species Dependencies') }}</label>
                            <div class="border rounded p-3 bg-light">
                                @if(isset($species_config['results']) && !empty($species_config['results']))
                                    @php
                                        $resultsArray = array_map('trim', explode(',', $species_config['results']));
                                    @endphp
                                    @foreach($resultsArray as $resultIndex => $result)
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-primary">{{ __('If RESULTS = "') }}{{ $result }}{{ __('" then SPECIES shows:') }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $result }}" readonly style="background-color: #f8f9fa;">
                                                <input type="text" class="form-control" wire:model="species_config.species_dependencies.{{ $result }}" placeholder="e.g., N/A, E. coli, Salmonella (comma separated)" wire:keydown.enter.prevent>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted">{{ __('Add Results Options first to configure dependencies') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage Dependencies -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Stage Dependencies') }}</label>
                            <div class="border rounded p-3 bg-light">
                                @if(isset($species_config['species_dependencies']) && is_array($species_config['species_dependencies']) && count($species_config['species_dependencies']) > 0)
                                    @foreach($species_config['species_dependencies'] as $result => $speciesOptions)
                                        @if(!empty($speciesOptions))
                                            @php
                                                $speciesArray = array_map('trim', explode(',', $speciesOptions));
                                            @endphp
                                            @foreach($speciesArray as $species)
                                                @if($species !== 'N/A')
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-success">{{ __('If SPECIES = "') }}{{ $species }}{{ __('" then STAGE shows:') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="{{ $species }}" readonly style="background-color: #f8f9fa;">
                                                        <input type="text" class="form-control" wire:model="species_config.stage_dependencies.{{ $species }}" placeholder="e.g., Early, Mid, Late (comma separated)" wire:keydown.enter.prevent>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-muted">{{ __('Configure Species Dependencies first') }}</div>
                                    <!-- Debug: species_dependencies count: {{ isset($species_config['species_dependencies']) ? count($species_config['species_dependencies']) : 'not set' }} -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Specimen Field:Value Configuration -->
                @if($table_type === 'specimen')
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>{{ __('Specimen Template Configuration') }}</h6>
                            <p class="mb-2"><strong>{{ __('Standard Table Format:') }}</strong></p>
                            <ul class="mb-2">
                                <li>{{ __('This template uses a standard table format with fixed columns') }}</li>
                                <li>{{ __('Columns: SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT') }}</li>
                                <li>{{ __('Perfect for specimen-specific test results') }}</li>
                                <li>{{ __('No field:value configuration needed') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Field-Value Multi-Column Configuration -->
                @if($table_type === 'field_value_multi')
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>{{ __('Field-Value Multi-Column Configuration') }}</h6>
                            <p class="mb-2"><strong>{{ __('Field:Value Format with Multiple Columns:') }}</strong></p>
                            <ul class="mb-2">
                                <li>{{ __('This template displays results in field:value format') }}</li>
                                <li>{{ __('Multiple fields per row for compact display') }}</li>
                                <li>{{ __('Perfect for routine examinations (Urine, Stool, etc.)') }}</li>
                                <li>{{ __('Single header with multiple parameter columns') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Field-Value Layout Configuration -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Columns per Row') }}</label>
                            <select class="form-select" wire:model="field_value_columns">
                                <option value="1">1 Column per Row</option>
                                <option value="2">2 Columns per Row</option>
                                <option value="3">3 Columns per Row</option>
                                <option value="4">4 Columns per Row</option>
                                <option value="5">5 Columns per Row</option>
                            </select>
                            <small class="text-muted">{{ __('How many field:value pairs to display per row') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Field Separator') }}</label>
                            <select class="form-select" wire:model="field_value_separator">
                                <option value=": ">Colon (Field: Value)</option>
                                <option value=" = ">Equals (Field = Value)</option>
                                <option value=" - ">Dash (Field - Value)</option>
                                <option value=" | ">Pipe (Field | Value)</option>
                            </select>
                            <small class="text-muted">{{ __('Separator between field name and value') }}</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Dynamic Form Builder -->
        @if(!in_array($table_type, ['species_dependent']))
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>{{ __('Form Builder') }}</h6>
            </div>
            <div class="card-body">
                <!-- Group Management -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" wire:model.defer="current_group" wire:keydown.enter.prevent="addGroup" placeholder="Enter field group name">
                            <button type="button" class="btn btn-outline-primary" wire:click.prevent="addGroup">
                                <i class="fas fa-plus me-1"></i>{{ __('Add Group') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success" wire:click="addField">
                                <i class="fas fa-plus me-1"></i>{{ __('Add Field') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Available Groups -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">{{ __('Available Groups') }}:</label>
                    <div class="p-3 border rounded bg-light">
                        @if(count($field_groups) > 0)
                            @foreach($field_groups as $index => $group)
                                <span class="badge bg-primary text-white me-2 mb-2 d-inline-flex align-items-center" style="font-size:12px; padding:6px 12px; border-radius:15px; cursor:pointer;" onclick="editGroupName({{ $index }}, '{{ $group }}')" title="Click to edit">
                                    {{ $group }}
                                    <button type="button" class="btn btn-sm btn-outline-light ms-2" style="padding:0; width:16px; height:16px; font-size:8px; border-radius:50%;" onclick="event.stopPropagation(); Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('removeGroup', {{ $index }})" title="Remove group">×</button>
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted small">No groups added yet. Add a group to organize your fields.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Species Dependent Auto-Generated Fields Info -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-magic me-2"></i>{{ __('Auto-Generated Fields') }}</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>{{ __('Species Dependent Template') }}</h6>
                    <p class="mb-2">{{ __('This template will automatically generate the following fields:') }}</p>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-center p-2 border rounded bg-light">
                                <strong class="text-primary">RESULTS</strong>
                                <br><small class="text-muted">Dropdown</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center p-2 border rounded bg-light">
                                <strong class="text-primary">SPECIES</strong>
                                <br><small class="text-muted">Dependent Dropdown</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center p-2 border rounded bg-light">
                                <strong class="text-primary">STAGE</strong>
                                <br><small class="text-muted">Dependent Dropdown</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center p-2 border rounded bg-light">
                                <strong class="text-primary">COUNT</strong>
                                <br><small class="text-muted">Input Field</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center p-2 border rounded bg-light">
                                <strong class="text-primary">UNIT</strong>
                                <br><small class="text-muted">Dropdown</small>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0 mt-3"><strong>{{ __('Note:') }}</strong> {{ __('No manual field addition is required. Configure the dependencies above and the fields will be automatically generated.') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Fields -->
        @if(!in_array($table_type, ['species_dependent']) && count($form_fields) > 0)
        <div class="form-fields-container">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary"><i class="fas fa-list me-2"></i>{{ __('Form Fields') }} ({{ count($form_fields) }})</h6>
                </div>
                <div class="card-body p-0">
                    @foreach($form_fields as $index => $field)
                    <div class="border-bottom p-4 {{ $index === count($form_fields) - 1 ? '' : 'border-bottom' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 me-3 fw-bold text-primary">{{ __('Field') }} #{{ $index + 1 }}</h6>
                                <span class="badge bg-primary text-white" style="font-size:11px; padding:5px 10px; border-radius:12px;">{{ $field['group'] ?? 'General' }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeField({{ $index }})" title="Delete Field">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Field Name') }}</label>
                                    <input type="text" class="form-control @error('form_fields.'.$index.'.name') is-invalid @enderror" wire:model="form_fields.{{ $index }}.name" placeholder="e.g., patient_name" wire:keydown.enter.prevent>
                                    @error('form_fields.'.$index.'.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Field Label') }}</label>
                                    <input type="text" class="form-control @error('form_fields.'.$index.'.label') is-invalid @enderror" wire:model="form_fields.{{ $index }}.label" placeholder="e.g., Patient Name" wire:keydown.enter.prevent>
                                    @error('form_fields.'.$index.'.label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Field Type') }}</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.type" onchange="handleFieldTypeChange({{ $index }}, this.value)">
                                        @foreach($this->getFieldTypes() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Field Group') }}</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.group">
                                        <option value="General">General</option>
                                        @foreach($field_groups as $group)
                                            <option value="{{ $group }}">{{ $group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Required') }}</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.required">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Unit and Placeholder -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Placeholder') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.placeholder" placeholder="Enter placeholder text" wire:keydown.enter.prevent>
                                </div>
                            </div>
                            @if($table_type !== 'simple')
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Unit') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.unit" placeholder="e.g., mg/dL" wire:keydown.enter.prevent>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Reference Range for Number Fields -->
                        @php
                            $fieldType = isset($field['type']) ? (is_string($field['type']) ? $field['type'] : 'text') : 'text';
                            $isNumberField = $fieldType === 'number';
                        @endphp
                        @if($isNumberField)
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Ref. Min') }}</label>
                                    <input type="number" step="any" class="form-control" wire:model="form_fields.{{ $index }}.reference_min" placeholder="e.g., 4.5" wire:keydown.enter.prevent>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Ref. Max') }}</label>
                                    <input type="number" step="any" class="form-control" wire:model="form_fields.{{ $index }}.reference_max" placeholder="e.g., 11.0" wire:keydown.enter.prevent>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">Validation</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.validation">
                                        <option value="">{{ __('None') }}</option>
                                        @foreach($this->getValidationRules() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Options for dropdown, radio, checkbox -->
                        @php
                            $fieldType = isset($field['type']) ? (is_string($field['type']) ? $field['type'] : 'text') : 'text';
                            $showOptions = in_array($fieldType, ['dropdown', 'radio', 'checkbox']);
                        @endphp
                        @if($showOptions)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Options') }}</label>
                                    <div class="options-container">
                                        @if(isset($field['options']) && is_array($field['options']) && count($field['options']) > 0)
                                            @for($i = 0; $i < count($field['options']); $i += 2)
                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.options.{{ $i }}" placeholder="Option {{ $i + 1 }}">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-danger" wire:click="removeOption({{ $index }}, {{ $i }})">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(isset($field['options'][$i + 1]))
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.options.{{ $i + 1 }}" placeholder="Option {{ $i + 2 }}">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-outline-danger" wire:click="removeOption({{ $index }}, {{ $i + 1 }})">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endfor
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addOption({{ $index }})">
                                            <i class="fas fa-plus"></i> {{ __('Add Option') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- File Upload Configuration for File Fields -->
                        @php
                            $fieldType = isset($field['type']) ? (is_string($field['type']) ? $field['type'] : 'text') : 'text';
                            $isFileField = $fieldType === 'file';
                        @endphp
                        @if($isFileField)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('File Upload Field Configuration') }}</label>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>{{ __('File Upload Field') }}</strong>
                                        <p class="mb-1">{{ __('This field will allow users to upload files when filling out the test form.') }}</p>
                                        <ul class="mb-0">
                                            <li>{{ __('Accepted formats: PDF, JPG, JPEG, PNG, DOC, DOCX') }}</li>
                                            <li>{{ __('Maximum file size: 10MB') }}</li>
                                            <li>{{ __('Files will be stored securely') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Layout Configuration (for multi-column) -->
                        @if($layout_type === 'multi_column')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Column Position') }}</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.column_position">
                                        @for($i = 1; $i <= $columns_per_row; $i++)
                                            <option value="{{ $i }}">Column {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">{{ __('Position in the row (1-') }}{{ $columns_per_row }}{{ __(')') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold text-muted small">{{ __('Row Group') }}</label>
                                    <input type="number" class="form-control" wire:model="form_fields.{{ $index }}.row_group" min="1" placeholder="1">
                                    <small class="text-muted">{{ __('Group fields into the same row') }}</small>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @elseif(!in_array($table_type, ['species_dependent']))
        <div class="text-center py-4">
            <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('No fields added yet. Click "Add Field" to start building your form.') }}</p>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('No fields added yet. Click "Add Field" to start building your form.') }}</p>
        </div>
        @endif
    </form>

    <div class="card-footer bg-light border-top">
        @if(!in_array($table_type, ['species_dependent']) && count($form_fields) === 0)
            <div class="text-warning me-auto">
                <i class="fas fa-exclamation-triangle me-1"></i>{{ __('Please add at least one field before creating the template.') }}
            </div>
        @endif



        <!-- Validation Errors Display -->
        @if($errors->any())
            <div class="text-danger me-auto">
                <i class="fas fa-exclamation-circle me-1"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between">
            <a href="{{ route('pathology.test.template.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
            </a>
            <div>
                <button type="button" class="btn btn-primary" wire:click="store">
                    <i class="fas fa-save me-1"></i>{{ __('Create Template') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.laboratory-report-preview {
    font-size: 12px;
    line-height: 1.4;
}

.laboratory-report-preview table {
    font-size: 11px;
}

.laboratory-report-preview th,
.laboratory-report-preview td {
    padding: 8px 12px;
    vertical-align: middle;
}

/* Modal visibility fixes */
.modal.show {
    display: block !important;
    z-index: 9999 !important;
}

.modal-backdrop.show {
    z-index: 9998 !important;
}

@media print {
    .modal-header,
    .modal-footer,
    .btn {
        display: none !important;
    }

    .modal-dialog {
        max-width: 100% !important;
        margin: 0 !important;
    }

    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }

    .laboratory-report-preview {
        padding: 0 !important;
    }
}
</style>
