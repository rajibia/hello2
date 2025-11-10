<div>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-edit me-2"></i>{{ __('Edit Dynamic Pathology Template') }}
        </h4>
        <a href="{{ route('pathology.test.template.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>{{ __('Back to Templates') }}
        </a>
    </div>

    <form wire:submit.prevent="update">
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
                            <small class="text-muted">{{ __('FontAwesome icon class for the template') }}</small>
                            @error('icon_class') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Configuration -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary"><i class="fas fa-cogs me-2"></i>{{ __('Template Configuration') }} <span class="text-muted">(Read Only)</span></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Table Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="table_type" disabled>
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="standard">Standard (ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT)</option>
                                <option value="simple">Simple (ANALYTE, RESULTS)</option>
                                <option value="specimen">Specimen (SPECIMEN, RESULTS, REFERENCE RANGE, FLAG, UNIT)</option>
                                <option value="species_dependent">Species Dependent (RESULTS, SPECIES, STAGE, COUNT, UNIT)</option>
                                <option value="field_value_multi">Field-Value Multi-Column</option>
                            </select>
                            <small class="text-muted">{{ __('Template type cannot be changed after creation') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">{{ __('Layout Type') }}</label>
                            <select class="form-select" wire:model="layout_type" disabled>
                                <option value="single_row">Single Field per Row</option>
                                <option value="two_columns">Two Columns per Row</option>
                                <option value="three_columns">Three Columns per Row</option>
                                <option value="four_columns">Four Columns per Row</option>
                            </select>
                            <small class="text-muted">{{ __('Layout type cannot be changed after creation') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Field Value Multi-Column Configuration -->
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

        <!-- Species Dependent Configuration -->
        @if($table_type === 'species_dependent')
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-magic me-2"></i>{{ __('Species Dependent Configuration') }}</h6>
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
                    <p class="mb-0 mt-3"><strong>{{ __('Note:') }}</strong> {{ __('No manual field addition is required. Configure the dependencies below and the fields will be automatically generated.') }}</p>
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Fields Section -->
        @if($table_type !== 'species_dependent')
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary"><i class="fas fa-list me-2"></i>{{ __('Form Fields') }}</h6>
                <button type="button" class="btn btn-sm btn-primary" wire:click="addField">
                    <i class="fas fa-plus"></i> {{ __('Add Field') }}
                </button>
            </div>
            <div class="card-body">
                @if(isset($form_fields) && is_array($form_fields) && count($form_fields) > 0)
                    @foreach($form_fields as $index => $field)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">{{ __('Field') }} #{{ $index + 1 }}</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeField({{ $index }})">
                                <i class="fas fa-trash"></i> {{ __('Remove') }}
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Field Name') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.name" placeholder="e.g., glucose_level">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Field Label') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.label" placeholder="e.g., Glucose Level">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Field Type') }}</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.type">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="textarea">Text Area</option>
                                        <option value="file">File Upload</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Unit') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.unit" placeholder="e.g., mg/dL">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Placeholder') }}</label>
                                    <input type="text" class="form-control" wire:model="form_fields.{{ $index }}.placeholder" placeholder="e.g., Enter glucose level">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Validation</label>
                                    <select class="form-select" wire:model="form_fields.{{ $index }}.validation">
                                        <option value="">{{ __('None') }}</option>
                                        <option value="required">{{ __('Required') }}</option>
                                        <option value="email">{{ __('Email') }}</option>
                                        <option value="numeric">{{ __('Numeric') }}</option>
                                        <option value="min:1">{{ __('Minimum 1') }}</option>
                                        <option value="max:100">{{ __('Maximum 100') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Reference Range for Number Fields -->
                        @if($field['type'] === 'number')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Reference Min') }}</label>
                                    <input type="number" step="0.01" class="form-control" wire:model="form_fields.{{ $index }}.reference_min" placeholder="e.g., 70">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Reference Max') }}</label>
                                    <input type="number" step="0.01" class="form-control" wire:model="form_fields.{{ $index }}.reference_max" placeholder="e.g., 100">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Options for dropdown fields -->
                        @if(in_array($field['type'], ['dropdown', 'radio', 'checkbox']))
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">{{ __('Options') }}</label>
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
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        {{ __('No fields added yet. Click the "Add Field" button to start.') }}
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Submit Button -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('pathology.test.template.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Cancel') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('Update Template') }}
            </button>
        </div>
    </form>
</div>
