@extends('layouts.app')
@section('title')
    {{ __('messages.ipd_patient.new_ipd_patient') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        {{--
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="search-bar mx-auto">
                {{ Form::open(['method'=> 'GET', 'route'=>'ipd.patient.search', 'class'=>'d-flex align-items-center']) }}
                {{ Form::select('search_by', ['name'=>'Name', 'phone'=>'Phone Number', 'location'=>'Location', 'insurance_number'=>'Insurance Number'], null, ['class'=>'form-select me-2', 'placeholder'=>'Search by']) }}
                {{ Form::text('search_value', null, ['class'=>'form-control me-2', 'placeholder'=>'Enter search value']) }}
                {{ Form::submit('Search', ['class'=>'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
            <a href="{{ route('ipd.patient.index') }}"
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
                </div>
            </div>
            {{-- Include the search results partial --}}
            @include('ipd_patient_departments.ipd_patient_search')
            <div class="card">
                {{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createPatientCasesUrl','class'=>'patientCasesUrl'])}}
                {{Form::hidden('patientBedsUrl',route('patient.beds.list'),['id'=>'createPatientBedsUrl','class'=>'patientBedsUrl'])}}
                {{Form::hidden('isEdit',false,['class'=>'isEdit'])}}

                <div class="card-body">
                    {{ Form::open(['route' => ['ipd.patient.store'], 'method'=>'post', 'files' => true, 'id' => 'createIpdPatientForm']) }}
                    @include('ipd_patient_departments.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
{{--   assets/js/ipd_patients/create.js --}}
@endsection
