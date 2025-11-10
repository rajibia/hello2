<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('maternity.postnatal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patientId }}">
            <h3 class="card-title mb-3">Postnatal History</h3>
            <div class="row">
                <!-- Row 1 -->
                <div class="col-md-6 mb-3">
                    <label for="labour_time" class="form-label">Labour Time</label>
                    <input
                        type="time"
                        class="form-control"
                        id="labour_time"
                        name="labour_time"
                        value="{{ old('labour_time') }}"
                    />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="delivery_time" class="form-label">Delivery Time</label>
                    <input
                        type="time"
                        class="form-control"
                        id="delivery_time"
                        name="delivery_time"
                        value="{{ old('delivery_time') }}"
                    />
                </div>

                <!-- Row 2 -->
                <div class="col-md-12 mb-3">
                    <label for="routine_question" class="form-label">Routine Question</label>
                    <textarea
                        class="form-control"
                        id="routine_question"
                        name="routine_question"
                        rows="4"
                        placeholder="Enter routine questions and answers..."
                    >{{ old('routine_question') }}</textarea>
                </div>

                <!-- Row 3 -->
                <div class="col-md-12 mb-3">
                    <label for="general_remark" class="form-label">General Remark</label>
                    <textarea
                        class="form-control"
                        id="general_remark"
                        name="general_remark"
                        rows="4"
                        placeholder="Enter general remarks..."
                    >{{ old('general_remark') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
