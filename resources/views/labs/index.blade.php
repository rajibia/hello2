@extends('layouts.app')
@section('title')
    {{ __('messages.labs') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('labReportUrl',url('labs'),['id'=>'showLabReportUrl'])}}
            {{ Form::hidden('lab', __('messages.package.lab'), ['id' => 'Lab']) }}
            <livewire:lab-table/>
            @include('labs.templates.templates')
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
{{--
    JS File :- assets/js/custom/input_price_format.js
               assets/js/labs/labs.js
--}}
