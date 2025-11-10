@extends('layouts.app')
@section('title')
    {{ __('messages.diagnoses') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('diagnosisReportUrl',url('diagnosis'),['id'=>'showDiagnosisReportUrl'])}}
            {{ Form::hidden('diagnosis', __('messages.package.diagnosis'), ['id' => 'Diagnosis']) }}
            <livewire:diagnosis-table/>
            @include('diagnosis.templates.templates')
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
{{--
    JS File :- assets/js/custom/input_price_format.js
               assets/js/diagnosis/diagnosis.js
--}}
