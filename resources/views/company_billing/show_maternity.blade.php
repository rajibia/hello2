@extends('layouts.app')
@section('title')
    Company Maternity Bill Details
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
                                <h4 class="mb-0">Company Maternity Bill Details</h4>
                                <p class="text-muted mb-0">Maternity ID: {{ $bill->id ?? 'N/A' }}</p>
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
                                    <div>{{ $bill->patient->patient_unique_id ?? 'N/A' }}</div>
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
                            <h6 class="mb-0 fw-semibold">Maternity Bill Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Appointment Date</label>
                                    <div>{{ \Carbon\Carbon::parse($bill->appointment_date)->format('M d, Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Status</label>
                                    <div>
                                        @if(($bill->paid_amount ?? 0) >= $bill->standard_charge)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Partially Paid</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Company</label>
                                    <div>{{ $bill->patient->company->name ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Standard Charge</label>
                                    <div class="fw-bold text-success fs-5">
                                        {{ checkNumberFormat($bill->standard_charge, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Paid Amount</label>
                                    <div class="fw-bold text-info fs-5">
                                        {{ checkNumberFormat($bill->paid_amount ?? 0, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Balance</label>
                                    <div class="fw-bold text-danger fs-5">
                                        {{ checkNumberFormat($bill->standard_charge - ($bill->paid_amount ?? 0), strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maternity Details -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Maternity Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Gestational Age</label>
                                    <div>{{ $bill->gestational_age ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Blood Pressure</label>
                                    <div>{{ $bill->blood_pressure ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Weight</label>
                                    <div>{{ $bill->weight ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-semibold">Height</label>
                                    <div>{{ $bill->height ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Notes</label>
                                    <div>{{ $bill->notes ?? 'No notes available' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
