<div id="edit_general_examination_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Examination</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editGeneralExaminationForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editGeneralExaminationErrorsBox"></div>
               
                {{ Form::hidden('general_examination_id', null, ['id' => 'editGeneralExaminationId']) }}

                <div class="row gx-10 mb-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('general_examination', 'General Examination Comment:', ['class' => 'form-label']) }}
                                {{ Form::textarea('general_examination', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'General Examination Comment', 'id' => 'editGeneralExaminationValue']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Body Part</th>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lungs</td>
                                <td>
                                    {{ Form::radio('lungs_status', 0, ['id' => 'opdLungStatusView']) }}
                                    {{ Form::label('lungs_status_unknown', 'Unknown') }}
                                    {{ Form::radio('lungs_status', 1, ['id' => 'opdLungStatusView']) }}
                                    {{ Form::label('lungs_status_yes', 'Yes') }}
                                    {{ Form::radio('lungs_status', 2, ['id' => 'opdLungStatusView']) }}
                                    {{ Form::label('lungs_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('lungs_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_lungs_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Cardio</td>
                                <td>
                                    {{ Form::radio('cardio_status', 0, ['id' => 'cardioStatus']) }}
                                    {{ Form::label('cardio_status_unknown', 'Unknown') }}
                                    {{ Form::radio('cardio_status', 1, ['id' => 'cardioStatus']) }}
                                    {{ Form::label('cardio_status_yes', 'Yes') }}
                                    {{ Form::radio('cardio_status', 2, ['id' => 'cardioStatus']) }}
                                    {{ Form::label('cardio_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('cardio_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_cardio_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Abdomen</td>
                                <td>
                                    {{ Form::radio('abdomen_status', 0, ['id' => 'abdomenStatus']) }}
                                    {{ Form::label('abdomen_status_unknown', 'Unknown') }}
                                    {{ Form::radio('abdomen_status', 1, ['id' => 'abdomenStatus']) }}
                                    {{ Form::label('abdomen_status_yes', 'Yes') }}
                                    {{ Form::radio('abdomen_status', 2, ['id' => 'abdomenStatus']) }}
                                    {{ Form::label('abdomen_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('abdomen_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_abdomen_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Ear</td>
                                <td>
                                    {{ Form::radio('ear_status', 0, ['id' => 'earStatus']) }}
                                    {{ Form::label('ear_status_unknown', 'Unknown') }}
                                    {{ Form::radio('ear_status', 1, ['id' => 'earStatus']) }}
                                    {{ Form::label('ear_status_yes', 'Yes') }}
                                    {{ Form::radio('ear_status', 2, ['id' => 'earStatus']) }}
                                    {{ Form::label('ear_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('ear_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_ear_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nose</td>
                                <td>
                                    {{ Form::radio('nose_status', 0, ['id' => 'noseStatus']) }}
                                    {{ Form::label('nose_status_unknown', 'Unknown') }}
                                    {{ Form::radio('nose_status', 1, ['id' => 'noseStatus']) }}
                                    {{ Form::label('nose_status_yes', 'Yes') }}
                                    {{ Form::radio('nose_status', 2, ['id' => 'noseStatus']) }}
                                    {{ Form::label('nose_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nose_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_nose_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Throat</td>
                                <td>
                                    {{ Form::radio('throat_status', 0, ['id' => 'throatStatus']) }}
                                    {{ Form::label('throat_status_unknown', 'Unknown') }}
                                    {{ Form::radio('throat_status', 1, ['id' => 'throatStatus']) }}
                                    {{ Form::label('throat_status_yes', 'Yes') }}
                                    {{ Form::radio('throat_status', 2, ['id' => 'throatStatus']) }}
                                    {{ Form::label('throat_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('throat_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_throat_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Muscles</td>
                                <td>
                                    {{ Form::radio('musco_status', 0, ['id' => 'muscoStatus']) }}
                                    {{ Form::label('musco_status_unknown', 'Unknown') }}
                                    {{ Form::radio('musco_status', 1, ['id' => 'muscoStatus']) }}
                                    {{ Form::label('musco_status_yes', 'Yes') }}
                                    {{ Form::radio('musco_status', 2, ['id' => 'muscoStatus']) }}
                                    {{ Form::label('musco_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('musco_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_muscos_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nervous</td>
                                <td>
                                    {{ Form::radio('nervous_status', 0, ['id' => 'nervousStatus']) }}
                                    {{ Form::label('nervous_status_unknown', 'Unknown') }}
                                    {{ Form::radio('nervous_status', 1, ['id' => 'nervousStatus']) }}
                                    {{ Form::label('nervous_status_yes', 'Yes') }}
                                    {{ Form::radio('nervous_status', 2, ['id' => 'nervousStatus']) }}
                                    {{ Form::label('nervous_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nervous_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_nervous_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Skin</td>
                                <td>
                                    {{ Form::radio('skin_status', 0, ['id' => 'skinStatus']) }}
                                    {{ Form::label('skin_status_unknown', 'Unknown') }}
                                    {{ Form::radio('skin_status', 1, ['id' => 'skinStatus']) }}
                                    {{ Form::label('skin_status_yes', 'Yes') }}
                                    {{ Form::radio('skin_status', 2, ['id' => 'skinStatus']) }}
                                    {{ Form::label('skin_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('skin_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_skin_description']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Eye</td>
                                <td>
                                    {{ Form::radio('eye_status', 0, ['id' => 'eyeStatus']) }}
                                    {{ Form::label('eye_status_unknown', 'Unknown') }}
                                    {{ Form::radio('eye_status', 1, ['id' => 'eyeStatus']) }}
                                    {{ Form::label('eye_status_yes', 'Yes') }}
                                    {{ Form::radio('eye_status', 2, ['id' => 'eyeStatus']) }}
                                    {{ Form::label('eye_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('eye_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'edit_eye_description']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                

                <div class="d-flex justify-content-end">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnEditGeneralExaminationSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" id="btnGeneralExaminationCancel"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
