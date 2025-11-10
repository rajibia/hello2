@extends('layouts.app')
@section('title')
    Systemic Examination Details
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{__('messages.advanced_payment.advanced_payment_details')}}</h1>
            <div class="text-end mt-4 mt-md-0">
                {{-- <a class="btn btn-primary advance-payment-edit-btn"
                   data-id="{{ $advancedPayment->id }}">{{ __('messages.common.edit') }}</a> --}}
                   <a href="#" onClick="history.back()"
                   class="btn btn-outline-primary ms-2">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column livewire-table">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
                @include('systemic_examinations.show_fields')
        </div>
    </div>
@endsection
@section('scripts')
    {{--   assets/js/custom/input_price_format.js --}}
    {{--   assets/js/advanced_payments/advanced_payments.js --}}
    {{--   assets/js/advanced_payments/create-edit.js --}}
@endsection
