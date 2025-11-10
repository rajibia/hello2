@extends('layouts.app')
@section('title')
    Company IPD Bill Details
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
                                <h4 class="mb-0">Company IPD Bill Details</h4>
                                <p class="text-muted mb-0">IPD Number: {{ $bill->ipdPatient->ipd_number ?? 'N/A' }}</p>
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
                                    <div class="fw-bold">{{ $bill->ipdPatient->patient->user ? $bill->ipdPatient->patient->user->first_name . ' ' . $bill->ipdPatient->patient->user->last_name : 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Patient ID</label>
                                    <div>{{ $bill->ipdPatient->patient->patient_unique_id ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Phone</label>
                                    <div>{{ $bill->ipdPatient->patient->user ? ($bill->ipdPatient->patient->user->phone ?? 'N/A') : 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-semibold">Email</label>
                                    <div>{{ $bill->ipdPatient->patient->user ? ($bill->ipdPatient->patient->user->email ?? 'N/A') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">IPD Bill Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Admission Date</label>
                                    <div>{{ \Carbon\Carbon::parse($bill->ipdPatient->admission_date)->format('M d, Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Status</label>
                                    <div>
                                        @if($bill->bill_status == 1)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted fw-semibold">Company</label>
                                    <div>{{ $bill->ipdPatient->patient->company->name ?? 'N/A' }}</div>
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
                                        {{ checkNumberFormat($bill->paid_amount, strtoupper(getCurrentCurrency())) }}
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

            <!-- IPD Bill Items -->
            @if($bill->ipdBillItems && $bill->ipdBillItems->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">IPD Bill Items</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead style="background-color: #212529; color: white;">
                                        <tr>
                                            <th style="color: white !important; background-color: #212529 !important;">Charge</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Category</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Standard Charge</th>
                                            <th style="color: white !important; background-color: #212529 !important;">Applied Charge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->ipdBillItems as $item)
                                        <tr>
                                            <td>{{ $item->charge->name ?? 'N/A' }}</td>
                                            <td>{{ $item->charge->chargeCategory->name ?? 'N/A' }}</td>
                                            <td>{{ checkNumberFormat($item->charge->standard_charge, strtoupper(getCurrentCurrency())) }}</td>
                                            <td>{{ checkNumberFormat($item->applied_charge, strtoupper(getCurrentCurrency())) }}</td>
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
