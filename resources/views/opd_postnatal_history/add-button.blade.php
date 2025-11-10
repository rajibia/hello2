@aware(['component'])

@php
    $opdId = $component->opdId;
@endphp

@if ((Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Nurse')) && $opdId != null)
    <a href="{{ route('opd.postnatal.create', ['ref_opd_id' => $opdId]) }}" class="btn btn-primary">
        {{ __('messages.postnatal.new_postnatal') }}
    </a>
@endif
