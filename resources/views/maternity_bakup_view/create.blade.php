@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patient.new_maternity_patient') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        {{--
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            
            <div class="search-bar mx-auto">
                {{ Form::open(['method'=> 'GET', 'route'=>'maternity.patient.search', 'class'=>'d-flex align-items-center']) }}
                {{ Form::select('search_by', ['name'=>'Name', 'phone'=>'Phone Number', 'location'=>'Location', 'insurance_number'=>'Insurance Number'], null, ['class'=>'form-select me-2', 'placeholder'=>'Search by']) }}
                {{ Form::text('search_value', null, ['class'=>'form-control me-2', 'placeholder'=>'Enter search value']) }}
                {{ Form::submit('Search', ['class'=>'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
            <a href="{{route('maternity.index') }}"
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
            @include('maternity.maternity_patient_search')
            <div class="card">
                {{Form::hidden('patientCasesUrl',route('patient.cases.list'),['id'=>'createMaternityPatientCasesUrl','class'=>'maternityPatientCasesUrl'])}}
                {{Form::hidden('doctorMaternityChargeUrl',route('getDoctor.Maternitycharge'),['id'=>'createDoctorMaternityChargeUrl','class'=>'doctorMaternityChargeUrl'])}}
                {{Form::hidden('chargeMaternityChargeUrl',route('getCharge.Maternitycharge'),['id'=>'createChargeMaternityChargeUrl','class'=>'chargeMaternityChargeUrl'])}}
                {{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
                {{Form::hidden('lastVisit',(isset($data['last_visit'])) ? $data['last_visit']->patient_id : false,['id'=>'createMaternityLastVisit','class'=>'lastVisit'])}}

                <div class="card-body">
                    {{ Form::open(['route' => ['maternity.patient.store'], 'method'=>'post', 'id' => 'createMaternityPatientForm']) }}
                    {{Form::hidden('maternityAddSubmitRoute',route('maternity.patient.store'),['id'=>'maternityAddSubmitRoute','class'=>'maternityAddSubmitRoute'])}}
                    @include('maternity.fields')
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
    {{--   assets/js/maternity/create.js --}}
@endsection
