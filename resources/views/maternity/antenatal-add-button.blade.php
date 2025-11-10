@aware(['component'])

@php
    $patientId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $patientId != null)
    <a href="/antenatal-create?ref_ipd_id={{ $patientId }}" class="btn btn-primary">
        New Antenatal
    </a>
@endif
