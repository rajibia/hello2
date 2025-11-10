    <div class="row">
    
        {{-- @if($patient_id != '') --}}
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, null, ['class' => 'form-select', '', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        {{-- <input type="hidden" name="patient_id" value="{{ $patient_id }}"> --}}
        <input type="hidden" name="create_from_route" value="patient">
    </div>
    <div class="row">

        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician'))
        <div class="col-sm-12">
            <div class="table-responsive-sm">
                <div class="overflow-auto">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="">{{ __('messages.pathology_test.test_name') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.pathology_test.report_days') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.pathology_test.report_date') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.pathology_test.amount') }}<span class="required"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="pathology-test-container">
                            <tr>
                                <td>
                                    {{-- {{ Form::select('parameter_id[]', $data['pathologyParameters'], null, ['class' => 'form-select  select2Selector patholory-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }} --}}
                                    {{ Form::select('test_name', $pathologyTestTemplates, null, ['class' => 'form-select', '', 'required', 'id' => 'vitalsTestId', 'placeholder' => __('messages.pathology_test.test_name')]) }}
                                </td>
                                <td>
                                    {{ Form::text('report_days[]', null, ['placeholder' => __('messages.pathology_test.report_days'),'class' => 'form-control', 'id' => 'rangeId', 'readonly']) }}
                                </td>
                                <td>
                                    {{ Form::datetime('report_date[]', null, ['placeholder' => __('messages.pathology_test.report_date'),'class' => 'form-control ', 'data-id' => 1]) }}
                                </td>
                                <td>
                                    {{ Form::text('amount[]', null, ['placeholder' => __('messages.pathology_test.amount'),'class' => 'form-control', 'id' => 'unitId', 'readonly']) }}
                                </td>
                                <td class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                                    <a href="javascript:void(0)" type="button"
                                        class="btn btn-primary text-star add-parameter-test">
                                        {{ __('messages.common.add') }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    
    {{-- @else
    <div class="form-group col-md-3 mb-5">
        {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
    </div> --}}
    {{-- @endif --}}
    
    {{-- @if ($opd_id != '') 
    <div class="form-group col-md-3 mb-5">
            {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', ($opd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        <input type="hidden" name="opd_id" value="{{ $opd_id }}">
        <input type="hidden" name="create_from_route" value="opd"> --}}
    {{-- @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div> --}}
    {{-- @endif --}}

    {{-- @if ($ipd_id != '') 
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
        <input type="hidden" name="create_from_route" value="ipd"> --}}
    {{-- @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div> --}}
    {{-- @endif --}}

    {{-- <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('test_name', __('messages.pathology_test.test_name').':',['class' => 'form-label']) }}
            <span class="required"></span> --}}
            {{-- {{ Form::text('test_name', null, ['class' => 'form-control','required','placeholder'=>__('messages.pathology_test.test_name')]) }} --}}
            {{-- {{ Form::select('test_name', $pathologyTestTemplates, null, ['class' => 'form-select', '', 'required', 'id' => 'vitalsTestId', 'placeholder' => __('messages.pathology_test.test_name')]) }}
        </div>
    </div> --}}
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('referral_doctor', __('messages.pathology_test.referral_doctor').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{-- {{ Form::text('referral_doctor', null, ['class' => 'form-control','required','placeholder'=>__('messages.pathology_test.referral_doctor')]) }} --}}
            {{ Form::select('referral_doctor', $doctors, null, ['class' => 'form-select', '', 'required', 'id' => 'vitalsDoctorId', 'placeholder' => __('messages.pathology_test.referral_doctor')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('doctor_name', __('messages.pathology_test.reference_name').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('doctor_name', null, ['class' => 'form-control','required','placeholder'=>__('messages.pathology_test.reference_name')]) }}
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('subcategory', __('messages.pathology_test.subcategory').':',['class' => 'form-label']) }}
            {{ Form::text('subcategory', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.subcategory')]) }}
        </div>
    </div>
    {{-- <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('method', __('messages.pathology_test.method').':',['class' => 'form-label']) }}
            {{ Form::text('method', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.method')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('report_days', __('messages.pathology_test.report_days').':',['class' => 'form-label']) }}
            {{ Form::number('report_days', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.report_days')]) }}
        </div>
    </div> --}}
    {{-- <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('charge_category_id', __('messages.pathology_test.charge_category').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('charge_category_id',$data['chargeCategories'], null, ['class' => 'form-select pChargeCategories','required','id' => 'pathologyChargeCategories','placeholder'=>__('messages.pathology_category.select_charge_category'),'required']) }}
        </div>
    </div> --}}
   
    
    <div class="d-flex justify-content-end">
        <div class="form-group">
            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
            <a href="{{ route('pathology.test.index') }}"
               class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
        </div>
    </div>
</div>
