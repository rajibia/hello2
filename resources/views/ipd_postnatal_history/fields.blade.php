<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('ipd.postnatal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="ipd_id" value="{{ $ipdId }}">
            <h3 class="card-title mb-3">{{ __('messages.postnatal.title') }}</h3>
            <div class="row">
                <!-- Patient ID -->
                <div class="col-md-3 mb-3">
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
                
                <!-- Labour Time -->
                <div class="col-md-3 mb-3">
                    <label for="labour_time" class="form-label">{{ __('messages.postnatal.labour_time') }}</label>
                    <input 
                        type="time" 
                        class="form-control" 
                        id="labour_time" 
                        name="labour_time" 
                    />
                </div>
                
                <!-- Delivery Time -->
                <div class="col-md-3 mb-3">
                    <label for="delivery_time" class="form-label">{{ __('messages.postnatal.delivery_time') }}</label>
                    <input 
                        type="time" 
                        class="form-control" 
                        id="delivery_time" 
                        name="delivery_time" 
                    />
                </div>
                
                <!-- Routine Question -->
                <div class="col-md-3 mb-3">
                    <label for="routine_question" class="form-label">{{ __('messages.postnatal.routine_question') }}</label>
                    <textarea 
                        class="form-control" 
                        id="routine_question" 
                        name="routine_question" 
                        rows="2">
                    </textarea>
                </div>

                <!-- General Remark -->
                <div class="col-md-12 mb-3">
                    <label for="general_remark" class="form-label">{{ __('messages.postnatal.general_remark') }}</label>
                    <textarea 
                        class="form-control" 
                        id="general_remark" 
                        name="general_remark" 
                        rows="3">
                    </textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
