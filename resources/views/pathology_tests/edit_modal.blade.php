<div id="edit_pathology_test_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Pathology Test</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editPathTestForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editPathologyTestErrorsBox"></div>
                @if (isset($opdPatientDepartment))
                    {{ Form::hidden('opd_id', $opdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $opdPatientDepartment->patient_id) }}
                @elseif(isset($ipdPatientDepartment))
                    {{ Form::hidden('ipd_id', $ipdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $ipdPatientDepartment->patient_id) }}
                @endif

                {{ Form::hidden('pathologyTestUrl', url('pathology-tests'), ['id' => 'pathologyTestURL']) }}
                {{ Form::hidden('pathology.test.edit.modal', url('pathology-tests-show-modal'), ['id' => 'pathologyTestBillEditUrl']) }}
                {{ Form::hidden('pathologyTestActionUrl', url('pathology-tests'), ['class' => 'pathologyTestActionURL']) }}
                {{ Form::hidden('editpathologyTestUrl', url('pathology-tests'), ['id' => 'editpathologyTestUrl']) }}
                {{ Form::hidden('pathology_test', __('messages.pathology_test.pathology_tests'), ['id' => 'pathologyTest']) }}
                {{ Form::hidden('uniqueId',2,['id'=>'parameterUniqueId'])}}
                {{ Form::hidden('associateParameters',json_encode($parameterList),['class'=>'associateParameters'])}}


                <input type="hidden" name="path_id" value="" id="path_edit_id" >

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
                        </div>
                    @endif
                    
                        <div class="form-group col-md-3 mb-5">
                            {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('case_id', $caseIds, null, ['class' => 'form-select  ', ($case_id != '' ? 'disabled' : ''), 'required', 'id' => 'vitalsEdCASEID', 'placeholder' => __('messages.ipd_patient.case_id')]) }}
                           {{-- {{($caseIds)}} --}}
                            <input type="hidden" name="case_id" value="" id="vitalsEdCASEIDHid">
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
                                            <tbody class="edit-pathology-test-container" id="pathEdParam">
                                                
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
                                        {{ Form::select('referral_doctor', $doctors, null, ['class' => 'form-select referral_doctor_edit_path', '', 'required', 'id' => 'referral_doctor_edit_path', 'placeholder' => __('messages.pathology_test.referral_doctor')]) }}
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mb-5">
                                        {{ Form::label('doctor_name', __('messages.pathology_test.reference_name').':',['class' => 'form-label']) }}
                                        <span class="required"></span>
                                        {{ Form::text('doctor_name', null, ['class' => 'form-control','required', 'id' => 'doctor_name_edit_path', 'placeholder'=>__('messages.pathology_test.reference_name')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-5">
                                        {{ Form::label('note', __('messages.pathology_test.note').':',['class' => 'form-label']) }}
                                        {{ Form::textarea('note', null, ['class' => 'form-control', 'id' => 'noteEd', 'placeholder'=>__('messages.pathology_test.note')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-5">
                                        {{ Form::label('previous_report_value', __('messages.pathology_test.previous_report_value').':',['class' => 'form-label']) }}
                                        {{ Form::text('previous_report_value', null, ['class' => 'form-control', 'id' => 'previous_ed_report_value', 'placeholder'=>__('messages.pathology_test.previous_report_value')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-6">
                           <table>
                                <tbody>
                                    <tr>
                                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.sub_total') }}:</th>
                                        <th style="text-align:right"><span id="sub_total_edit_path">0.00</span> {{getCurrencySymbol()}}</th>
                                    </tr>
                                    
                                    <tr>
                                        <th  style="text-align:left">{{ __('messages.invoice.discount') }}(%):</th>
                                        <th style="text-align:left"> 
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::number('discount_percent', 0, ['class' => 'form-control', 'id' => 'discount_input_edit_path', 'placeholder'=>__('messages.pathology_test.discount_percent')]) }}
                                                </div>
                                            </div>
                                        </th>
                                        <th style="text-align:right"><span id="discount_edit_path">0.00</span> {{getCurrencySymbol()}}</th>
                                        <input type="hidden" name="discount_value" value="" id="discount_hidden_edit_path">
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.total') }}:</th>
                                        <th style="text-align:right"><span id="total_edit_path">0.00</span> {{getCurrencySymbol()}}</th>
                                        <input type="hidden" name="total" value="" id="total_hidden_edit_path">
                                    </tr>
                                    <input type="hidden" name="amount_paid" value="" id="amount_paid_edit_path">
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
                        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'editPathTestSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
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
