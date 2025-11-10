@extends('layouts.app')
@section('title')
    {{ $pageTitle ?? __('messages.maternity_patients') }}
@endsection
@section('css')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">--}}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <div class="d-flex flex-column flex-xl-row justify-content-between mb-5">
                <div class="d-flex align-items-center">
                    <div class="d-inline-block">
                        {{Form::hidden('maternityPatientUrl',url('maternity'),['id'=>'indexMaternityPatientUrl'])}}
                        {{Form::hidden('patientUrl',url('patients'),['id'=>'indexPatientMaternityUrl'])}}
                        {{Form::hidden('doctorUrl',url('doctors'),['id'=>'indexMaternityDoctorUrl'])}}
                        {{ Form::hidden('maternity_patient_department', __('messages.maternity_patient.maternity_patient'), ['id' => 'Receptionist']) }}
                    </div>
                </div>
            </div>
            
            @if(isset($filter))
                <livewire:maternity-patient-table filter="{{ $filter }}"/>
            @else
                <livewire:maternity-patient-table/>
            @endif
            
            @include('maternity.templates.templates')

              @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/maternity/maternity_delete.js') }}"></script>
    <script src="{{ asset('assets/js/maternity/maternity_patients.js') }}"></script>
@endsection
