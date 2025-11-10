@extends('layouts.app')
@section('title')
    {{ __('messages.suppliers') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
             {{Form::hidden('updatePaymentStatusUrl',url('payment-status'),['id'=>'updatePaymentStatusUrl'])}}
            {{Form::hidden('supplierUrl',url('suppliers'),['id'=>'indexSupplierUrl'])}}
            {{ Form::hidden('suppliers', __('messages.advanced_payment.supplier'), ['id' => 'Suppliers']) }}
            <livewire:supplier-table/>
            @include('accountants.templates.templates')
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/suppliers/suppliers.js --}}
