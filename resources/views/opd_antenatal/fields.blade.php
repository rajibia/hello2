<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('opd.antenatal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="opd_id" value="{{ $opdId }}">
            <h3 class="card-title mb-3">{{ __('messages.antenatal.title') }}</h3>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="patient_id" class="form-label">{{ __('messages.antenatal.patient_id') }}</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="patient_id" 
                        name="patient_id" 
                        value="{{ $patientId }}" 
                        readonly 
                    />
                </div>
                {{-- <div class="col-md-3 mb-3">
                    <label for="bleeding" class="form-label">{{ (__('messages.antenatal.bleeding')) }}</label>
                    <input class="form-control" id="bleeding" name="bleeding" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="headache" class="form-label">{{ (__('messages.antenatal.headache')) }}</label>
                    <input class="form-control" id="headache" name="headache" rows="2" />
                </div> --}}
                <div class="col-md-3 mb-3">
                    <label for="bleeding" class="form-label">{{ __('messages.antenatal.bleeding') }}</label>
                    <select class="form-control" id="bleeding" name="bleeding">
                        <option value="">Select</option>
                        <option value="1">{{ __('Yes') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="headache" class="form-label">{{ __('messages.antenatal.headache') }}</label>
                    <select class="form-control" id="headache" name="headache">
                        <option value="">Select</option>
                        <option value="1">{{ __('Yes') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="pain" class="form-label">{{ (__('messages.antenatal.pain')) }}</label>
                    <input class="form-control" id="pain" name="pain" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="constipation" class="form-label">{{ (__('messages.antenatal.constipation')) }}</label>
                    <select class="form-control" id="constipation" name="constipation">
                        <option value="">Select</i> </option>
                        <option value="1">{{ __('Yes') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </select>
                </div>

                <!-- Row 2 -->
                <div class="col-md-3 mb-3">
                    <label for="urinary_symptoms" class="form-label">{{ (__('messages.antenatal.urinary_symptoms')) }}</label>
                    <input class="form-control" id="urinary_symptoms" name="urinary_symptoms" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vomiting" class="form-label">{{ (__('messages.antenatal.vomiting')) }}</label>
                    <select class="form-control" id="vomiting" name="vomiting">
                        <option value="">Select </option>
                        <option value="1">{{ __('Yes') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="cough" class="form-label">{{ (__('messages.antenatal.cough')) }}</label>
                    <select class="form-control" id="cough" name="cough">
                        <option value="">Select</option>
                        <option value="1">{{ __('Yes') }}</option>
                        <option value="0">{{ __('No') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vaginal_discharge" class="form-label">{{ (__('messages.antenatal.vaginal_discharge')) }}</label>
                    <input class="form-control" id="vaginal_discharge" name="vaginal_discharge" rows="2" />
                </div>

                <!-- Row 3 -->
                <div class="col-md-3 mb-3">
                    <label for="oedema" class="form-label">{{ (__('messages.antenatal.oedema')) }}</label>
                    <input class="form-control" id="oedema" name="oedema" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="haemorrhoids" class="form-label">{{ (__('messages.antenatal.haemorrhoids')) }}</label>
                    <input class="form-control" id="haemorrhoids" name="haemorrhoids" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date" class="form-label">{{ (__('messages.antenatal.date')) }}</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="condition" class="form-label">{{ (__('messages.antenatal.condition')) }}</label>
                    <input class="form-control" id="condition" name="condition" rows="2" />
                </div>

                <!-- Row 4 -->
                <div class="col-md-3 mb-3">
                    <label for="special_findings_and_remark" class="form-label">{{ (__('messages.antenatal.special_findings_and_remark')) }}</label>
                    <input class="form-control" id="special_findings_and_remark" name="special_findings_and_remark" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="pelvic_examination" class="form-label">{{ (__('messages.antenatal.pelvic_examination')) }}</label>
                    <input class="form-control" id="pelvic_examination" name="pelvic_examination" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sp" class="form-label">{{ (__('messages.antenatal.sp')) }}</label>
                    <input class="form-control" id="sp" name="sp" rows="2" />
                </div>
            </div>

            <hr> <!-- Divider between sections -->

            <!-- Section: Antenatal Examination -->
            <h4>Antenatal Examination</h4>
            <div class="row">
                <!-- Row 1 -->
                <div class="col-md-3 mb-3">
                    <label for="uter_size" class="form-label">{{ (__('messages.antenatal.uter_size')) }}</label>
                    <input class="form-control" id="uter_size" name="uter_size" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="uterus_size" class="form-label">{{ (__('messages.antenatal.uterus_size')) }}</label>
                    <input class="form-control" id="uterus_size" name="uterus_size" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="presentation_position" class="form-label">{{ (__('messages.antenatal.presentation_position')) }}</label>
                    <input class="form-control" id="presentation_position" name="presentation_position" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="presenting_part_to_brim" class="form-label">{{ (__('messages.antenatal.presenting_part_to_brim')) }}</label>
                    <input class="form-control" id="presenting_part_to_brim" name="presenting_part_to_brim" rows="2" />
                </div>

                <!-- Row 2 -->
                <div class="col-md-3 mb-3">
                    <label for="foetal_heart" class="form-label">{{ (__('messages.antenatal.foetal_heart')) }}</label>
                    <input class="form-control" id="foetal_heart" name="foetal_heart" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="blood_pressure" class="form-label">{{ (__('messages.antenatal.blood_pressure')) }}</label>
                    <input class="form-control" id="blood_pressure" name="blood_pressure" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="antenatal_oedema" class="form-label">{{ (__('messages.antenatal.antenatal_oedema')) }}</label>
                    <input class="form-control" id="antenatal_oedema" name="antenatal_oedema" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="urine_sugar" class="form-label">{{ (__('messages.antenatal.urine_sugar')) }}</label>
                    <input class="form-control" id="urine_sugar" name="urine_sugar" rows="2" />
                </div>

                <!-- Row 3 -->
                <div class="col-md-3 mb-3">
                    <label for="urine_albumin" class="form-label">{{ (__('messages.antenatal.urine_albumin')) }}</label>
                    <input class="form-control" id="urine_albumin" name="urine_albumin" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="antenatal_weight" class="form-label">{{ (__('messages.antenatal.antenatal_weight')) }}</label>
                    <input class="form-control" id="antenatal_weight" name="antenatal_weight" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="remark" class="form-label">{{ (__('messages.antenatal.remark')) }}</label>
                    <input class="form-control" id="remark" name="remark" rows="2" />
                </div>
                <div class="col-md-3 mb-3">
                    <label for="next_visit" class="form-label">{{ (__('messages.antenatal.next_visit')) }}</label>
                    <input type="date" class="form-control" id="next_visit" name="next_visit">
                </div>

                <!-- Row 4 -->
                <div class="col-md-12 mb-3">
                    <label for="previous_antenatal_details" class="form-label">{{ (__('messages.antenatal.previous_antenatal_details')) }}</label>
                    <textarea class="form-control" id="previous_antenatal_details" name="previous_antenatal_details" rows="3"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
