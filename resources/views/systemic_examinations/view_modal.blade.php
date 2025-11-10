<div id="show_systemic_examination_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
<style>
        #show_systemic_examination_modal input[type="radio"]:disabled:checked {
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
        #show_systemic_examination_modal input[type="radio"]:disabled:not(:checked) {
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
                <h2>View Systemic Examination</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                    {{ Form::text('opdLungsDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Cardio</td>
                                <td>
                                    {{ Form::radio('cardio_status', 0, false, ['id' => 'opdCardioStatusView0', 'disabled']) }}
                                    {{ Form::label('opdCardioStatusView0', 'Unknown') }}
                                    {{ Form::radio('cardio_status', 1, false, ['id' => 'opdCardioStatusView1', 'disabled']) }}
                                    {{ Form::label('opdCardioStatusView1', 'Yes') }}
                                    {{ Form::radio('cardio_status', 2, false, ['id' => 'opdCardioStatusView2', 'disabled']) }}
                                    {{ Form::label('opdCardioStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdCardioDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Abdomen</td>
                                <td>
                                    {{ Form::radio('abdomen_status', 0, false, ['id' => 'opdAbdomenStatusView0', 'disabled']) }}
                                    {{ Form::label('opdAbdomenStatusView0', 'Unknown') }}
                                    {{ Form::radio('abdomen_status', 1, false, ['id' => 'opdAbdomenStatusView1', 'disabled']) }}
                                    {{ Form::label('opdAbdomenStatusView1', 'Yes') }}
                                    {{ Form::radio('abdomen_status', 2, false, ['id' => 'opdAbdomenStatusView2', 'disabled']) }}
                                    {{ Form::label('opdAbdomenStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdAbdomenDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Ear</td>
                                <td>
                                    {{ Form::radio('ear_status', 0, false, ['id' => 'opdEarStatusView0', 'disabled']) }}
                                    {{ Form::label('opdEarStatusView0', 'Unknown') }}
                                    {{ Form::radio('ear_status', 1, false, ['id' => 'opdEarStatusView1', 'disabled']) }}
                                    {{ Form::label('opdEarStatusView1', 'Yes') }}
                                    {{ Form::radio('ear_status', 2, false, ['id' => 'opdEarStatusView2', 'disabled']) }}
                                    {{ Form::label('opdEarStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdEarDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nose</td>
                                <td>
                                    {{ Form::radio('nose_status', 0, false, ['id' => 'opdNoseStatusView0', 'disabled']) }}
                                    {{ Form::label('opdNoseStatusView0', 'Unknown') }}
                                    {{ Form::radio('nose_status', 1, false, ['id' => 'opdNoseStatusView1', 'disabled']) }}
                                    {{ Form::label('opdNoseStatusView1', 'Yes') }}
                                    {{ Form::radio('nose_status', 2, false, ['id' => 'opdNoseStatusView2', 'disabled']) }}
                                    {{ Form::label('opdNoseStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdNoseDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Throat</td>
                                <td>
                                    {{ Form::radio('throat_status', 0, false, ['id' => 'opdThroatStatusView0', 'disabled']) }}
                                    {{ Form::label('opdThroatStatusView0', 'Unknown') }}
                                    {{ Form::radio('throat_status', 1, false, ['id' => 'opdThroatStatusView1', 'disabled']) }}
                                    {{ Form::label('opdThroatStatusView1', 'Yes') }}
                                    {{ Form::radio('throat_status', 2, false, ['id' => 'opdThroatStatusView2', 'disabled']) }}
                                    {{ Form::label('opdThroatStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdThroatDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Muscles</td>
                                <td>
                                    {{ Form::radio('musco_status', 0, false, ['id' => 'opdMuscoStatusView0', 'disabled']) }}
                                    {{ Form::label('opdMuscoStatusView0', 'Unknown') }}
                                    {{ Form::radio('musco_status', 1, false, ['id' => 'opdMuscoStatusView1', 'disabled']) }}
                                    {{ Form::label('opdMuscoStatusView1', 'Yes') }}
                                    {{ Form::radio('musco_status', 2, false, ['id' => 'opdMuscoStatusView2', 'disabled']) }}
                                    {{ Form::label('opdMuscoStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdMuscoDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Nervous</td>
                                <td>
                                    {{ Form::radio('nervous_status', 0, false, ['id' => 'opdNervousStatusView0', 'disabled']) }}
                                    {{ Form::label('opdNervousStatusView0', 'Unknown') }}
                                    {{ Form::radio('nervous_status', 1, false, ['id' => 'opdNervousStatusView1', 'disabled']) }}
                                    {{ Form::label('opdNervousStatusView1', 'Yes') }}
                                    {{ Form::radio('nervous_status', 2, false, ['id' => 'opdNervousStatusView2', 'disabled']) }}
                                    {{ Form::label('opdNervousStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdNervousDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Skin</td>
                                <td>
                                    {{ Form::radio('skin_status', 0, false, ['id' => 'opdSkinStatusView0', 'disabled']) }}
                                    {{ Form::label('opdSkinStatusView0', 'Unknown') }}
                                    {{ Form::radio('skin_status', 1, false, ['id' => 'opdSkinStatusView1', 'disabled']) }}
                                    {{ Form::label('opdSkinStatusView1', 'Yes') }}
                                    {{ Form::radio('skin_status', 2, false, ['id' => 'opdSkinStatusView2', 'disabled']) }}
                                    {{ Form::label('opdSkinStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdSkinDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                
                            <tr>
                                <td>Eye</td>
                                <td>
                                    {{ Form::radio('eye_status', 0, false, ['id' => 'opdEyeStatusView0', 'disabled']) }}
                                    {{ Form::label('opdEyeStatusView0', 'Unknown') }}
                                    {{ Form::radio('eye_status', 1, false, ['id' => 'opdEyeStatusView1', 'disabled']) }}
                                    {{ Form::label('opdEyeStatusView1', 'Yes') }}
                                    {{ Form::radio('eye_status', 2, false, ['id' => 'opdEyeStatusView2', 'disabled']) }}
                                    {{ Form::label('opdEyeStatusView2', 'No') }}
                                </td>
                                <td>
                                    {{ Form::text('opdEyeDescriptionViewSystemic', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h3> Systemic Examination </h3>
                        <p id="opdSystemicExaminationView"></p>
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" aria-label="Close" id="cancelEditopdDiagnosis" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
