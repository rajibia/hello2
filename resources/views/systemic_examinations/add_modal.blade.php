<div id="add_systemic_examination_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Systemic Examination</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addSystemicExaminationForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="addSystemicExaminationErrorsBox"></div>
                @if (isset($opdPatientDepartment))
                    {{ Form::hidden('opd_id', $opdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $opdPatientDepartment->id) }}
                @elseif(isset($ipdPatientDepartment))
                    {{ Form::hidden('ipd_id', $ipdPatientDepartment->id) }}
                    {{ Form::hidden('patient_id', $ipdPatientDepartment->patient_id) }}
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
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
                                    {{ Form::radio('lungs_status', 0, true) }}
                                    {{ Form::label('lungs_status_unknown', 'Unknown') }}
                                    {{ Form::radio('lungs_status', 1) }}
                                    {{ Form::label('lungs_status_yes', 'Yes') }}
                                    {{ Form::radio('lungs_status', 2) }}
                                    {{ Form::label('lungs_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('lungs_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
            
                            <tr>
                                <td>Cardio</td>
                                <td>
                                    {{ Form::radio('cardio_status', 0, true) }}
                                    {{ Form::label('cardio_status_unknown', 'Unknown') }}
                                    {{ Form::radio('cardio_status', 1) }}
                                    {{ Form::label('cardio_status_yes', 'Yes') }}
                                    {{ Form::radio('cardio_status', 2) }}
                                    {{ Form::label('cardio_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('cardio_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
            
                            <tr>
                                <td>Abdomen</td>
                                <td>
                                    {{ Form::radio('abdomen_status', 0, true) }}
                                    {{ Form::label('abdomen_status_unknown', 'Unknown') }}
                                    {{ Form::radio('abdomen_status', 1) }}
                                    {{ Form::label('abdomen_status_yes', 'Yes') }}
                                    {{ Form::radio('abdomen_status', 2) }}
                                    {{ Form::label('abdomen_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('abdomen_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
            
                            <tr>
                                <td>Ear</td>
                                <td>
                                    {{ Form::radio('ear_status', 0, true) }}
                                    {{ Form::label('ear_status_unknown', 'Unknown') }}
                                    {{ Form::radio('ear_status', 1) }}
                                    {{ Form::label('ear_status_yes', 'Yes') }}
                                    {{ Form::radio('ear_status', 2) }}
                                    {{ Form::label('ear_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('ear_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Nose</td>
                                <td>
                                    {{ Form::radio('nose_status', 0, true) }}
                                    {{ Form::label('nose_status_unknown', 'Unknown') }}
                                    {{ Form::radio('nose_status', 1) }}
                                    {{ Form::label('nose_status_yes', 'Yes') }}
                                    {{ Form::radio('nose_status', 2) }}
                                    {{ Form::label('nose_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nose_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Throat</td>
                                <td>
                                    {{ Form::radio('throat_status', 0, true) }}
                                    {{ Form::label('throat_status_unknown', 'Unknown') }}
                                    {{ Form::radio('throat_status', 1) }}
                                    {{ Form::label('throat_status_yes', 'Yes') }}
                                    {{ Form::radio('throat_status', 2) }}
                                    {{ Form::label('throat_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('throat_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Musco</td>
                                <td>
                                    {{ Form::radio('musco_status', 0, true) }}
                                    {{ Form::label('musco_status_unknown', 'Unknown') }}
                                    {{ Form::radio('musco_status', 1) }}
                                    {{ Form::label('musco_status_yes', 'Yes') }}
                                    {{ Form::radio('musco_status', 2) }}
                                    {{ Form::label('musco_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('musco_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Nervous</td>
                                <td>
                                    {{ Form::radio('nervous_status', 0, true) }}
                                    {{ Form::label('nervous_status_unknown', 'Unknown') }}
                                    {{ Form::radio('nervous_status', 1) }}
                                    {{ Form::label('nervous_status_yes', 'Yes') }}
                                    {{ Form::radio('nervous_status', 2) }}
                                    {{ Form::label('nervous_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nervous_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Skin</td>
                                <td>
                                    {{ Form::radio('skin_status', 0, true) }}
                                    {{ Form::label('skin_status_unknown', 'Unknown') }}
                                    {{ Form::radio('skin_status', 1) }}
                                    {{ Form::label('skin_status_yes', 'Yes') }}
                                    {{ Form::radio('skin_status', 2) }}
                                    {{ Form::label('skin_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('skin_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Eye</td>
                                <td>
                                    {{ Form::radio('eye_status', 0, true) }}
                                    {{ Form::label('eye_status_unknown', 'Unknown') }}
                                    {{ Form::radio('eye_status', 1) }}
                                    {{ Form::label('eye_status_yes', 'Yes') }}
                                    {{ Form::radio('eye_status', 2) }}
                                    {{ Form::label('eye_status_no', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('eye_description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                                </td>
                            </tr>
            
                        </tbody>
                    </table>
                </div>
                <div class="row gx-10 mb-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('systemic_examination', 'Systemic Examination:', ['class' => 'form-label']) }}
                                {{ Form::textarea('systemic_examination', null, ['class' => 'form-control', 'rows' => 3, 'tabindex' => '2', 'placeholder' => 'Systemic Examination']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary me-3','id'=>'btnSystemicExaminationSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" id="btnSystemicExaminationCancel"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
