<div class="row g-3">
    <!-- Patient and Case Information -->
    <div class="row">
        @if ($patient_id != '')
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select patient_name', ($patient_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                <input type="hidden" name="patient_id" value="{{ $patient_id }}">
            </div>
        @else
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                {{ Form::select('patient_id', $patients, null, ['class' => 'form-select patient_name', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div>
        @endif

        @if ($opd_id != '')
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('messages.opd_patient.opd_number') }}</label>
                {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', ($opd_id != '' ? 'disabled' : ''), 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                <input type="hidden" name="opd_id" value="{{ $opd_id }}">
                <input type="hidden" name="create_from_route" value="opd">
            </div>
        @endif

        @if ($ipd_id != '')
            <div class="col-md-6">
                <label class="form-label fw-semibold">{{ __('messages.ipd_patient.ipd_number') }}</label>
                {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
                <input type="hidden" name="create_from_route" value="ipd">
            </div>
        @endif

        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('messages.ipd_patient.case_id') }} <span class="text-danger">*</span></label>
            {{ Form::select('case_id', $caseIds, $case_id, ['class' => 'form-select case_id', ($case_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsCASEID', 'placeholder' => __('messages.ipd_patient.case_id')]) }}
            @if($case_id != '')
                <input type="hidden" name="case_id" value="{{ $case_id }}">
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">REFERRAL DOCTOR <span class="text-danger">*</span></label>
            @if(Auth::user()->hasRole('Doctor'))
                @php
                    $currentDoctorId = Auth::user()->doctor->id ?? null;
                    $currentDoctorName = Auth::user()->doctor->doctorUser->full_name ?? Auth::user()->name ?? 'Unknown Doctor';
                @endphp
                {{ Form::select('doctor_id', $doctors, $currentDoctorId, ['class' => 'form-select', 'required', 'id' => 'doctor_id', 'readonly', 'style' => 'background-color: #f8f9fa;']) }}
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Auto-selected: {{ $currentDoctorName }}</small>
            @else
            {{ Form::select('doctor_id', $doctors, null, ['class' => 'form-select', 'required', 'id' => 'doctor_id', 'placeholder' => 'Select Doctor']) }}
            @endif
        </div>
    </div>

    <!-- Notes Section -->
    <div class="row">
        <div class="col-12">
            <label class="form-label fw-semibold">Notes</label>
            {{ Form::textarea('note', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter any additional notes...']) }}
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
                    <button type="button" class="btn btn-success btn-sm add-parameter-test-billing">
                        <i class="fas fa-plus me-1"></i>Add Test
                    </button>
                </div>

                <div class="card border">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%">Test Name <span class="text-danger">*</span></th>
                                        <th style="width: 20%">Report Days</th>
                                        <th style="width: 25%">Report Date <span class="text-danger">*</span></th>
                                        <th style="width: 15%">Amount (GHS)</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-muted small bg-light">
                                            <i class="fas fa-info-circle me-1"></i>Select from available dynamic templates. Report date will be automatically calculated based on report days.
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="pathology-test-container">
                                    <tr>
                                        <td>
                                            @if(count($templatesForSelect) > 0)
                                                {{ Form::select('template_id[]', $templatesForSelect, null, ['class' => 'form-select template-select', 'required', 'id' => 'template_id_1', 'placeholder' => __('messages.pathology_test.select_test_name')]) }}
                                            @else
                                                <select name="template_id[]" class="form-select template-select" required disabled>
                                                    <option value="">No templates available</option>
                                                </select>
                                                <small class="text-danger">No pathology test templates are available. Please create templates first.</small>
                                            @endif
                                            <input type="hidden" name="form_configuration[]" class="form-config" value="">
                                        </td>
                                        <td>
                                            {{ Form::text('report_days[]', null, ['placeholder' => __('messages.pathology_test.report_days'), 'class' => 'form-control report-days', 'id' => 'report_days_1', 'readonly']) }}
                                        </td>
                                        <td>
                                            {{ Form::date('report_date[]', date('Y-m-d'), ['placeholder' => __('messages.pathology_test.report_date'), 'class' => 'form-control report-date', 'id' => 'report_date_1', 'required', 'min' => date('Y-m-d'), 'readonly']) }}
                                        </td>
                                        <td>
                                            {{ Form::text('amount[]', null, ['placeholder' => __('messages.pathology_test.amount'), 'class' => 'form-control amount', 'id' => 'amount_1', 'readonly']) }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
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
                        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary', 'id' => 'submitPathologyTest']) }}
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
