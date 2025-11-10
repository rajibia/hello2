@if(request()->query('filter') !== 'upcoming')
<div class="me-3">
   <input class="form-control custom-width" id="time_range-maternity" /><b class="caret"></b>
</div>
@endif

@php
    // Get the necessary data for the modal
    $maternityRepo = App\Repositories\MaternityRepository::class;
    $maternityRepo = app($maternityRepo);
    $data = $maternityRepo->getAssociatedData();
    $data['maternityNumber'] = 'MAT-' . random_int(100000, 999999);
    $doctors = $data['doctors'];
@endphp

<button type="button" class="btn btn-primary" 
        data-bs-toggle="modal" 
        data-bs-target="#createMaternityModal">
    {{ __('messages.maternity.new_maternity_patient') }}
</button>

@include('maternity.create_modal', ['doctors' => $doctors])
@include('maternity.maternity_modal_js')
