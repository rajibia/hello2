@extends('layouts.app')
@section('title')
    Company Invoice Details
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        
        <div class="d-flex flex-column">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Company Invoice Details</h4>
                                <p class="text-muted mb-0">Invoice ID: {{ $invoice->invoice_id }}</p>
                            </div>
                            <div>
                                <a href="{{ route('company-billing.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Company Invoices
                                </a>
                                <a href="{{ route('company-billing.pdf', $invoice->id) }}" class="btn btn-primary" target="_blank">
                                     <i class="fas fa-download me-1"></i>Download PDF
                                 </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Patient Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Patient Name</label>
                                    <div class="fw-bold">{{ $invoice->patient->user ? $invoice->patient->user->first_name . ' ' . $invoice->patient->user->last_name : 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Patient ID</label>
                                    <div>{{ $invoice->patient->patient_unique_id }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Phone</label>
                                    <div>{{ $invoice->patient->user ? ($invoice->patient->user->phone ?? 'N/A') : 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Email</label>
                                    <div>{{ $invoice->patient->user ? ($invoice->patient->user->email ?? 'N/A') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Invoice Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Invoice Date</label>
                                    <div>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y h:i A') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Status</label>
                                    <div>
                                        @if($invoice->balance == 0)
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($invoice->balance == $invoice->amount)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @elseif($invoice->balance > 0 && $invoice->balance < $invoice->amount)
                                            <span class="badge bg-warning">Partially Paid</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Company</label>
                                    <div>{{ $invoice->patient->company->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Total Amount</label>
                                    <div class="fw-bold text-success fs-5">
                                        {{ checkNumberFormat($invoice->amount, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Balance</label>
                                    <div class="fw-bold text-danger fs-5">
                                        {{ checkNumberFormat($invoice->balance, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill Items -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Invoice Items</h6>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $hasItems = false;
                                $totalAmount = 0;
                            @endphp
                            
                            <!-- Invoice Items -->
                            @if($invoice->invoiceItems && $invoice->invoiceItems->count() > 0)
                                @php $hasItems = true; @endphp
                                <div class="border-bottom">
                                    <div class="p-3 bg-light">
                                        <h6 class="mb-0 text-primary"><i class="fas fa-list me-2"></i>Invoice Items</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice->invoiceItems as $invoiceItem)
                                                    @php $totalAmount += $invoiceItem->total; @endphp
                                                    <tr>
     
<td>
    <strong>
        {{ $invoiceItem->description ?? $invoiceItem->charge?->chargeCategory?->name ?? '—' }}
    </strong>
</td>
                                                        <td>{{ $invoiceItem->quantity ?? 'N/A' }}</td>
                                                        <td>{{ checkNumberFormat($invoiceItem->price ?? 0, strtoupper(getCurrentCurrency())) }}</td>
                                                        <td>{{ checkNumberFormat($invoiceItem->total, strtoupper(getCurrentCurrency())) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No detailed invoice items available for this invoice.</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($hasItems)
                            <div class="card-footer bg-light">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="text-muted">
                                            <small>Generated on {{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="fw-bold fs-5 text-success">
                                            Total: {{ checkNumberFormat($invoice->amount, strtoupper(getCurrentCurrency())) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection