@aware(['component'])

@php
    $patientId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $patientId != null)
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNursingNoteModal">
        Add Nursing Progress Notes
    </button>
@endif
