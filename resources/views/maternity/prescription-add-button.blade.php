@aware(['component'])

@php
    $maternityId = $component->maternityId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $maternityId != null)
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
        New Prescription
    </button>
@endif
