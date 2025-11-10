<div class="me-3">
   <input class="form-control custom-width" id="time_range-opd" /><b class="caret"></b>
</div>

<div class="d-flex align-items-center">
    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createIpdPatientModal">
        {{ __('messages.ipd_patient.new_ipd_patient') }}
    </a>
</div>
@include('ipd_patient_departments.create_modal')
@include('ipd_patient_departments.ipd_modal_js')