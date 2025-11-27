<div class="row gx-10 mb-5">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                <div class="form-group mb-3 position-relative">
                    
                    <input type="text" value="{{ $data['patient_name'] ?? '' }}" id="patient_search" class="form-control" placeholder="Search and select patient" autocomplete="off">
                    <div id="patient_results" class="list-group ipdPatientId position-absolute w-100" style="z-index: 1000;"></div>
                    <input type="hidden" name="patient_id" id="selected_patient_id" value="{{ $data['patient_id'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label required']) }}
                {{ Form::select('case_id', [null], null, ['class' => 'form-select ipdDepartmentCaseId', 'id' => 'ipdDepartmentCaseId', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                {{ Form::text('ipd_number', $data['ipdNumber'], ['class' => 'form-control', 'readonly', 'placeholder' => __('messages.ipd_patient.ipd_number')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', 0, ['class' => 'form-control ipdDepartmentFloatNumber', 'max' => '100', 'step' => '.01', 'tabindex' => '2', 'placeholder' => __('messages.ipd_patient.height')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', 0, ['placeholder' => __('messages.ipd_patient.weight'), 'class' => 'form-control ipdDepartmentFloatNumber', 'data-mask' => '##0,00', 'max' => '200', 'step' => '.01', 'tabindex' => '3']) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp') . ':', ['class' => 'form-label']) }}
                {{ Form::text('bp', null, ['class' => 'form-control', 'tabindex' => '4', 'placeholder' => __('messages.ipd_patient.bp')]) }}
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

    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('admission_date', __('messages.ipd_patient.admission_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('admission_date', null, ['placeholder' => __('messages.ipd_patient.admission_date'), 'class' => getLoggedInUser()->thememode ? 'bg-light ipdAdmissionDate form-control' : 'bg-white ipdAdmissionDate form-control', 'id' => 'ipdAdmissionDate', 'autocomplete' => 'off', 'required', 'tabindex' => '5']) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('doctor_id', $data['doctors'], null, ['class' => 'form-select', 'id' => 'ipdDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2', 'tabindex' => '6']) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_type_id', __('messages.ipd_patient.bed_type_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_type_id', $data['bedTypes'], null, ['class' => 'form-select ipdBedTypeId', 'required', 'id' => 'ipdBedTypeId', 'placeholder' => __('messages.bed.select_bed_type'), 'data-control' => 'select2', 'tabindex' => '7']) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_id', __('messages.ipd_patient.bed_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_id', [null], null, ['class' => 'form-select bedId', 'required', 'id' => 'ipdBedId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.bed_assign.bed')]) }}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px" name="is_old_patient" type="checkbox" value="1" id="ipdFlexSwitchDefault">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms', __('messages.ipd_patient.symptoms') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes', __('messages.ipd_patient.notes') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
</div>

{{-- 🔥 ADDED FOR REDIRECTING BACK TO IPD PROFILE --}}
<input type="hidden" name="create_from_route" value="ipd">
<input type="hidden" name="ipd_id" value="{{ $data['ipd_id'] ?? '' }}">

@if(!isset($data['modal_form']) || !$data['modal_form'])
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'ipdSave']) !!}
    <a href="{!! route('ipd.patient.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
@endif
