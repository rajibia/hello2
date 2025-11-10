@extends('layouts.app')
@section('title')
    All Patient Bills
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">All Patient Bills</h4>
                <p class="text-muted mb-0">View all bill types (OPD, Medicine, IPD, Pathology, Radiology) grouped by patients</p>
            </div>
        </div>
        {{Form::hidden('invoiceUrl',route('invoices.index'),['id'=>'indexInvoiceUrl'])}}
        {{Form::hidden('patientUrl',url('patients'),['id'=>'indexPatientUrl'])}}
        {{ Form::hidden('invoices', __('messages.invoice.invoice'), ['id' => 'Invoices']) }}
        <div class="d-flex flex-column">

            <livewire:invoice-table/>
        </div>
    </div>
@endsection
@section('page_scripts')
    {{-- assets/js/moment.min.js --}}
@endsection
@section('scripts')
    {{-- assets/js/custom/input_price_format.js --}}
    {{-- assets/js/invoices/invoice.js --}}

@endsection
