<div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="poverview" role="tabpanel">
            <div class="card">
                <div>
                    <div class="card-body">
                        <div class="row gx-10 mb-5">
                            <div class="col-md-4 col-12 mb-6 mb-lg-0 d-flex align-items-center justify-content-start">
                                <h4>Systemic Examination # <span class="text-gray-500">{{ $systemic_examination->examination_number ?? '' }}</span></h4>
                            </div>
                            @if ($systemic_examination->patient_id != '')
                                <div class="form-group col-md-4 mb-5">
                                    {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
                                    <span class="required"></span>
                                    {{ Form::select('patient_id', $patients, $systemic_examination->patient_id ?? null, ['class' => 'form-select', 'disabled' => 'disabled', 'required', 'id' => 'vitalsPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                                </div>
                            @endif

                            @if ($systemic_examination->opd_id != '')
                                <div class="form-group col-md-4 mb-5">
                                    {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
                                    <span class="required"></span>
                                    {{ Form::select('opd_id', $opds, $systemic_examination->opd_id ?? null, ['class' => 'form-select', 'disabled' => 'disabled', 'required', 'id' => 'vitalsOPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                                </div>
                            @endif

                            @if ($systemic_examination->ipd_id != '')
                                <div class="form-group col-md-4 mb-5">
                                    {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                                    <span class="required"></span>
                                    {{ Form::select('ipd_id', $ipds, $systemic_examination->ipd_id ?? null, ['class' => 'form-select', 'disabled' => 'disabled', 'required', 'id' => 'vitalsIPDId', 'placeholder' => __('messages.document.select_patient')]) }}
                                </div>
                            @endif

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
                                                {{ Form::radio('lungs_status', 0, $systemic_examination->lungs_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('lungs_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('lungs_status', 1, $systemic_examination->lungs_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('lungs_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('lungs_status', 2, $systemic_examination->lungs_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('lungs_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('lungs_description', $systemic_examination->lungs_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Cardio</td>
                                            <td>
                                                {{ Form::radio('cardio_status', 0, $systemic_examination->cardio_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('cardio_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('cardio_status', 1, $systemic_examination->cardio_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('cardio_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('cardio_status', 2, $systemic_examination->cardio_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('cardio_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('cardio_description', $systemic_examination->cardio_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Abdomen</td>
                                            <td>
                                                {{ Form::radio('abdomen_status', 0, $systemic_examination->abdomen_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('abdomen_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('abdomen_status', 1, $systemic_examination->abdomen_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('abdomen_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('abdomen_status', 2, $systemic_examination->abdomen_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('abdomen_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('abdomen_description', $systemic_examination->abdomen_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Ear</td>
                                            <td>
                                                {{ Form::radio('ear_status', 0, $systemic_examination->ear_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('ear_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('ear_status', 1, $systemic_examination->ear_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('ear_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('ear_status', 2, $systemic_examination->ear_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('ear_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('ear_description', $systemic_examination->ear_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Nose</td>
                                            <td>
                                                {{ Form::radio('nose_status', 0, $systemic_examination->nose_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nose_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('nose_status', 1, $systemic_examination->nose_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nose_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('nose_status', 2, $systemic_examination->nose_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nose_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('nose_description', $systemic_examination->nose_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Throat</td>
                                            <td>
                                                {{ Form::radio('throat_status', 0, $systemic_examination->throat_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('throat_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('throat_status', 1, $systemic_examination->throat_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('throat_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('throat_status', 2, $systemic_examination->throat_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('throat_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('throat_description', $systemic_examination->throat_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Muscles</td>
                                            <td>
                                                {{ Form::radio('musco_status', 0, $systemic_examination->musco_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('musco_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('musco_status', 1, $systemic_examination->musco_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('musco_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('musco_status', 2, $systemic_examination->musco_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('musco_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('musco_description', $systemic_examination->musco_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Nervous</td>
                                            <td>
                                                {{ Form::radio('nervous_status', 0, $systemic_examination->nervous_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nervous_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('nervous_status', 1, $systemic_examination->nervous_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nervous_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('nervous_status', 2, $systemic_examination->nervous_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('nervous_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('nervous_description', $systemic_examination->nervous_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Skin</td>
                                            <td>
                                                {{ Form::radio('skin_status', 0, $systemic_examination->skin_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('skin_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('skin_status', 1, $systemic_examination->skin_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('skin_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('skin_status', 2, $systemic_examination->skin_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('skin_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('skin_description', $systemic_examination->skin_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Eye</td>
                                            <td>
                                                {{ Form::radio('eye_status', 0, $systemic_examination->eye_status === 0, ['disabled' => 'disabled']) }}
                                                {{ Form::label('eye_status_unknown', 'Unknown', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('eye_status', 1, $systemic_examination->eye_status === 1, ['disabled' => 'disabled']) }}
                                                {{ Form::label('eye_status_yes', 'Yes', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                                {{ Form::radio('eye_status', 2, $systemic_examination->eye_status === 2, ['disabled' => 'disabled']) }}
                                                {{ Form::label('eye_status_no', 'No', ['class' => 'form-label', 'disabled' => 'disabled']) }}
                                            </td>
                                            <td>
                                                {{ Form::text('eye_description', $systemic_examination->eye_description, ['class' => 'form-control', 'placeholder' => 'Description', 'required', 'disabled' => 'disabled']) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
