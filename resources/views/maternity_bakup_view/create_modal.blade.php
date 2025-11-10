<div class="modal fade" id="createMaternityModal" tabindex="-1" aria-labelledby="createMaternityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMaternityModalLabel">New Maternity Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="maternityValidationErrorsBox"></div>
                @include('maternity.create_modal_content', ['doctors' => $doctors ?? [], 'data' => $data ?? []])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveMaternityBtn">{{ __('messages.common.save') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
