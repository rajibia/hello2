@extends('layouts.app')
@section('title')
    Company Medicine Bill Details
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
                                <h4 class="mb-0">Company Medicine Bill Details</h4>
                                <p class="text-muted mb-0">Bill Number: {{ $bill->bill_number }}</p>
                            </div>
                            <div>
                                <a href="{{ route('company-billing.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Company Invoices
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
                                    <div class="fw-bold">{{ $bill->patient->user ? $bill->patient->user->first_name . ' ' . $bill->patient->user->last_name : 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Patient ID</label>
                                    <div>{{ $bill->patient->patient_unique_id }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Phone</label>
                                    <div>{{ $bill->patient->user ? ($bill->patient->user->phone ?? 'N/A') : 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Email</label>
                                    <div>{{ $bill->patient->user ? ($bill->patient->user->email ?? 'N/A') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Medicine Bill Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Bill Date</label>
                                    <div>{{ \Carbon\Carbon::parse($bill->bill_date)->format('M d, Y h:i A') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Status</label>
                                    <div>
                                        @if($bill->payment_status == 1)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Company</label>
                                    <div>{{ $bill->patient->company->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Total Amount</label>
                                    <div class="fw-bold text-success fs-5">
                                        {{ checkNumberFormat($bill->total, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Discount</label>
                                    <div class="fw-bold text-info fs-5">
                                        {{ checkNumberFormat($bill->discount ?? 0, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Net Amount</label>
                                    <div class="fw-bold text-primary fs-5">
                                        {{ checkNumberFormat($bill->total - ($bill->discount ?? 0), strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medicine Items -->
            @if($bill->saleMedicine && $bill->saleMedicine->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Medicine Items</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead style="background-color: #212529; color: white;">
                                        <tr>
                                            <th style="color: white !important; background-color: #212529 !important;">Medicine</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Quantity</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Sale Price</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->saleMedicine as $item)
                                        <tr>
                                            <td>{{ $item->medicine->name ?? 'N/A' }}</td>
                                            <td>{{ $item->sale_quantity }}</td>
                                            <td>{{ checkNumberFormat($item->sale_price, strtoupper(getCurrentCurrency())) }}</td>
                                            <td>{{ checkNumberFormat($item->sale_price * $item->sale_quantity, strtoupper(getCurrentCurrency())) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
