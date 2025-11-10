@extends('layouts.app')
@section('title')
    Company Billing
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')

        <div class="d-flex flex-column">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Company Billing Management</h4>
                            <p class="text-muted mb-0">Manage all company invoices with filtering and sorting options</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Search & Filter Company Invoices</h6>
                        </div>
                        <div class="card-body bg-light">
                            <form method="GET" action="{{ route('company-billing.index') }}">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">Patient</label>
                                        <select name="patient_id" class="form-select">
                                            <option value="">All Patients</option>
                                            @foreach($patients as $patientId => $patientName)
                                                <option value="{{ $patientId }}" {{ $filters['patient_id'] == $patientId ? 'selected' : '' }}>
                                                    {{ $patientName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">Company</label>
                                        <select name="company_id" class="form-select">
                                            <option value="">All Companies</option>
                                            @foreach($companies as $companyId => $companyName)
                                                <option value="{{ $companyId }}" {{ $filters['company_id'] == $companyId ? 'selected' : '' }}>
                                                    {{ $companyName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">Type</label>
                                        <select name="type" class="form-select">
                                            <option value="">All Types</option>
                                            @foreach($billTypes as $key => $typeName)
                                                <option value="{{ $key }}" {{ $filters['type'] == $key ? 'selected' : '' }}>
                                                    {{ $typeName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            @foreach($status as $key => $statusValue)
                                                <option value="{{ $key }}" {{ $filters['status'] == $key ? 'selected' : '' }}>
                                                    {{ $statusValue }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">From Date</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-muted fw-semibold">To Date</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('company-billing.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bills Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Company Invoices ({{ $bills->total() }} records)</h6>
                            <div class="text-muted">
                                Showing {{ $bills->firstItem() }} to {{ $bills->lastItem() }} of {{ $bills->total() }} results
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($bills->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead style="background-color: #212529; color: white;">
                                            <tr>
                                                <th style="color: white !important; background-color: #212529 !important;">SL</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Patient</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Company</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Type</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Invoice Date</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Amount</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Balance</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Status</th>
                                                <th style="color: white !important; background-color: #212529 !important;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bills as $index => $bill)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $bills->firstItem() + $index }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm me-2">
                                                                <div class="avatar-title bg-primary rounded-circle">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $bill->patient->user->first_name ?? 'N/A' }} {{ $bill->patient->user->last_name ?? '' }}</h6>
                                                                <small class="text-muted">ID: {{ $bill->patient->patient_unique_id ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $bill->patient->company->name ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                    @if($bill->bill_type == 'OPD Invoice')
                                        <span class="badge bg-primary">{{ $bill->bill_type }}</span>
                                    @elseif($bill->bill_type == 'Medicine Bill')
                                        <span class="badge bg-success">{{ $bill->bill_type }}</span>
                                    @elseif($bill->bill_type == 'IPD Bill')
                                        <span class="badge bg-warning">{{ $bill->bill_type }}</span>
                                    @elseif($bill->bill_type == 'Laboratory Test')
                                        <span class="badge bg-info">{{ $bill->bill_type }}</span>
                                    @elseif($bill->bill_type == 'Radiology Test')
                                        <span class="badge bg-secondary">{{ $bill->bill_type }}</span>
                                    @elseif($bill->bill_type == 'Maternity Bill')
                                        <span class="badge bg-danger">{{ $bill->bill_type }}</span>
                                    @else
                                        <span class="badge bg-dark">{{ $bill->bill_type }}</span>
                                    @endif
                                </td>
                                                    <td>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($bill->invoice_date)->format('M d, Y') }}</span>
                                </td>
                                                    <td>
                                        <span class="fw-bold text-success">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($bill->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($bill->balance, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($bill->balance == 0)
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($bill->balance == $bill->amount)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @elseif($bill->balance > 0 && $bill->balance < $bill->amount)
                                            <span class="badge bg-warning">Partially Paid</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('company-billing.show', $bill->id) }}"
                                                               class="btn btn-sm btn-outline-primary"
                                                               title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('company-billing.pdf', $bill->id) }}"
                                                               class="btn btn-sm btn-outline-success"
                                                               title="Download PDF"
                                                               target="_blank">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-file-invoice-dollar fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">No Company Invoices Found</h5>
                                    <p class="text-muted">No invoices match your current filter criteria.</p>
                                </div>
                            @endif
                        </div>

                        @if($bills->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $bills->firstItem() }} to {{ $bills->lastItem() }} of {{ $bills->total() }} results
                                    </div>
                                    <div>
                                        {{ $bills->appends(request()->query())->links() }}
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

@section('scripts')
    <script>
        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('form');
            const selects = filterForm.querySelectorAll('select');
            const dateInputs = filterForm.querySelectorAll('input[type="date"]');

            // Auto-submit on select change
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            // Auto-submit on date change with slight delay
            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    setTimeout(() => {
                        filterForm.submit();
                    }, 300);
                });
            });
        });
    </script>
@endsection
