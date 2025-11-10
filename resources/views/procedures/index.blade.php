@extends('layouts.app')
@section('title')
    {{ __('messages.procedures') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('procedureReportUrl',url('procedures'),['id'=>'showProcedureReportUrl'])}}
            {{ Form::hidden('procedure', __('messages.package.procedure'), ['id' => 'Procedure']) }}
            <livewire:procedure-table/>
            @include('procedures.templates.templates')
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
{{--
    JS File :- assets/js/custom/input_price_format.js
               assets/js/procedures/procedures.js
--}}
