<div id="edit_ipd_diagnosis_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.ipd_patient_diagnosis.edit_ipd_diagnosis') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editIpdDiagnosisForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editIpdDiagnosisErrorsBox"></div>
                {{ Form::hidden('id', null, ['id' => 'ipdDiagnosisId']) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('code', __('messages.ipd_patient_diagnosis.code') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('code', null, ['placeholder' => __('messages.ipd_patient_diagnosis.code'),'class' => 'form-control', 'required', 'readOnly', 'id' => 'editIpdDiagnosisCode']) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('name', __('messages.ipd_patient_diagnosis.name') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('name', null, ['placeholder' => __('messages.ipd_patient_diagnosis.name'),'class' => 'form-control', 'required', 'readOnly', 'id' => 'editIpdDiagnosisName']) }}
                    </div>
                    <div class="form-group col-md-12 mb-5">
                        {{ Form::label('report_date', __('messages.ipd_patient_diagnosis.report_date') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('report_date', null, ['placeholder' => __('messages.ipd_patient_diagnosis.report_date'),'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'editIpdDiagnosisReportDate', 'autocomplete' => 'off', 'required']) }}
                    </div>
                    <div class="form-group col-md-12 mb-5">
                        <div class="form-group">
                            {{ Form::label('description', __('messages.ipd_patient_diagnosis.description') . ':', ['class' => 'form-label']) }}
                            {{ Form::textarea('description', null, ['placeholder' => __('messages.ipd_patient_diagnosis.description'),'class' => 'form-control', 'rows' => 4, 'id' => 'editIpdDiagnosisDescription']) }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0">
                    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-2', 'id' => 'editIpdDiagnosisSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" aria-label="Close" id="cancelEditIpdDiagnosis" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
