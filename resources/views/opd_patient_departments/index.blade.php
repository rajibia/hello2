@extends('layouts.app')
@section('title')
    {{ $pageTitle ?? __('messages.opd_patients') }}
@endsection
@section('css')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">--}}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            {{Form::hidden('opdPatientUrl',url('opds'),['id'=>'indexOpdPatientUrl'])}}
            {{Form::hidden('patientUrl',url('patients'),['id'=>'indexPatientOpdUrl'])}}
            {{Form::hidden('doctorUrl',url('doctors'),['id'=>'indexOpdDoctorUrl'])}}
            {{ Form::hidden('opd_patient_department', __('messages.opd_patient.opd_patient'), ['id' => 'Receptionist']) }}
            
            @if(isset($filter))
                <livewire:opd-patient-table filter="{{ $filter }}"/>
            @else
                <livewire:opd-patient-table/>
            @endif
            
            @include('opd_patient_departments.templates.templates')

              @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    {{-- assets/js/opd_patients/opd_patients.js--}}
@endsection
