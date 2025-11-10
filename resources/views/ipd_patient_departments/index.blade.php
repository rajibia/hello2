@extends('layouts.app')
@section('title')
    {{ $pageTitle ?? __('messages.ipd_patient.ipd_patients') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            {{Form::hidden('ipdPatientUrl',url('ipds'),['id'=>'indexIpdPatientUrl'])}}
            {{Form::hidden('patientUrl',url('patients'),['id'=>'indexIpdDepartmentPatientUrl'])}}
            {{Form::hidden('doctorUrl',url('doctors'),['id'=>'indexIpdDepartmentDoctorUrl'])}}
            {{Form::hidden('bedUrl',url('beds'),['id'=>'indexIpdDepartmentBedUrl'])}}
            {{ Form::hidden('ipd_patient', __('messages.ipd_patient.ipd_patient'), ['id' => 'ipdPatientDepartment']) }}
            
            
            
            @if(isset($filter))
                <livewire:ipd-patient-table filter="{{ $filter }}"/>
            @else
                <livewire:ipd-patient-table/>
            @endif
            
            @include('ipd_patient_departments.templates.templates')
            @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
{{--    assets/js/ipd_patients/ipd_patients.js--}}
@endsection
