<div>
    <!-- Error Messages -->
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Success Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="store">
        <div class="row g-3">
            <!-- Patient and Case Information -->
            <div class="row">
                @if ($patient_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                        <select wire:model="patient_id" class="form-select" {{ $patient_id != '' ? 'disabled' : '' }} required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $id => $name)
                                <option value="{{ $id }}" {{ $patient_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($patient_id != '')
                            <input type="hidden" wire:model="patient_id" value="{{ $patient_id }}">
                        @endif
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                        <select wire:model="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($opd_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.opd_patient.opd_number') }}</label>
                        <select wire:model="opd_id" class="form-select" {{ $opd_id != '' ? 'disabled' : '' }}>
                            <option value="">Select OPD</option>
                            @foreach($opds as $id => $number)
                                <option value="{{ $id }}" {{ $opd_id == $id ? 'selected' : '' }}>{{ $number }}</option>
                            @endforeach
                        </select>
                        @if($opd_id != '')
                            <input type="hidden" wire:model="opd_id" value="{{ $opd_id }}">
                        @endif
                    </div>
                @endif

                @if ($ipd_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.ipd_patient.ipd_number') }}</label>
                        <select wire:model="ipd_id" class="form-select" {{ $ipd_id != '' ? 'disabled' : '' }}>
                            <option value="">Select IPD</option>
                            @foreach($ipds as $id => $number)
                                <option value="{{ $id }}" {{ $ipd_id == $id ? 'selected' : '' }}>{{ $number }}</option>
                            @endforeach
                        </select>
                        @if($ipd_id != '')
                            <input type="hidden" wire:model="ipd_id" value="{{ $ipd_id }}">
                        @endif
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('messages.ipd_patient.case_id') }} <span class="text-danger">*</span></label>
                    <select wire:model="case_id" class="form-select" {{ $case_id != '' ? 'disabled' : '' }} required>
                        <option value="">Select Case</option>
                        @foreach($caseIds as $id => $caseId)
                            <option value="{{ $id }}" {{ $case_id == $id ? 'selected' : '' }}>{{ $caseId }}</option>
                        @endforeach
                    </select>
                    @if($case_id != '')
                        <input type="hidden" wire:model="case_id" value="{{ $case_id }}">
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">REFERRAL DOCTOR <span class="text-danger">*</span></label>
                    <select wire:model="doctor_id" class="form-select" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="row">
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea wire:model="note" class="form-control" rows="3" placeholder="Enter any additional notes..."></textarea>
                </div>
            </div>

            <!-- Multiple Tests Section -->
            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor'))
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="fas fa-list me-2"></i>Test Requests
                            </h6>
                            <button type="button" class="btn btn-success btn-sm" wire:click="addTest">
                                <i class="fas fa-plus me-1"></i>Add Test
                            </button>
                        </div>

                        <div class="card border">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%">Test Name <span class="text-danger">*</span></th>
                                                <th style="width: 10%">Template Type</th>
                                                <th style="width: 15%">Report Days</th>
                                                <th style="width: 20%">Report Date <span class="text-danger">*</span></th>
                                                <th style="width: 15%">Amount (GHS)</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-muted small bg-light">
                                                    <i class="fas fa-info-circle me-1"></i>Select from available dynamic templates. Template type indicates the format of the test results.
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selected_tests as $index => $test)
                                                <tr>
                                                    <td>
                                                        @if(count($templatesForSelect) > 0)
                                                            <select wire:model="selected_tests.{{ $index }}.template_id" class="form-select" required>
                                                                <option value="">Select Template</option>
                                                                @foreach($templatesForSelect as $id => $name)
                                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select class="form-select" disabled>
                                                                <option value="">No templates available</option>
                                                            </select>
                                                            <small class="text-danger">No pathology test templates are available. Please create templates first.</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $test['template_type'] ?: '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <input type="text" wire:model="selected_tests.{{ $index }}.report_days" class="form-control" placeholder="Report Days" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="date" wire:model="selected_tests.{{ $index }}.report_date" class="form-control" required min="{{ date('Y-m-d') }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" wire:model="selected_tests.{{ $index }}.amount" class="form-control" placeholder="Amount" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        @if(count($selected_tests) > 1)
                                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeTest({{ $index }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Permission Required:</strong> Only Administrators and Doctors can create pathology test requests. Please contact your administrator if you need access.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pathology.test.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>{{ __('messages.common.cancel') }}
                        </a>
                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor'))
                            @if(count($templatesForSelect) > 0)
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="store">
                                    <span wire:loading.remove wire:target="store">
                                        <i class="fas fa-save me-2"></i>{{ __('messages.common.save') }}
                                    </span>
                                    <span wire:loading wire:target="store">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Creating...
                                    </span>
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" disabled title="No templates available">
                                    <i class="fas fa-save me-2"></i>{{ __('messages.common.save') }}
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-primary" disabled title="Permission required">
                                <i class="fas fa-save me-2"></i>{{ __('messages.common.save') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Loading Overlay -->
    <div wire:loading wire:target="store" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
