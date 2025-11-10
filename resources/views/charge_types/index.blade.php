@extends('layouts.app')
@section('title')
    {{ __('messages.charge_type.charge_types') }}
@endsection
@section('css')
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">--}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{ Form::hidden('chargeTypeUrl', url('charge-types'), ['class' => 'chargeTypeURLID' , 'id' => 'chargeTypeURLID']) }}
            {{ Form::hidden('chargeTypeCreateUrl', route('charge-types.store'), ['class' => 'chargeTypeCreateURLID']) }}
            <livewire:charge-type-table/>
            {{ Form::hidden('charge-type', __('messages.charge_type.charge_type'), ['id' => 'chargeType']) }}
            @include('charge_types.create_modal')
            @include('charge_types.edit_modal')
        </div>
    </div>
@endsection
    {{--     ssets/js/charge_types/charge_types.js -}}
    {{--     assets/js/charge_types/create-edit.js --}}

