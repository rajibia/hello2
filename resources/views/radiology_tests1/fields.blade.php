<div class="row">
    @if($patient_id != '')
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', ($patient_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
        <input type="hidden" name="create_from_route" value="patient">
    {{-- @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div> --}}
    @endif

    @if ($opd_id != '') 
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', ($opd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        <input type="hidden" name="opd_id" value="{{ $opd_id }}">
        <input type="hidden" name="create_from_route" value="opd">
    {{-- @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div> --}}
    @endif

    @if ($ipd_id != '') 
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div>
        <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
        <input type="hidden" name="create_from_route" value="ipd">
    {{-- @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
        </div> --}}
    @endif

    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('test_name', __('messages.radiology_test.test_name').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('test_name', null, ['class' => 'form-control','required','placeholder'=>__('messages.radiology_test.test_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('short_name', __('messages.radiology_test.short_name').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('short_name', null, ['class' => 'form-control','required','placeholder'=>__('messages.radiology_test.short_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('test_type', __('messages.radiology_test.test_type').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('test_type', null, ['class' => 'form-control','required','placeholder'=>__('messages.radiology_test.test_type')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('category_id', __('messages.radiology_test.category_name').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('category_id',$data['radiologyCategories'], null, ['class' => 'form-select radiologyCategories','required','id' => 'radiologyCategories','placeholder'=>__('messages.medicine.select_category'),'required']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('unit', 'Unit:',['class' => 'form-label']) }}
            {{ Form::number('unit', null, ['class' => 'form-control','placeholder'=>'Unit']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('subcategory', __('messages.radiology_test.subcategory').':',['class' => 'form-label']) }}
            {{ Form::text('subcategory', null, ['class' => 'form-control','placeholder'=>__('messages.radiology_test.subcategory')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('method', __('messages.pathology_test.method').':',['class' => 'form-label']) }}
            {{ Form::text('method', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.method')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('report_days', __('messages.radiology_test.report_days').':',['class' => 'form-label']) }}
            {{ Form::number('report_days', null, ['class' => 'form-control','placeholder'=>__('messages.radiology_test.report_days')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('charge_category_id', __('messages.radiology_test.charge_category').':',['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('charge_category_id',$data['chargeCategories'], null, ['class' => 'form-select pChargeCategories','required','id' => 'radiologyChargeCategories','placeholder'=>__('messages.radiology_test.charge_category'),'required']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('standard_charge', __('messages.radiology_test.standard_charge').':',['class' => 'form-label']) }}
            <span class="required"></span>
            (<b>{{ getCurrencySymbol() }}</b>)
            {{ Form::text('standard_charge', null, ['placeholder' => __('messages.radiology_test.standard_charge'),'class' => 'form-control price-input radiologyStandardCharge', 'id' => 'pTestStandardCharge', 'readonly', 'required']) }}
        </div>
    </div>
    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician'))
        <div class="col-sm-12">
            <div class="table-responsive-sm">
                <div class="overflow-auto">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="">{{ __('messages.new_change.parameter_name') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.new_change.patient_result') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.new_change.reference_range') }}<span class="required"></span>
                                </th>
                                <th class="">{{ __('messages.pathology_test.unit') }}<span class="required"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="radiology-test-container">
                            <tr>
                                <td>
                                    {{ Form::select('parameter_id[]', $data['radiologyParameters'], null, ['class' => 'form-select  select2Selector radiology-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }}
                                </td>
                                <td>
                                    {{ Form::text('patient_result[]', null, ['placeholder' => __('messages.new_change.patient_result'),'class' => 'form-control ', 'data-id' => 1]) }}
                                </td>
                                <td>
                                    {{ Form::text('reference_range[]', null, ['placeholder' => __('messages.new_change.reference_range'),'class' => 'form-control', 'id' => 'rangeId', 'readonly']) }}
                                </td>
                                <td>
                                    {{ Form::text('unit_id[]', null, ['placeholder' => __('messages.pathology_test.unit'),'class' => 'form-control', 'id' => 'unitId', 'readonly']) }}
                                </td>
                                <td class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                                    <a href="javascript:void(0)" type="button"
                                        class="btn btn-primary text-star add-parameter-radiology-test">
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
    <div class="d-flex justify-content-end">
        <div class="form-group">
            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
            <a href="{{ route('radiology.test.index') }}"
               class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
        </div>
    </div>
</div>
