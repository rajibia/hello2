@extends('layouts.app')
@section('title')
    {{ __('messages.scans') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('scanReportUrl',url('scans'),['id'=>'showScanReportUrl'])}}
            {{ Form::hidden('scan', __('messages.package.scan'), ['id' => 'Scan']) }}
            <livewire:scan-table/>
            @include('scans.templates.templates')
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
{{--
    JS File :- assets/js/custom/input_price_format.js
               assets/js/scans/scans.js
--}}
