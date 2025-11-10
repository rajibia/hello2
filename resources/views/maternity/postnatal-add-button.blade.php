@aware(['component'])

@php
    $patientId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $patientId != null)
    <a href="/maternity-postnatal/create?patient_id={{ $patientId }}" class="btn btn-primary">
        New Postnatal
    </a>
@endif
