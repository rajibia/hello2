@aware(['component'])

@php
    $ipdId = $component->ipdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $ipdId != null)
    <a href="{{ route('ipd.postnatal.create', ['ref_ipd_id' => $ipdId]) }}" class="btn btn-primary">
        {{ __('messages.postnatal.new_postnatal') }}
    </a>
@endif
