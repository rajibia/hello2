@if(request()->query('filter') !== 'upcoming')
<div class="me-3">
   <input class="form-control custom-width" id="time_range-maternity" /><b class="caret"></b>
</div>
@endif

@php
    // Get the necessary data for the modal
    $maternityRepository = App\Repositories\MaternityRepository::class;
    $maternityRepository = app($maternityRepository);
    $data = $maternityRepository->getAssociatedData();
    $data['maternityNumber'] = 'MAT-' . random_int(100000, 999999);
    $doctors = $data['doctors'];
@endphp

@modulePermission('maternity', 'add')
<button type="button" class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#createMaternityPatientModal">
    {{ __('messages.maternity_patient.new_maternity_patient') }}
</button>
@endmodulePermission

@include('maternity.create_modal', ['doctors' => $doctors])
@include('maternity.maternity_modal_js')
