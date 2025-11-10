<div id="edit_vitals_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.advanced_payment.edit_advanced_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editVitalsForm']) }}
            {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editVitalsErrorsBox"></div>
                {{ Form::hidden('vitals_id', null, ['id' => 'vitalsId']) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('patient_id', __('messages.advanced_payment.patient') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('patient_id', $patients ?? [], null, ['class' => 'form-select', 'id' => 'editPatientId', 'placeholder' => __('messages.document.select_patient'), 'required', 'data-control' => 'select2']) }}
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
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
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'editVitalsSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
