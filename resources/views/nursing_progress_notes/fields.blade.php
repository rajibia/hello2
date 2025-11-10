<div class="row gx-10 mb-5">
    @if($patient_id != '')
        <div class="form-group col-md-12 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', ($patient_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
    
        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
        <input type="hidden" name="create_from_route" value="patient">
    @endif

    @if ($opd_id != '') 
        <div class="form-group col-md-12 mb-5">
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
        <div class="form-group col-md-12 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        @if($ipd_id != '')
            <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
            <input type="hidden" name="create_from_route" value="ipd">
        @endif
    @endif

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes', 'Progress Notes:', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'required', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Progress Notes']) }}
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

