<div id="add_pathology_test_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <h2>Add Pathology Test</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addPathTestForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="addPathologyTestErrorsBox"></div>
                @if (isset($opdPatientDepartment))
                    {{ Form::hidden('opd_id', $opdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $opdPatientDepartment->patient_id) }}
                @elseif(isset($ipdPatientDepartment))
                    {{ Form::hidden('ipd_id', $ipdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $ipdPatientDepartment->patient_id) }}
                @endif

                {{ Form::hidden('pathologyTestUrl', url('pathology-tests'), ['id' => 'pathologyTestURL']) }}
                {{ Form::hidden('pathology.test.show.modal', url('pathology-tests-show-modal'), ['id' => 'pathologyTestBillShowUrl']) }}
                {{ Form::hidden('pathologyTestActionUrl', url('pathology-tests'), ['class' => 'pathologyTestActionURL']) }}
                {{ Form::hidden('addpathologyTestUrl', route('pathology.test.store'), ['id' => 'addpathologyTestUrl']) }}
                {{ Form::hidden('pathology_test', __('messages.pathology_test.pathology_tests'), ['id' => 'pathologyTest']) }}
                {{ Form::hidden('uniqueId',2,['id'=>'parameterUniqueId'])}}
                {{ Form::hidden('associateParameters',json_encode($parameterList),['class'=>'associateParameters'])}}




                <div class="row">
                    <div class="row">
                        @if (isset($ipdPatientDepartment) && $ipdPatientDepartment->patient_id)
                            <div class="form-group col-md-3 mb-5">
                                {{ Form::label('patient_id_lbl', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
                                <span class="required"></span>
                                {{ Form::select('patient_id', $patients, $ipdPatientDepartment->patient_id ?? "", ['class' => 'form-select patient_name', ($ipdPatientDepartment->patient_id ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                            </div>
                            <input type="hidden" name="patient_id" value="{{ $ipdPatientDepartment->patient_id ?? "" }}">
                        @endif

                        @if (isset($opdPatientDepartment) && $opdPatientDepartment->patient_id)
                            <div class="form-group col-md-3 mb-5">
                                {{ Form::label('patient_id_lbl', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
                                <span class="required"></span>
                                {{ Form::select('patient_id', $patients, $opdPatientDepartment->patient_id ?? "", ['class' => 'form-select patient_name', ($opdPatientDepartment->patient_id ? 'disabled' : ''), 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                            </div>
                            <input type="hidden" name="patient_id" value="{{ $opdPatientDepartment->patient_id ?? "" }}">
                        @endif
                    
                        @if (isset($opdPatientDepartment) && $opdPatientDepartment->id != '') 
                            <div class="form-group col-md-3 mb-5">
                                {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
                                {{-- <span class="required"></span> --}}
                                {{ Form::select('opd_id', $opds, isset($opdPatientDepartment) ? $opdPatientDepartment->id : "", ['class' => 'form-select', ($opdPatientDepartment->id != '' ? 'disabled' : ''), 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                            </div>
                            <input type="hidden" name="opd_id" value="{{ isset($opdPatientDepartment) ? $opdPatientDepartment->id : "" }}">
                            <input type="hidden" name="create_from_route" value="opd">
                        @endif
                    
                    
                        @if (isset($ipdPatientDepartment) && $ipdPatientDepartment->id != '') 
                            <div class="form-group col-md-3 mb-5">
                                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                                {{-- <span class="required"></span> --}}
                                {{ Form::select('ipd_id', $ipds, isset($ipdPatientDepartment) ? $ipdPatientDepartment->id : "", ['class' => 'form-select', ($ipdPatientDepartment->id != '' ? 'disabled' : ''), 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                                <input type="hidden" name="ipd_id" value="{{ isset($ipdPatientDepartment) ? $ipdPatientDepartment->id : "" }}">
                                <input type="hidden" name="create_from_route" value="ipd">
                            </div>
                        @endif
                    
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('case_id1', $caseIds, $case_id, ['class' => 'form-select case_id', ($case_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsCASEID', 'placeholder' => __('messages.ipd_patient.case_id')]) }}
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
                                            <tbody class="add-pathology-test-container">
                                                <tr>
                                                    <td>
                                                        {{-- {{ Form::select('parameter_id[]', $data['pathologyParameters'], null, ['class' => 'form-select  select2Selector patholory-parameter-data', 'required', 'placeholder' => __('messages.new_change.select_parameter_name'), 'data-id' => '1', 'data-control' => 'select2']) }} --}}
                                                        {{ Form::select('test_name[]', $pathologyTestTemplates, null, ['class' => 'form-select select2Selector add_path', '', 'required', 'id' => 'vitalsModalTestId', 'placeholder' => __('messages.pathology_test.select_test_name')]) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::text('report_days[]', null, ['placeholder' => __('messages.pathology_test.report_days'),'class' => 'form-control', 'id' => 'report_days', 'readonly']) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::date('report_date[]', null, ['placeholder' => __('messages.pathology_test.report_date'),'class' => 'form-control ', 'id' => 'report_date']) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::text('amount[]', null, ['placeholder' => __('messages.pathology_test.amount'),'class' => 'form-control amount_summand', 'id' => 'amount', 'readonly']) }}
                                                    </td>
                                                    <td class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                                                        <a href="javascript:void(0)" type="button"
                                                            class="btn btn-primary text-star add-parameter-test-billing-modal">
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
                    </div>
                    
                    <div class="d-flex border-top pt-5">
                        <div class="col-md-6">
                            <div class="row ">
                                <div class="col-md-5">
                                    <div class="form-group mb-5">
                                        {{ Form::label('doctor_id', __('messages.pathology_test.referral_doctor').':',['class' => 'form-label']) }}
                                        <span class="required"></span>
                                        {{-- {{ Form::text('referral_doctor', null, ['class' => 'form-control','required','placeholder'=>__('messages.pathology_test.referral_doctor')]) }} --}}
                                        {{ Form::select('referral_doctor', $doctors, null, ['class' => 'form-select referral_doctor', '', 'required', 'id' => 'vitalsDoctorId', 'placeholder' => __('messages.pathology_test.referral_doctor')]) }}
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mb-5">
                                        {{ Form::label('doctor_name', __('messages.pathology_test.reference_name').':',['class' => 'form-label']) }}
                                        <span class="required"></span>
                                        {{ Form::text('doctor_name', null, ['class' => 'form-control','required', 'id' => 'doctor_name', 'placeholder'=>__('messages.pathology_test.reference_name')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-5">
                                        {{ Form::label('note', __('messages.pathology_test.note').':',['class' => 'form-label']) }}
                                        {{ Form::textarea('note', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.note')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-5">
                                        {{ Form::label('previous_report_value', __('messages.pathology_test.previous_report_value').':',['class' => 'form-label']) }}
                                        {{ Form::text('previous_report_value', null, ['class' => 'form-control','placeholder'=>__('messages.pathology_test.previous_report_value')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-6">
                           <table>
                                <tbody>
                                    <tr>
                                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.sub_total') }}:</th>
                                        <th style="text-align:right"><span id="sub_total_add_path">0.00</span> {{getCurrencySymbol()}}</th>
                                    </tr>
                                    
                                    <tr>
                                        <th  style="text-align:left">{{ __('messages.invoice.discount') }}(%):</th>
                                        <th style="text-align:left"> 
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::number('discount_percent', 0, ['class' => 'form-control', 'id' => 'discount_input_add_path', 'placeholder'=>__('messages.pathology_test.discount_percent')]) }}
                                                </div>
                                            </div>
                                        </th>
                                        <th style="text-align:right"><span id="discount_add_path">0.00</span> {{getCurrencySymbol()}}</th>
                                        <input type="hidden" name="discount_value" value="" id="discount_hidden_add_path">
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.total') }}:</th>
                                        <th style="text-align:right"><span id="total_add_path">0.00</span> {{getCurrencySymbol()}}</th>
                                        <input type="hidden" name="total" value="" id="total_hidden_add_path">
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                    </div>
                 
                   
                    
                    {{-- <div class="d-flex justify-content-end">
                        <div class="form-group">
                            {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2']) }}
                            <a href="{{ route('pathology.test.index') }}"
                               class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
                        </div>
                    </div> --}}

                    <div class="d-flex justify-content-end">
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'addPathTestSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                        <button type="button" id="btnComplaintCancel"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
