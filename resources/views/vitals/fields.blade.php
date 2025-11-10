<div class="row gx-10 mb-5">
    @if($patient_id != '')
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', ($patient_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>

        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
        <input type="hidden" name="create_from_route" value="patient">
    @endif

    @if ($opd_id != '')
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', ($opd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        @if($opd_id != '')
            <input type="hidden" name="opd_id" value="{{ $opd_id }}">
            <input type="hidden" name="create_from_route" value="opd">
        @endif
    @endif

    @if ($ipd_id != '')
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        @if($ipd_id != '')
            <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
            @if($maternity_id != '')
                <input type="hidden" name="maternity_id" value="{{ $maternity_id }}">
                <input type="hidden" name="create_from_route" value="maternity">
            @else
                <input type="hidden" name="create_from_route" value="ipd">
            @endif
        @endif
    @endif

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', 0, ['class' => 'form-control ipdDepartmentFloatNumber', 'required', 'max' => '100', 'step' => '.01', 'tabindex' => '2', 'placeholder' => __('messages.ipd_patient.height')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', 0, ['placeholder' => __('messages.ipd_patient.weight'), 'class' => 'form-control ipdDepartmentFloatNumber', 'required', 'data-mask' => '##0,00', 'max' => '200', 'step' => '.01', 'tabindex' => '3']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp') . ':', ['class' => 'form-label']) }}
                {{ Form::text('bp', null, ['class' => 'form-control', 'required', 'tabindex' => '4', 'placeholder' => __('messages.ipd_patient.bp')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('pulse', __('messages.ipd_patient_diagnosis.pulse') . ':', ['class' => 'form-label']) }}
                {{ Form::text('pulse', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.ipd_patient_diagnosis.pulse')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('respiration', __('messages.ipd_patient_diagnosis.respiration') . ':', ['class' => 'form-label']) }}
                {{ Form::text('respiration', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.ipd_patient_diagnosis.respiration')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('temperature', __('messages.ipd_patient_diagnosis.temperature') . ':', ['class' => 'form-label']) }}
                {{ Form::text('temperature', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.ipd_patient_diagnosis.temperature')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('oxygen_saturation', __('messages.ipd_patient_diagnosis.oxygen_saturation') . ':', ['class' => 'form-label']) }}
                {{ Form::text('oxygen_saturation', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.ipd_patient_diagnosis.oxygen_saturation')]) }}
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary me-2 btnVitalsSave">{{ __('messages.common.save') }}</button>
    @if($patient_id != '')
        <a href="{!! route('patients.show', $patient_id) !!}"
        class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
    @endif

</div>

