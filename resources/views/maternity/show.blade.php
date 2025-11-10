@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patient.maternity_patient_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">

                @role('Admin|Doctor')
                    @if (!$maternityPatient->doctor_discharge)
                        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#maternityDischargeModal">
                            Discharge Patient
                        </button>
                    @else
                        <span class="badge bg-success me-2">Discharged</span>
                    @endif
                @endrole
                @role('Admin|Doctor|Case Manager|Receptionist')
                <a href="{{ url('maternity/' . $maternityPatient->id . '/edit') }}"
                   class="btn btn-primary me-2">{{ __('messages.common.edit') }}</a>
                @endrole
                <a href="{{ route('maternity.index') }}"
                   class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('maternity.show_fields')
@endsection
