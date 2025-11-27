@extends('layouts.app')
@section('title')
    {{ __('messages.opd_patient.new_opd_patient') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        {{--
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            
            <div class="search-bar mx-auto">
                {{ Form::open(['method'=> 'GET', 'route'=>'opd.patient.search', 'class'=>'d-flex align-items-center']) }}
                {{ Form::select('search_by', ['name'=>'Name', 'phone'=>'Phone Number', 'location'=>'Location', 'insurance_number'=>'Insurance Number'], null, ['class'=>'form-select me-2', 'placeholder'=>'Search by']) }}
                {{ Form::text('search_value', null, ['class'=>'form-control me-2', 'placeholder'=>'Enter search value']) }}
                {{ Form::submit('Search', ['class'=>'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
            <a href="{{route('opd.patient.index') }}"
               class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
        --}}
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column livewire-table">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                    @include('flash::message')
                </div>
            </div>
            
            {{-- Include the search results partial --}}
            @include('opd_patient_departments.opd_patient_search')
            <div class="card">
                {{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createOpdPatientCasesUrl','class'=>'opdPatientCasesUrl'])}}
                {{Form::hidden('doctorOpdChargeUrl',route('getDoctor.OPDcharge'),['id'=>'createDoctorOpdChargeUrl','class'=>'doctorOpdChargeUrl'])}}
                {{Form::hidden('chargeOpdChargeUrl',route('getCharge.OPDcharge'),['id'=>'createChargeOpdChargeUrl','class'=>'chargeOpdChargeUrl'])}}
                {{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
                {{Form::hidden('lastVisit',(isset($data['last_visit'])) ? $data['last_visit']->patient_id : false,['id'=>'createOpdLastVisit','class'=>'lastVisit'])}}

                <div class="card-body">
                    {{ Form::open(['route' => ['opd.patient.store'], 'method'=>'post', 'id' => 'createOpdPatientForm']) }}
                    {{Form::hidden('opdAddSubmitRoute',route('opd.patient.store'),['id'=>'opdAddSubmitRoute','class'=>'opdAddSubmitRoute'])}}
                    @include('opd_patient_departments.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
{{--   assets/js/moment.min.js --}}
@endsection
@section('scripts')
    {{--   assets/js/opd_patients/create.js --}}
@endsection