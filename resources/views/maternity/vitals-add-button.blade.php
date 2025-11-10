@aware(['component'])

@php
    $patientId = $component->ipdId;
    $maternityId = $component->maternityId ?? null;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $patientId != null)
    <a href="/vitals/create?ref_ipd_id={{ $patientId }}&ref_maternity_id={{ $maternityId }}" class="btn btn-success">
        New Vitals
    </a>
@endif
