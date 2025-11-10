<div id="edit_postnatal_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="editModalLabel">{{ __('messages.postnatal.edit_title') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{ Form::open(['route' => 'ipd.postnatal.store', 'method' => 'POST', 'id' => 'editPostnatalForm']) }}
            <div class="modal-body">
                <input type="hidden" name="ipd_id" id="edit_ipd_id" value="">

                <div class="row">
                    <!-- Patient ID -->
                    <div class="col-md-3 mb-3">
                        <label for="patient_id" class="form-label">{{ __('messages.postnatal.patient_id') }}</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="edit_patient_id" 
                            name="patient_id" 
                            value="" 
                            readonly 
                        />
                    </div>
                    
                    <!-- Labour Time -->
                    <div class="col-md-3 mb-3">
                        <label for="labour_time" class="form-label">{{ __('messages.postnatal.labour_time') }}</label>
                        <input 
                            type="time" 
                            class="form-control" 
                            id="edit_labour_time" 
                            name="labour_time" 
                        />
                    </div>
                    
                    <!-- Delivery Time -->
                    <div class="col-md-3 mb-3">
                        <label for="delivery_time" class="form-label">{{ __('messages.postnatal.delivery_time') }}</label>
                        <input 
                            type="time" 
                            class="form-control" 
                            id="edit_delivery_time" 
                            name="delivery_time" 
                        />
                    </div>
                    
                    <!-- Routine Question -->
                    <div class="col-md-3 mb-3">
                        <label for="routine_question" class="form-label">{{ __('messages.postnatal.routine_question') }}</label>
                        <textarea 
                            class="form-control" 
                            id="edit_routine_question" 
                            name="routine_question" 
                            rows="2">
                        </textarea>
                    </div>

                    <!-- General Remark -->
                    <div class="col-md-12 mb-3">
                        <label for="general_remark" class="form-label">{{ __('messages.postnatal.general_remark') }}</label>
                        <textarea 
                            class="form-control" 
                            id="edit_general_remark" 
                            name="general_remark" 
                            rows="3">
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer pt-0">
                <button type="submit" class="btn btn-primary" id="editPostnatalSubmitBtn" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                    {{ __('messages.common.save') }}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
