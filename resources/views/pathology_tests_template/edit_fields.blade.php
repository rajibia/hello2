<div class="row">
    @if ($patient_id != '')
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, $patient_id ?? null, ['class' => 'form-select', $patient_id != '' ? 'disabled' : '', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
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
            {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', $opd_id != '' ? 'disabled' : '', 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
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
            {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', $ipd_id != '' ? 'disabled' : '', 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
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
            {{ Form::label('test_name', __('messages.pathology_test.test_name') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('test_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.pathology_test.test_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('short_name', __('messages.pathology_test.short_name') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('short_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.pathology_test.short_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('test_type', __('messages.pathology_test.test_type') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('test_type', null, ['class' => 'form-control', 'required', 'placeholder' => __('messages.pathology_test.test_type')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('category_id', __('messages.pathology_test.category_name') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('category_id', $data['pathologyCategories'], null, ['class' => 'form-select pathologyCategories', 'required', 'id' => 'editPathologyCategories', 'placeholder' => __('messages.medicine.select_category'), 'required']) }}
        </div>
    </div>
    {{-- 
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('unit', __('messages.pathology_test.unit') . ':', ['class' => 'form-label']) }}
            {{ Form::number('unit', null, ['class' => 'form-control', 'placeholder' => __('messages.pathology_test.unit')]) }}
        </div>
    </div> --}}
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('subcategory', __('messages.pathology_test.subcategory') . ':', ['class' => 'form-label']) }}
            {{ Form::text('subcategory', null, ['class' => 'form-control', 'placeholder' => __('messages.pathology_test.subcategory')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('method', __('messages.pathology_test.method') . ':', ['class' => 'form-label']) }}
            {{ Form::text('method', null, ['class' => 'form-control', 'placeholder' => __('messages.pathology_test.method')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('report_days', __('messages.pathology_test.report_days') . ':', ['class' => 'form-label']) }}
            {{ Form::number('report_days', null, ['class' => 'form-control', 'placeholder' => __('messages.pathology_test.report_days')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('charge_category_id', __('messages.pathology_test.charge_category') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('charge_category_id', $data['chargeCategories'], null, ['class' => 'form-select pChargeCategories', 'required', 'id' => 'editPathologyChargeCategories', 'placeholder' => __('messages.pathology_category.select_charge_category'), 'required']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('standard_charge', __('messages.pathology_test.standard_charge') . ':', ['class' => 'form-label ']) }}
            <span class="required"></span>
            (<b>{{ getCurrencySymbol() }}</b>)
            {{ Form::text('standard_charge', null, ['placeholder' => __('messages.pathology_test.standard_charge'), 'class' => 'form-control price-input pathologyStandardCharge', 'id' => 'editPTestStandardCharge', 'readonly', 'required']) }}
        </div>
    </div>
    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician'))
        <div class="col-sm-12">
            <div class="table-responsive-sm">
                <div class="overflow-auto">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="">{{ __('messages.new_change.parameter_name') }}<span
                                        class="required"></span>
                                </th>
                                {{-- <th class="">{{ __('messages.new_change.patient_result') }}<span
                                        class="required"></span>
                                </th> --}}
                                <th class="">{{ __('messages.new_change.reference_range') }}<span
                                        class="required"></span>
                                </th>
                                <th class="">{{ __('messages.pathology_test.unit') }}<span
                                        class="required"></span>
                                </th>
                                <th class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                                    <a href="javascript:void(0)" type="button"
                                        class="btn btn-primary text-star add-parameter-test">
                                        {{ __('messages.common.add') }}
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="pathology-test-container">
                            @if (isset($pathologyParameterItems))
                                @foreach ($pathologyParameterItems as $key => $pathologyParameterItem)
                                    <tr>
                                        <td>
                                            {{ Form::select('parameter_id[]', $data['pathologyParameters'], isset($pathologyParameterItem->pathologyParameter) ? $pathologyParameterItem->pathologyParameter->id : null, ['class' => 'form-select  select2Selector patholory-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }}
                                        </td>
                                        {{-- <td>
                                            {{ Form::text('patient_result[]', $pathologyParameterItem->patient_result ?? null, ['placeholder' => __('messages.new_change.patient_result'), 'class' => 'form-control ', 'data-id' => 1, 'required']) }}
                                        </td> --}}
                                        <td>
                                            {{ Form::text('reference_range[]', $pathologyParameterItem->pathologyParameter->reference_range ?? null, ['placeholder' => __('messages.new_change.reference_range'), 'class' => 'form-control', 'id' => 'rangeId', 'readonly']) }}
                                        </td>
                                        <td>
                                            {{ Form::text('unit_id[]', $pathologyParameterItem->pathologyParameter->pathologyUnit->name ?? null, ['placeholder' => __('messages.pathology_test.unit'), 'class' => 'form-control', 'id' => 'unitId', 'readonly']) }}
                                        </td>
                                        @if ($key != 0)
                                            <td class="text-center">
                                                <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                                    class="delete-parameter-test  btn px-1 text-danger fs-3 pe-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            @if ($pathologyParameterItems->count() == false)
                                <tr>
                                    <td>
                                        {{ Form::select('parameter_id[]', $data['pathologyParameters'], null, ['class' => 'form-select  select2Selector patholory-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }}
                                    </td>
                                    {{-- <td>
                                        {{ Form::text('patient_result[]', null, ['placeholder' => __('messages.new_change.patient_result'), 'class' => 'form-control ', 'data-id' => 1, 'required']) }}
                                    </td> --}}
                                    <td>
                                        {{ Form::text('reference_range[]', null, ['placeholder' => __('messages.new_change.reference_range'), 'class' => 'form-control', 'id' => 'rangeId', 'readonly']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('unit_id[]', null, ['placeholder' => __('messages.pathology_test.unit'), 'class' => 'form-control', 'id' => 'unitId', 'readonly']) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    {{-- <div class="col-md-12">
        <div class="form-group mb-5">
            {{ Form::label('status', 'Status:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('status', ['0' => 'Pending', '1' => 'In Progress', '2' => 'Done'], $status, ['class' => 'form-select', 'required', 'id' => 'vitalsPatientId', 'placeholder' => 'Select Status', 'required']) }}
        </div>
    </div> --}}
    <div class="col-md-12">
        <div class="d-flex justify-content-end">
            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
            <a href="{{ route('pathology.test.template.index') }}"
                class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
        </div>
    </div>
</div>
