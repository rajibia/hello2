<div id="edit_complaint_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Complaint</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editComplaintForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editComplaintErrorsBox"></div>
               
                {{ Form::hidden('complaint_id', null, ['id' => 'editComplaintId']) }}
                <div class="row gx-10 mb-5">
                <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('main_complaint', 'Main Complaint:', ['class' => 'form-label']) }}
                                {{ Form::textarea('main_complaint', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Main Complaint', 'id' => 'editMainComplaintValue']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('main_complaint_progression', 'Progression of Main Complaint:', ['class' => 'form-label']) }}
                                {{ Form::textarea('main_complaint_progression', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Progression of Main Complaint', 'id' => 'editMainComplaintProgressionValue']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('direct_questioning', 'Direct Questioning:', ['class' => 'form-label']) }}
                                {{ Form::textarea('direct_questioning', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Direct Questioning', 'id' => 'editDirectQuestioningValue']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('drug_history', 'Drug History:', ['class' => 'form-label']) }}
                                {{ Form::textarea('drug_history', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Drug History', 'id' => 'editDrugHistoryValue']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnEditComplaintSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" id="btnComplaintCancel"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
