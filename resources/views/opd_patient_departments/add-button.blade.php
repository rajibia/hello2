@if(request()->query('filter') !== 'upcoming')
<div class="me-3">
   <input class="form-control custom-width" id="time_range-opd" /><b class="caret"></b>
</div>
@endif

@php
    // Get the necessary data for the modal
    $opdPatientDepartmentRepo = App\Repositories\OpdPatientDepartmentRepository::class;
    $opdPatientDepartmentRepo = app($opdPatientDepartmentRepo);
    $data = $opdPatientDepartmentRepo->getAssociatedData();
    $data['opdNumber'] = 'OPD-' . random_int(100000, 999999);
    $doctors = $data['doctors'];
@endphp
@modulePermission('opds', 'add')
<button type="button" class="btn btn-primary" 
        data-bs-toggle="modal" 
        data-bs-target="#createOpdPatientModal">
    {{ __('messages.opd_patient.new_opd_patient') }}
</button>
@endmodulePermission

@include('opd_patient_departments.create_modal', ['doctors' => $doctors])
@include('opd_patient_departments.opd_modal_js')
