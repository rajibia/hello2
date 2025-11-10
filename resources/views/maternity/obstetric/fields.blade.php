<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('maternity.obstetric.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patientId }}">
            <h3 class="card-title mb-3">Previous Obstetric History</h3>
            <div class="row">
                <!-- Row 1 -->
                <div class="col-md-6 mb-3">
                    <label for="place_of_delivery" class="form-label">Place of Delivery</label>
                    <input
                        type="text"
                        class="form-control"
                        id="place_of_delivery"
                        name="place_of_delivery"
                        value="{{ old('place_of_delivery') }}"
                        placeholder="Enter place of delivery"
                    />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="duration_of_pregnancy" class="form-label">Duration of Pregnancy</label>
                    <input
                        type="text"
                        class="form-control"
                        id="duration_of_pregnancy"
                        name="duration_of_pregnancy"
                        value="{{ old('duration_of_pregnancy') }}"
                        placeholder="e.g., 38 weeks"
                    />
                </div>

                <!-- Row 2 -->
                <div class="col-md-6 mb-3">
                    <label for="complication_in_pregnancy_or_puerperium" class="form-label">Complications in Pregnancy or Puerperium</label>
                    <textarea
                        class="form-control"
                        id="complication_in_pregnancy_or_puerperium"
                        name="complication_in_pregnancy_or_puerperium"
                        rows="3"
                        placeholder="Enter any complications..."
                    >{{ old('complication_in_pregnancy_or_puerperium') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="birth_weight" class="form-label">Birth Weight</label>
                    <input
                        type="text"
                        class="form-control"
                        id="birth_weight"
                        name="birth_weight"
                        value="{{ old('birth_weight') }}"
                        placeholder="e.g., 3.2 kg"
                    />
                </div>

                <!-- Row 3 -->
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="infant_feeding" class="form-label">Infant Feeding</label>
                    <select class="form-control" id="infant_feeding" name="infant_feeding">
                        <option value="">Select Feeding Method</option>
                        <option value="Breastfeeding" {{ old('infant_feeding') == 'Breastfeeding' ? 'selected' : '' }}>Breastfeeding</option>
                        <option value="Bottle Feeding" {{ old('infant_feeding') == 'Bottle Feeding' ? 'selected' : '' }}>Bottle Feeding</option>
                        <option value="Mixed" {{ old('infant_feeding') == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>

                <!-- Row 4 -->
                <div class="col-md-6 mb-3">
                    <label for="birth_status" class="form-label">Birth Status</label>
                    <select class="form-control" id="birth_status" name="birth_status">
                        <option value="">Select Birth Status</option>
                        <option value="Alive" {{ old('birth_status') == 'Alive' ? 'selected' : '' }}>Alive</option>
                        <option value="Stillborn" {{ old('birth_status') == 'Stillborn' ? 'selected' : '' }}>Stillborn</option>
                        <option value="Died" {{ old('birth_status') == 'Died' ? 'selected' : '' }}>Died</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alive" class="form-label">Alive</label>
                    <select class="form-control" id="alive" name="alive">
                        <option value="">Select</option>
                        <option value="1" {{ old('alive') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('alive') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Row 5 -->
                <div class="col-md-6 mb-3">
                    <label for="alive_or_dead_date" class="form-label">Alive or Dead Date</label>
                    <input
                        type="date"
                        class="form-control"
                        id="alive_or_dead_date"
                        name="alive_or_dead_date"
                        value="{{ old('alive_or_dead_date') }}"
                    />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="previous_medical_history" class="form-label">Previous Medical History</label>
                    <textarea
                        class="form-control"
                        id="previous_medical_history"
                        name="previous_medical_history"
                        rows="3"
                        placeholder="Enter previous medical history..."
                    >{{ old('previous_medical_history') }}</textarea>
                </div>

                <!-- Row 6 -->
                <div class="col-md-12 mb-3">
                    <label for="special_instruction" class="form-label">Special Instruction</label>
                    <textarea
                        class="form-control"
                        id="special_instruction"
                        name="special_instruction"
                        rows="3"
                        placeholder="Enter any special instructions..."
                    >{{ old('special_instruction') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
