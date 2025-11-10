<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('ipd.obstetric.store') }}" method="POST">
            @csrf
            <input type="hidden" name="ipd_id" value="{{ $ipdId }}">
            <h3 class="card-title mb-3">{{ __('messages.previous_obstetric_history.previous_obstetric_history') }}</h3>
            <div class="row">
                <!-- Patient ID -->
                <div class="col-md-6 mb-3">
                    <label for="patient_id" class="form-label">{{ __('messages.postnatal.patient_id') }}</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="patient_id" 
                        name="patient_id" 
                        value="{{ $patientId }}" 
                        readonly 
                    />
                </div>

                <!-- Place of Delivery -->
                <div class="col-md-6 mb-3">
                    <label for="place_of_delivery" class="form-label">{{ __('messages.previous_obstetric_history.place_of_delivery') }}</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="place_of_delivery" 
                        name="place_of_delivery" 
                    />
                </div>

                <!-- Duration of Pregnancy -->
                <div class="col-md-6 mb-3">
                    <label for="duration_of_pregnancy" class="form-label">{{ __('messages.previous_obstetric_history.duration_of_pregnancy') }}</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="duration_of_pregnancy" 
                        name="duration_of_pregnancy" 
                    />
                </div>

                <!-- Complications -->
                <div class="col-md-6 mb-3">
                    <label for="complications" class="form-label">{{ __('messages.previous_obstetric_history.complications') }}</label>
                    <textarea 
                        class="form-control" 
                        id="complications" 
                        name="complications" 
                        rows="2">
                    </textarea>
                </div>

                <!-- Birth Weight -->
                <div class="col-md-6 mb-3">
                    <label for="birth_weight" class="form-label">{{ __('messages.previous_obstetric_history.birth_weight') }}</label>
                    <input 
                        type="number" 
                        step="0.1" 
                        class="form-control" 
                        id="birth_weight" 
                        name="birth_weight" 
                    />
                </div>

                <!-- Gender -->
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">{{ __('messages.previous_obstetric_history.gender') }}</label>
                    <select 
                        class="form-select" 
                        id="gender" 
                        name="gender">
                        <option value="0">{{ __('messages.user.male') }}</option>
                        <option value="1">{{ __('messages.user.female') }}</option>
                    </select>
                </div>

                <!-- Infant Feeding -->
                <div class="col-md-6 mb-3">
                    <label for="infant_feeding" class="form-label">{{ __('messages.previous_obstetric_history.infant_feeding') }}</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="infant_feeding" 
                        name="infant_feeding" 
                    />
                </div>

                <!-- Birth Status -->
                <div class="col-md-6 mb-3">
                    <label for="birth_status" class="form-label">{{ __('messages.previous_obstetric_history.birth_status') }}</label>
                    <select 
                        class="form-select" 
                        id="birth_status" 
                        name="birth_status">
                        <option value="1">{{ __('messages.previous_obstetric_history.alive') }}</option>
                        <option value="0">{{ __('messages.previous_obstetric_history.dead') }}</option>
                    </select>
                </div>

                <!-- Alive / Dead Date -->
                <div class="col-md-6 mb-3">
                    <label for="alive_or_dead_date" class="form-label">{{ __('messages.previous_obstetric_history.alive_or_dead_date') }}</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="alive_or_dead_date" 
                        name="alive_or_dead_date" 
                    />
                </div>

                <!-- Previous Medical History -->
                <div class="col-md-6 mb-3">
                    <label for="previous_medical_history" class="form-label">{{ __('messages.previous_obstetric_history.previous_medical_history') }}</label>
                    <textarea 
                        class="form-control" 
                        id="previous_medical_history" 
                        name="previous_medical_history" 
                        rows="2">
                    </textarea>
                </div>

                <!-- Special Instruction -->
                <div class="col-md-12 mb-3">
                    <label for="special_instruction" class="form-label">{{ __('messages.previous_obstetric_history.special_instruction') }}</label>
                    <textarea 
                        class="form-control" 
                        id="special_instruction" 
                        name="special_instruction" 
                        rows="3">
                    </textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
            </div>
        </form>
    </div>
</div>
