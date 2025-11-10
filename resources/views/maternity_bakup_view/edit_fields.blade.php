<div class="row gx-10 mb-5">
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
                {{ Form::hidden('patient_id', !empty($maternityPatient->patient_id) ? $maternityPatient->patient_id : '', ['class' => 'currencySymbol']) }}
                {{ Form::select('patient_id_select', $data['patients'], !empty($maternityPatient->patient_id) ? $maternityPatient->patient_id : '', ['class' => 'form-select', 'required', 'id' => 'editMaternityPatientId', 'placeholder' => __('messages.document.select_patient'), 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                {{ Form::select('case_id', [null], null, ['class' => 'form-select', 'required', 'id' => 'editMaternityCaseId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
                {{ Form::hidden('patient_case_id', !empty($maternityPatient->patientCase) ? $maternityPatient->patientCase->case_id : 'patient_case_id', ['class' => 'patientCaseId']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('maternity_number', __('messages.maternity_patient.maternity_number') . ':', ['class' => 'form-label']) }}
                {{ Form::text('maternity_number', null, ['class' => 'form-control', 'readonly','placeholder' => __('messages.maternity_patient.maternity_number')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', null, ['placeholder' => __('messages.ipd_patient.height') ,'class' => 'form-control', 'max' => '7', 'step' => '.01']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', null, ['placeholder' => __('messages.ipd_patient.weight'),'class' => 'form-control', 'max' => '200', 'step' => '.01']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp') . ':', ['class' => 'form-label']) }}
                {{ Form::text('bp', null, ['placeholder'=>__('messages.ipd_patient.bp'),'class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('pulse', __('messages.ipd_patient_diagnosis.pulse') . ':', ['class' => 'form-label']) }}
                {{ Form::text('pulse', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.pulse')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('respiration', __('messages.ipd_patient_diagnosis.respiration') . ':', ['class' => 'form-label']) }}
                {{ Form::text('respiration', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.respiration')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('temperature', __('messages.ipd_patient_diagnosis.temperature') . ':', ['class' => 'form-label']) }}
                {{ Form::text('temperature', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.temperature')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('oxygen_saturation', __('messages.ipd_patient_diagnosis.oxygen_saturation') . ':', ['class' => 'form-label']) }}
                {{ Form::text('oxygen_saturation', null, ['class' => 'form-control', 'placeholder' => __('messages.ipd_patient_diagnosis.oxygen_saturation')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('appointment_date', __('messages.opd_patient.appointment_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('appointment_date', null, ['placeholder' => __('messages.opd_patient.appointment_date'),'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'editOpdAppointmentDate', 'autocomplete' => 'off', 'required']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('doctor_id', $data['doctors'], null, ['class' => 'form-select', '', 'id' => 'editMaternityDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('charge_id', __('messages.consultation_charge') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('charge_id', $data['charges'], null, ['class' => 'form-select', 'required', 'id' => 'editMaternityChargeId', 'placeholder' => __('messages.select_consultation_charge'), 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                <div class="form-group">
                    {{ Form::label('standard_charge', __('messages.doctor_maternity_charge.standard_charge') . ':', ['class' => 'form-label']) }}
                    <div class="input-group">
                        {{ Form::text('standard_charge', null, ['class' => 'form-control price-input', 'id' => 'editMaternityStandardCharge', 'required','placeholder'=>__('messages.doctor_maternity_charge.standard_charge')]) }}
                        <div class="input-group-text border-0"><a><span>{{ getCurrencySymbol() }}</span></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('payment_mode', $data['paymentMode'], null, ['class' => 'form-select', 'required', 'id' => 'editOpdPaymentMode', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.payment.payment')]) }}
            </div>
        </div>
    </div> --}}
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms', __('messages.ipd_patient.symptoms') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes', __('messages.ipd_patient.notes') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}
                <br>
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px" name="is_old_patient" type="checkbox" value="1"
                        {{ $maternityPatient->is_old_patient ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3', 'id' => 'btnEditMaternitySave']) }}
    <a href="{!! route('maternity.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
