@aware(['component'])

@php
    $patientId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $patientId != null)
    <a href="/maternity-obstetric/create?patient_id={{ $patientId }}" class="btn btn-success">
        Add Previous Obstetric History
    </a>
@endif
