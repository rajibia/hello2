@extends('layouts.app')
@section('title')
    {{ __('messages.charge_type.charge_type_details')}}
@endsection
@section('css')
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/detail-header.css') }}">--}}
@endsection
@section('content')
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="row">
                {{ Form::hidden('chargeTypeUrl', url('charge-types'), ['class' => 'chargeTypeURLID']) }}
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
            <div class="p-12">
                @include('charge_types.show_fields')
            </div>
        </div>
    </div>
    @include('charge_types.edit_modal')
@endsection
{{--   assets/js/charge_types/create-details-edit.js --}}
