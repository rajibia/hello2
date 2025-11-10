<div id="show_general_examination_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <style>
        #show_general_examination_modal input[type="radio"]:disabled:checked {
            opacity: 1;
            background-color: #3269a8;
            border-color: #3269a8;
            box-shadow: 0 0 0 0.2rem rgba(50, 105, 168, 0.25);
            appearance: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
            position: relative;
            margin-left: 5px;
        }
        #show_general_examination_modal input[type="radio"]:disabled:not(:checked) {
            opacity: 0.5;
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid #adb5bd;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
            background-color: #fff;
        }
    </style>
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>View Examination</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'viewGeneralExaminationForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="viewGeneralExaminationErrorsBox"></div>
               
                {{ Form::hidden('general_examination_id', null, ['id' => 'viewGeneralExaminationId']) }}

                <div class="row gx-10 mb-5">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mb-5">
                            <div class="mb-5">
                                {{ Form::label('general_examination', 'Examination Comment:', ['class' => 'form-label']) }}
                                <div class="" id="opdGeneralExaminationView"></div>
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
                                    {{ Form::radio('lungs_status', 0, false, ['id' => 'opdLungsStatusView0', 'disabled']) }}
                                    {{ Form::label('opdLungsStatusView0', 'Unknown') }}
                                    {{ Form::radio('lungs_status', 1, false, ['id' => 'opdLungsStatusView1', 'disabled']) }}
                                    {{ Form::label('opdLungsStatusView1', 'Yes') }}
                                    {{ Form::radio('lungs_status', 2, false, ['id' => 'opdLungsStatusView2', 'disabled']) }}
                                    {{ Form::label('opdLungsStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('lungs_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdLungsDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Cardio</td>
                                <td>
                                    {{ Form::radio('cardio_status', 0, false, ['id' => 'cardioStatusView0', 'disabled']) }}
                                    {{ Form::label('cardioStatusView0', 'Unknown') }}
                                    {{ Form::radio('cardio_status', 1, false, ['id' => 'cardioStatusView1', 'disabled']) }}
                                    {{ Form::label('cardioStatusView1', 'Yes') }}
                                    {{ Form::radio('cardio_status', 2, false, ['id' => 'cardioStatusView2', 'disabled']) }}
                                    {{ Form::label('cardioStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('cardio_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdCardioDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Abdomen</td>
                                <td>
                                    {{ Form::radio('abdomen_status', 0, false, ['id' => 'abdomenStatusView0', 'disabled']) }}
                                    {{ Form::label('abdomenStatusView0', 'Unknown') }}
                                    {{ Form::radio('abdomen_status', 1, false, ['id' => 'abdomenStatusView1', 'disabled']) }}
                                    {{ Form::label('abdomenStatusView1', 'Yes') }}
                                    {{ Form::radio('abdomen_status', 2, false, ['id' => 'abdomenStatusView2', 'disabled']) }}
                                    {{ Form::label('abdomenStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('abdomen_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdAbdomenDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Ear</td>
                                <td>
                                    {{ Form::radio('ear_status', 0, false, ['id' => 'earStatusView0', 'disabled']) }}
                                    {{ Form::label('earStatusView0', 'Unknown') }}
                                    {{ Form::radio('ear_status', 1, false, ['id' => 'earStatusView1', 'disabled']) }}
                                    {{ Form::label('earStatusView1', 'Yes') }}
                                    {{ Form::radio('ear_status', 2, false, ['id' => 'earStatusView2', 'disabled']) }}
                                    {{ Form::label('earStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('ear_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdEarDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nose</td>
                                <td>
                                    {{ Form::radio('nose_status', 0, false, ['id' => 'noseStatusView0', 'disabled']) }}
                                    {{ Form::label('noseStatusView0', 'Unknown') }}
                                    {{ Form::radio('nose_status', 1, false, ['id' => 'noseStatusView1', 'disabled']) }}
                                    {{ Form::label('noseStatusView1', 'Yes') }}
                                    {{ Form::radio('nose_status', 2, false, ['id' => 'noseStatusView2', 'disabled']) }}
                                    {{ Form::label('noseStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nose_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdNoseDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Throat</td>
                                <td>
                                    {{ Form::radio('throat_status', 0, false, ['id' => 'throatStatusView0', 'disabled']) }}
                                    {{ Form::label('throatStatusView0', 'Unknown') }}
                                    {{ Form::radio('throat_status', 1, false, ['id' => 'throatStatusView1', 'disabled']) }}
                                    {{ Form::label('throatStatusView1', 'Yes') }}
                                    {{ Form::radio('throat_status', 2, false, ['id' => 'throatStatusView2', 'disabled']) }}
                                    {{ Form::label('throatStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('throat_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdThroatDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Muscles</td>
                                <td>
                                    {{ Form::radio('musco_status', 0, false, ['id' => 'muscoStatusView0', 'disabled']) }}
                                    {{ Form::label('muscoStatusView0', 'Unknown') }}
                                    {{ Form::radio('musco_status', 1, false, ['id' => 'muscoStatusView1', 'disabled']) }}
                                    {{ Form::label('muscoStatusView1', 'Yes') }}
                                    {{ Form::radio('musco_status', 2, false, ['id' => 'muscoStatusView2', 'disabled']) }}
                                    {{ Form::label('muscoStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('musco_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdMuscoDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nervous</td>
                                <td>
                                    {{ Form::radio('nervous_status', 0, false, ['id' => 'nervousStatusView0', 'disabled']) }}
                                    {{ Form::label('nervousStatusView0', 'Unknown') }}
                                    {{ Form::radio('nervous_status', 1, false, ['id' => 'nervousStatusView1', 'disabled']) }}
                                    {{ Form::label('nervousStatusView1', 'Yes') }}
                                    {{ Form::radio('nervous_status', 2, false, ['id' => 'nervousStatusView2', 'disabled']) }}
                                    {{ Form::label('nervousStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('nervous_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdNervousDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Skin</td>
                                <td>
                                    {{ Form::radio('skin_status', 0, false, ['id' => 'skinStatusView0', 'disabled']) }}
                                    {{ Form::label('skinStatusView0', 'Unknown') }}
                                    {{ Form::radio('skin_status', 1, false, ['id' => 'skinStatusView1', 'disabled']) }}
                                    {{ Form::label('skinStatusView1', 'Yes') }}
                                    {{ Form::radio('skin_status', 2, false, ['id' => 'skinStatusView2', 'disabled']) }}
                                    {{ Form::label('skinStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('skin_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdSkinDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Eye</td>
                                <td>
                                    {{ Form::radio('eye_status', 0, false, ['id' => 'eyeStatusView0', 'disabled']) }}
                                    {{ Form::label('eyeStatusView0', 'Unknown') }}
                                    {{ Form::radio('eye_status', 1, false, ['id' => 'eyeStatusView1', 'disabled']) }}
                                    {{ Form::label('eyeStatusView1', 'Yes') }}
                                    {{ Form::radio('eye_status', 2, false, ['id' => 'eyeStatusView2', 'disabled']) }}
                                    {{ Form::label('eyeStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('eye_description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'id' => 'opdEyeDescriptionView', 'disabled']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                

                <div class="d-flex justify-content-end">
                    <button type="button" id="btnViewGeneralExaminationCancel"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
