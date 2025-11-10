@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patient.edit_maternity_patient') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{route('maternity.index') }}"
               class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column livewire-table">
            <div class="row">
                    @include('layouts.errors')
                </div>
            </div>
            <div class="card">
                {{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'editMaternityPatientCasesUrl','class'=>'maternityPatientCasesUrl'])}}
                {{Form::hidden('doctorMaternityChargeUrl',route('getDoctor.MaternityCharge'),['id'=>'editDoctorMaternityChargeUrl','class'=>'doctorMaternityChargeUrl'])}}
                {{Form::hidden('chargeMaternityChargeUrl',route('getCharge.MaternityCharge'),['id'=>'createChargeMaternityChargeUrl','class'=>'chargeMaternityChargeUrl'])}}
                {{Form::hidden('isEdit',true,['class'=>'isEdit'])}}
                {{Form::hidden('lastVisit',false,['id'=>'editMaternityLastVisit','class'=>'lastVisit'])}}

                <div class="card-body">
                    {{ Form::model($maternityPatient, ['route' => ['maternity.update', $maternityPatient->id], 'method' => 'patch', 'id' => 'editMaternityPatientForm']) }}

                    @include('maternity.edit_fields')

                    {{ Form::close() }}
                </div>
            </div>
        </div>
{{--    </div>--}}
@endsection
@section('scripts')
    {{--   assets/js/maternity_patients/create.js --}}
@endsection
