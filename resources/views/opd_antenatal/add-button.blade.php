@aware(['component'])

@php
    $opdId = $component->opdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $opdId != null)
    <a href="{{ route('opd.antenatal.create', ['ref_opd_id' => $opdId]) }}" class="btn btn-primary">
        {{ __('messages.antenatal.new_antenatal') }}
    </a>
@endif
