@aware(['component'])

@php
    $ipdId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $ipdId != null)
    <a href="{{ route('ipd.obstetric.create', ['ref_ipd_id' => $ipdId]) }}" class="btn btn-primary">
        {{ __('messages.previous_obstetric_history.new_previous_obstetric_history') }}
    </a>
@endif
