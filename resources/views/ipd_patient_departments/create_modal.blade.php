<div id="createIpdPatientModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.ipd_patient.new_ipd_patient') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @include('ipd_patient_departments.create_modal_content')
        </div>
    </div>
</div>
