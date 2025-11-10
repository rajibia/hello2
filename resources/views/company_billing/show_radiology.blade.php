@extends('layouts.app')
@section('title')
    Company Radiology Test Details
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
                                <h4 class="mb-0">Company Radiology Test Details</h4>
                                <p class="text-muted mb-0">Test ID: {{ $bill->test_id ?? 'N/A' }}</p>
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
                            <h6 class="mb-0 fw-semibold">Radiology Test Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Test Date</label>
                                    <div>{{ \Carbon\Carbon::parse($bill->created_at)->format('M d, Y h:i A') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Status</label>
                                    <div>
                                        @if($bill->status == 1)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($bill->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
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
                                        {{ checkNumberFormat($bill->total_amount, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Paid Amount</label>
                                    <div class="fw-bold text-info fs-5">
                                        {{ checkNumberFormat($bill->amount_paid, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Balance</label>
                                    <div class="fw-bold text-danger fs-5">
                                        {{ checkNumberFormat($bill->balance, strtoupper(getCurrentCurrency())) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Radiology Test Items -->
            @if($bill->radiologyTestItems && $bill->radiologyTestItems->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Radiology Test Items</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead style="background-color: #212529; color: white;">
                                        <tr>
                                            <th style="color: white !important; background-color: #212529 !important;">Test Name</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Short Name</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Report Days</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->radiologyTestItems as $item)
                                        <tr>
                                            <td>{{ $item->radiologytesttemplate->test_name ?? 'N/A' }}</td>
                                            <td>{{ $item->radiologytesttemplate->short_name ?? 'N/A' }}</td>
                                            <td>{{ $item->radiologytesttemplate->report_days ?? 'N/A' }}</td>
                                            <td>{{ checkNumberFormat($item->amount, strtoupper(getCurrentCurrency())) }}</td>
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
