@extends('layouts.app')
@section('title')
    {{ __('messages.advanced_payment.advanced_payments') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <livewire:vitals-table/>
        </div>
        @include('partials.modal.templates.templates')
        {{Form::hidden('vitalsUrl',url('vitals'),['id'=>'indexVitalsUrl','class'=>'vitalsUrl'])}}
        {{Form::hidden('advancePaymentCreateUrl',route('vitals.store'),['id'=>'indexVitalsCreateUrl','class'=>'VitalsCreateUrl'])}}
        {{Form::hidden('vitalsUrl',url('vitals'),['id'=>'indexVitalsPatientUrl','class'=>'vitalsUrl'])}}
        {{ Form::hidden('Vitals', __('messages.vitals'), ['id' => 'Vitals']) }}

    </div>
@endsection
@section('scripts')
    {{--    assets/js/custom/input_price_format.js  --}}
    {{--    assets/js/advanced_payments/advanced_payments.js --}}
    {{--    assets/js/advanced_payments/create-edit.js --}}
@endsection
