@extends('layouts.app')
@section('title')
    {{ __('messages.maternity') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('ipdPatientUrl',url('ipds'),['id'=>'indexIpdPatientUrl'])}}
            {{Form::hidden('patientUrl',url('patients'),['id'=>'indexIpdDepartmentPatientUrl'])}}
            {{Form::hidden('doctorUrl',url('doctors'),['id'=>'indexIpdDepartmentDoctorUrl'])}}
            {{Form::hidden('bedUrl',url('beds'),['id'=>'indexIpdDepartmentBedUrl'])}}
            {{ Form::hidden('ipd_patient', __('messages.ipd_patient.ipd_patient'), ['id' => 'ipdPatientDepartment']) }}
            <livewire:maternity-patient-table/>
            @include('ipd_patient_departments.templates.templates')
            @include('pathology_tests.templates.templates')
            @include('radiology_tests.templates.templates')
        </div>
    </div>

@endsection
