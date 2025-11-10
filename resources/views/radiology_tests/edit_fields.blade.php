<div class="row">
    <div class="row">
        @if ($patient_id != '')
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('ss', $patients, $patient_id ?? null, ['class' => 'form-select patient_name', 'disabled', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                <input type="hidden" name="patient_id" value="{{ $patient_id }}">
            </div>
            
            @else
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('patient_id', $patients, null, ['class' => 'form-select patient_name', '', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div>
        @endif
    
        @if ($opd_id != '') 
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
                {{-- <span class="required"></span> --}}
                {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', ($opd_id != '' ? 'disabled' : ''), 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div>
            <input type="hidden" name="opd_id" value="{{ $opd_id }}">
            <input type="hidden" name="create_from_route" value="opd">
        {{-- @else
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('opd_id', $opds, $opd_id ?? null, ['class' => 'form-select', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div> --}}
        @endif

        @if ($ipd_id != '') 
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                {{-- <span class="required"></span> --}}
                {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', ($ipd_id != '' ? 'disabled' : ''), 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div>
            <input type="hidden" name="ipd_id" value="{{ $ipd_id }}">
            <input type="hidden" name="create_from_route" value="ipd">
            {{-- @else
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('ipd_id', $ipds, $ipd_id ?? null, ['class' => 'form-select', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
            </div> --}}
            @endif
            
            <div class="form-group col-md-3 mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('ccc', $caseIds, $case_id, ['class' => 'form-select case_id', ($case_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsCASEID', 'placeholder' => __('messages.ipd_patient.case_id')]) }}
                <input type="hidden" name="case_id" value="{{ $case_id }}">
            </div>

    </div>
    <div class="row">

        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Lab Technician'))
            <div class="col-sm-12">
                <div class="table-responsive-sm">
                    <div class="overflow-auto">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="">{{ __('messages.radiology_test.test_name') }}<span class="required"></span>
                                    </th>
                                    <th class="">{{ __('messages.radiology_test.report_days') }}<span class="required"></span>
                                    </th>
                                    <th class="">{{ __('messages.radiology_test.report_date') }}<span class="required"></span>
                                    </th>
                                    <th class="">{{ __('messages.radiology_test.amount') }}<span class="required"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="radiology-test-container" id="">
                                @if (isset($radiologyTestItems))
                                @php $i=0; @endphp
                                    @foreach ($radiologyTestItems as $key => $radiologyTestItem)
                                    @php $i++; @endphp
                                        <tr>
                                            <td>
                                                    {{-- {{ Form::select('parameter_id[]', $data['radiologyParameters'], null, ['class' => 'form-select  select2Selector patholory-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }} --}}
                                                    {{ Form::select('test_name[]', $radiologyTestTemplates, $radiologyTestItem->test_name, ['class' => 'form-select select2Selector edit_rad', '', 'required', 'id' => '', 'placeholder' => __('messages.radiology_test.test_name')]) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('report_days[]', $radiologyTestItem->radiologytesttemplate->report_days, ['placeholder' => __('messages.radiology_test.report_days'),'class' => 'form-control', 'id' => 'report_ed_rad_days', 'readonly']) }}
                                                </td>
                                                <td>
                                                    {{ Form::date('report_date[]', date("Y-m-d", strtotime($radiologyTestItem->report_date)), ['placeholder' => __('messages.radiology_test.report_date'),'class' => 'form-control ', 'id' => 'report_ed_rad_date']) }}
                                                </td>
                                                <td>
                                                    {{ Form::text('amount[]', number_format($radiologyTestItem->radiologytesttemplate->standard_charge, 2), ['placeholder' => __('messages.radiology_test.amount'),'class' => 'form-control amount_ed_rad_summand', 'id' => 'amount_ed_rad', 'readonly']) }}
                                                </td>
                                                <td class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                                                    @if($i == 1)
                                                    <a href="javascript:void(0)" type="button"
                                                    class="btn btn-primary text-star add-parameter-test-billing-edit-rad">
                                                    {{ __('messages.common.add') }}
                                                    </a>
                                                    @else
                                                    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                                                    class="delete-radiology-parameter-test-ed  btn px-1 text-danger fs-3 pe-0">
                                                            <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="d-flex border-top pt-5">
        <div class="col-md-6">
            <div class="row ">
                <div class="col-md-5">
                    <div class="form-group mb-5">
                        {{ Form::label('doctor_id', __('messages.radiology_test.referral_doctor').':',['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{-- {{ Form::text('referral_doctor', null, ['class' => 'form-control','required','placeholder'=>__('messages.radiology_test.referral_doctor')]) }} --}}
                        {{ Form::select('referral_doctor', $doctors, $doctor_id ?? null, ['class' => 'form-select referral_doctor_edit_rad', '', 'required', 'id' => 'referral_doctor_edit_rad', 'placeholder' => __('messages.radiology_test.referral_doctor')]) }}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mb-5">
                        {{ Form::label('doctor_name', __('messages.radiology_test.reference_name').':',['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('doctor_name', $doctor_name ?? null, ['class' => 'form-control','required', 'id' => 'doctor_name_edit_rad', 'placeholder'=>__('messages.radiology_test.reference_name')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group mb-5">
                        {{ Form::label('note', __('messages.radiology_test.note').':',['class' => 'form-label']) }}
                        {{ Form::textarea('note', null, ['class' => 'form-control', 'id' => 'noteEd_rad', 'placeholder'=>__('messages.radiology_test.note')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group mb-5">
                        {{ Form::label('previous_report_value', __('messages.radiology_test.previous_report_value').':',['class' => 'form-label']) }}
                        {{ Form::text('previous_report_value', null, ['class' => 'form-control', 'id' => 'previous_ed_rad_report_value', 'placeholder'=>__('messages.radiology_test.previous_report_value')]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-md-6">
           <table>
                <tbody>
                    <tr>
                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.sub_total') }}:</th>
                        <th style="text-align:right"><span id="sub_ed_rad_total">{{number_format(floor($radiologyTest->total) + floor($amount), 2)}}</span> {{getCurrencySymbol()}}</th>
                    </tr>
                    
                    <tr>
                        <th  style="text-align:left">{{ __('messages.invoice.discount') }}(%):</th>
                        <th style="text-align:left"> 
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::number('discount_percent', $percentage, ['class' => 'form-control', 'id' => 'discount_ed_rad_input', 'placeholder'=>__('messages.radiology_test.discount_percent')]) }}
                                </div>
                            </div>
                        </th>
                        <th style="text-align:right"><span id="discount_ed_rad">{{number_format( floor($amount), 2)}}</span> {{getCurrencySymbol()}}</th>
                        <input type="hidden" name="discount_value" value="{{number_format( floor($amount), 2)}}" id="discount_ed_rad_hidden">
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.total') }}:</th>
                        <th style="text-align:right"><span id="total_ed_rad">{{number_format( $radiologyTest->total, 2)}}</span> {{getCurrencySymbol()}}</th>
                        <input type="hidden" name="amount_paid" value="{{number_format( $radiologyTest->amount_paid, 2)}}" id="amount_ed_rad_paid">
                        <input type="hidden" name="total" value="{{number_format( $radiologyTest->total, 2)}}" id="total_ed_rad_hidden">
                    </tr>
                </tbody>
           </table>
        </div>
    </div>
 
   
    
    <div class="d-flex justify-content-end">
        <div class="form-group">
            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
            <a href="{{ route('radiology.test.index') }}"
               class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
        </div>
    </div>
</div>
