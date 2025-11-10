@extends('layouts.app')
@section('title')
    {{ __('messages.supplier.supplier_details') }}
@endsection
@section('page_css')
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                @if (!Auth::user()->hasRole('Doctor|Accountant|Case Manager|Nurse|Supplier'))
                    <a href="{{ route('suppliers.edit',['supplier' => $data->id]) }}"
                       class="btn btn-primary me-2">{{ __('messages.common.edit') }}</a>
                @endif
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        {{Form::hidden('supplierPaymentUrl',url('advanced-payments'),['id'=>'showSupplierAdvancedPaymentUrl'])}}
       
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                    @include('suppliers.show_fields')
                </div>
            </div>
            @include('suppliers.advanced_payments.edit_modal')
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/suppliers/suppliers_data_listing.js --}}
