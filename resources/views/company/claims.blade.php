@extends('layouts.app')
@section('title')
    Claims Report - {{ $company->name }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div>
                <a href="{{ route('companies.view', $company->id) }}"
                   class="btn btn-outline-secondary me-2">Back to Company</a>
                <a href="{{ route('companies.index') }}"
                   class="btn btn-outline-primary">View Companies</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Date Range Selection -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Claims Report</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('companies.claims', $company->id) }}" class="row align-items-end">
                    <div class="col-md-2 mb-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date"
                               class="form-control"
                               id="from_date"
                               name="from_date"
                               value="{{ request('from_date', $summaryData['from_date']) }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date"
                               class="form-control"
                               id="to_date"
                               name="to_date"
                               value="{{ request('to_date', $summaryData['to_date']) }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="bill_type" class="form-label">Bill Type</label>
                        <select class="form-select" id="bill_type" name="bill_type">
                            <option value="">All Bill Types</option>
                            <option value="opd_invoice" {{ request('bill_type') == 'opd_invoice' ? 'selected' : '' }}>OPD Invoice</option>
                            <option value="medicine_bill" {{ request('bill_type') == 'medicine_bill' ? 'selected' : '' }}>Medicine Bill</option>
                            <option value="ipd_bill" {{ request('bill_type') == 'ipd_bill' ? 'selected' : '' }}>IPD Bill</option>
                            <option value="pathology_test" {{ request('bill_type') == 'pathology_test' ? 'selected' : '' }}>Pathology Test</option>
                            <option value="radiology_test" {{ request('bill_type') == 'radiology_test' ? 'selected' : '' }}>Radiology Test</option>
                            <option value="maternity" {{ request('bill_type') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select class="form-select" id="patient_id" name="patient_id">
                            <option value="">All Patients</option>
                            @foreach($allPatients as $patient)
                                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->first_name }} {{ $patient->user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="per_page" class="form-label">Items Per Page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', '20') == '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Apply Filters
                            </button>
                            <a href="{{ route('companies.claims', $company->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear Filters
                            </a>
                            <button type="button" class="btn btn-secondary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>Print Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Report -->
        <div class="card mb-4" id="summary-report">
            <div class="card-header">
                <h5 class="mb-0">Claims Summary Report</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $summaryData['total_patients'] }}</h4>
                                <p class="mb-0">Total Patients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $summaryData['total_all_bills'] }}</h4>
                                <p class="mb-0">Total Bills</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($summaryData['total_bills'], 2) }}</h4>
                                <p class="mb-0">Total Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($summaryData['total_unpaid'], 2) }}</h4>
                                <p class="mb-0">Outstanding</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bill Type Breakdown -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Bill Type Breakdown:</h6>
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_invoices'] }}</h6>
                                        <small class="text-muted">OPD Invoices</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card border-success">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_medicine_bills'] }}</h6>
                                        <small class="text-muted">Medicine Bills</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card border-info">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_ipd_bills'] }}</h6>
                                        <small class="text-muted">IPD Bills</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card border-warning">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_pathology_tests'] }}</h6>
                                        <small class="text-muted">Pathology Tests</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card border-danger">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_radiology_tests'] }}</h6>
                                        <small class="text-muted">Radiology Tests</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card border-secondary">
                                    <div class="card-body text-center p-2">
                                        <h6 class="mb-1">{{ $summaryData['total_maternity'] }}</h6>
                                        <small class="text-muted">Maternity</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5 class="text-success">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($summaryData['total_paid'], 2) }}</h5>
                                <p class="mb-0">Total Paid Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h5 class="text-danger">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($summaryData['total_unpaid'], 2) }}</h5>
                                <p class="mb-0">Total Outstanding Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Payment Section -->
        <div class="card mb-4" id="bulk-payment-section">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>Bulk Payment Management
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="selectAllBills" class="form-check-input me-2" onchange="toggleAllBills()">
                            <label for="selectAllBills" class="form-check-label fw-semibold">Select All Unpaid Bills</label>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            <span class="me-3">
                                <strong>Selected Total:</strong>
                                <span id="selectedTotal" class="text-success fw-bold">GHS 0.00</span>
                            </span>
                            <button type="button" class="btn btn-success btn-sm" onclick="processBulkPayment()" id="bulkPayBtn" disabled>
                                <i class="fas fa-credit-card me-1"></i>Pay Selected Bills
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Claims Report -->
        <div class="card" id="detailed-report">
            <div class="card-header">
                <h5 class="mb-0">Detailed Claims Report</h5>
            </div>
            <div class="card-body">
                @if($patientsWithBills->total() > 0)
                    <!-- Pagination Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            Showing {{ $patientsWithBills->firstItem() }} to {{ $patientsWithBills->lastItem() }} of {{ $patientsWithBills->total() }} patients
                        </div>
                        <div class="text-muted">
                            Page {{ $patientsWithBills->currentPage() }} of {{ $patientsWithBills->lastPage() }}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white" style="width: 40px;">
                                        <input type="checkbox" id="selectAllHeader" class="form-check-input" onchange="toggleAllBills()">
                                    </th>
                                    <th class="text-white">#</th>
                                    <th class="text-white">Patient Name</th>
                                    <th class="text-white">Bill Type</th>
                                    <th class="text-white">Bill #</th>
                                    <th class="text-white">Date</th>
                                    <th class="text-white">Services/Items</th>
                                    <th class="text-white">Amount</th>
                                    <th class="text-white">Paid</th>
                                    <th class="text-white">Balance</th>
                                    <th class="text-white">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $billCounter = ($patientsWithBills->currentPage() - 1) * $patientsWithBills->perPage() + 1; @endphp
                                @foreach($patients as $patient)
                                    {{-- OPD Invoices --}}
                                    @foreach($patient->invoices as $invoice)
                                        <tr>
                                            <td class="text-center">
                                                @if($invoice->balance > 0)
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $invoice->id }}"
                                                           data-bill-type="opd_invoice"
                                                           data-balance="{{ $invoice->balance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="{{ $invoice->invoice_id }}"
                                                           onchange="updateSelectedTotal()">
                                                @endif
                                            </td>
                                            <td>{{ $billCounter++ }}</td>
                                            <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                            <td><span class="badge bg-primary">OPD Invoice</span></td>
                                            <td><a href="{{ route('invoices.show', $invoice->id) }}" class="text-primary text-decoration-none">{{ $invoice->invoice_id }}</a></td>
                                            <td>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</td>
                                            <td>
                                                @foreach($invoice->invoiceItems as $item)
                                                    <small class="d-block">{{ $item->charge->chargeCategory->name ?? 'N/A' }} - {{ $item->description }}</small>
                                                @endforeach
                                            </td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount - $invoice->balance, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->balance, 2) }}</td>
                                            <td>
                                                @if($invoice->balance > 0)
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @else
                                                    <span class="badge bg-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Medicine Bills --}}
                                    @foreach($patient->medicine_bills as $medicineBill)
                                        <tr>
                                            <td class="text-center">
                                                @if($medicineBill->balance > 0)
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $medicineBill->id }}"
                                                           data-bill-type="medicine_bill"
                                                           data-balance="{{ $medicineBill->balance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="{{ $medicineBill->bill_number }}"
                                                           onchange="updateSelectedTotal()">
                                                @endif
                                            </td>
                                            <td>{{ $billCounter++ }}</td>
                                            <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                            <td><span class="badge bg-success">Medicine Bill</span></td>
                                            <td><a href="{{ route('medicine-bills.show', $medicineBill->id) }}" class="text-primary text-decoration-none">{{ $medicineBill->bill_number }}</a></td>
                                            <td>{{ date('M d, Y', strtotime($medicineBill->created_at)) }}</td>
                                            <td>
                                                @foreach($medicineBill->saleMedicine as $item)
                                                    <small class="d-block">{{ $item->medicine->name ?? 'N/A' }} - Qty: {{ $item->quantity }}</small>
                                                @endforeach
                                            </td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->net_amount, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->net_amount - $medicineBill->balance, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->balance, 2) }}</td>
                                            <td>
                                                @if($medicineBill->balance > 0)
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @else
                                                    <span class="badge bg-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- IPD Bills --}}
                                    @foreach($patient->ipd_bills as $ipdPatient)
                                        @if($ipdPatient->bill)
                                            @php
                                                $ipdBill = $ipdPatient->bill;
                                                $ipdBalance = $ipdBill->net_payable_amount - $ipdBill->total_payments;
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    @if($ipdBalance > 0)
                                                        <input type="checkbox" class="form-check-input bill-checkbox"
                                                               data-bill-id="{{ $ipdPatient->id }}"
                                                               data-bill-type="ipd_bill"
                                                               data-balance="{{ $ipdBalance }}"
                                                               data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                               data-bill-number="IPD-{{ $ipdPatient->ipd_number }}"
                                                               onchange="updateSelectedTotal()">
                                                    @endif
                                                </td>
                                                <td>{{ $billCounter++ }}</td>
                                                <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                                <td><span class="badge bg-info">IPD Bill</span></td>
                                                <td><a href="{{ route('ipd.patient.show', $ipdPatient->id) }}" class="text-primary text-decoration-none">IPD-{{ $ipdPatient->ipd_number }}</a></td>
                                                <td>{{ date('M d, Y', strtotime($ipdPatient->created_at)) }}</td>
                                                <td>
                                                    <small class="d-block">IPD Admission - {{ $ipdPatient->bed->bedType->title ?? 'N/A' }}</small>
                                                </td>
                                                <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBill->net_payable_amount, 2) }}</td>
                                                <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBill->total_payments, 2) }}</td>
                                                <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBalance, 2) }}</td>
                                                <td>
                                                    @if($ipdBalance > 0)
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @else
                                                        <span class="badge bg-success">Paid</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- Pathology Tests --}}
                                    @foreach($patient->pathologyTests as $pathologyTest)
                                        <tr>
                                            <td class="text-center">
                                                @if($pathologyTest->balance > 0)
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $pathologyTest->id }}"
                                                           data-bill-type="pathology_test"
                                                           data-balance="{{ $pathologyTest->balance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="{{ $pathologyTest->bill_no }}"
                                                           onchange="updateSelectedTotal()">
                                                @endif
                                            </td>
                                            <td>{{ $billCounter++ }}</td>
                                            <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                            <td><span class="badge bg-warning">Pathology Test</span></td>
                                            <td><a href="{{ route('pathology.test.show', $pathologyTest->id) }}" class="text-primary text-decoration-none">{{ $pathologyTest->bill_no }}</a></td>
                                            <td>{{ date('M d, Y', strtotime($pathologyTest->created_at)) }}</td>
                                            <td>
                                                @foreach($pathologyTest->pathologyTestItems as $item)
                                                    <small class="d-block">{{ $item->pathologytesttemplate->test_name ?? 'N/A' }}</small>
                                                @endforeach
                                            </td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->total, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->total - $pathologyTest->balance, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->balance, 2) }}</td>
                                            <td>
                                                @if($pathologyTest->balance > 0)
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @else
                                                    <span class="badge bg-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Radiology Tests --}}
                                    @foreach($patient->radiologyTests as $radiologyTest)
                                        <tr>
                                            <td class="text-center">
                                                @if($radiologyTest->balance > 0)
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $radiologyTest->id }}"
                                                           data-bill-type="radiology_test"
                                                           data-balance="{{ $radiologyTest->balance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="{{ $radiologyTest->bill_no }}"
                                                           onchange="updateSelectedTotal()">
                                                @endif
                                            </td>
                                            <td>{{ $billCounter++ }}</td>
                                            <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                            <td><span class="badge bg-danger">Radiology Test</span></td>
                                            <td><a href="{{ route('radiology.test.show', $radiologyTest->id) }}" class="text-primary text-decoration-none">{{ $radiologyTest->bill_no }}</a></td>
                                            <td>{{ date('M d, Y', strtotime($radiologyTest->created_at)) }}</td>
                                            <td>
                                                @foreach($radiologyTest->radiologyTestItems as $item)
                                                    <small class="d-block">{{ $item->radiologytesttemplate->test_name ?? 'N/A' }}</small>
                                                @endforeach
                                            </td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->total, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->total - $radiologyTest->balance, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->balance, 2) }}</td>
                                            <td>
                                                @if($radiologyTest->balance > 0)
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @else
                                                    <span class="badge bg-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Maternity --}}
                                    @foreach($patient->maternity as $maternity)
                                        <tr>
                                            <td class="text-center">
                                                @if($maternity->balance > 0)
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $maternity->id }}"
                                                           data-bill-type="maternity"
                                                           data-balance="{{ $maternity->balance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="MAT-{{ $maternity->id }}"
                                                           onchange="updateSelectedTotal()">
                                                @endif
                                            </td>
                                            <td>{{ $billCounter++ }}</td>
                                            <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                                            <td><span class="badge bg-secondary">Maternity</span></td>
                                            <td><a href="{{ route('maternity.patient.show', $maternity->id) }}" class="text-primary text-decoration-none">MAT-{{ $maternity->id }}</a></td>
                                            <td>{{ date('M d, Y', strtotime($maternity->created_at)) }}</td>
                                            <td>
                                                <small class="d-block">Maternity Care</small>
                                            </td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->standard_charge, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->standard_charge - $maternity->balance, 2) }}</td>
                                            <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->balance, 2) }}</td>
                                            <td>
                                                @if($maternity->balance > 0)
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @else
                                                    <span class="badge bg-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $patientsWithBills->firstItem() }} to {{ $patientsWithBills->lastItem() }} of {{ $patientsWithBills->total() }} patients
                        </div>
                        <div>
                            {{ $patientsWithBills->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">No bills found for the selected date range.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Table header styling for better visibility */
        .table-dark th {
            background-color: #343a40 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-color: #454d55 !important;
        }

        .table-dark th.text-white {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        /* Ensure table headers are visible in print */
        @media print {
            .table-dark th {
                background-color: #343a40 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media print {
            /* Hide non-essential elements */
            .header_toolbar,
            .btn:not(.print-keep),
            .navbar,
            .sidebar,
            .footer,
            .no-print {
                display: none !important;
            }

            /* Page setup */
            @page {
                margin: 0.5in;
                size: A4;
            }

            body {
                font-size: 12px;
                line-height: 1.3;
                color: #000;
                background: white;
            }

            /* Container adjustments */
            .container-fluid {
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            /* Card styling */
            .card {
                border: 1px solid #ddd;
                box-shadow: none;
                margin-bottom: 20px;
                page-break-inside: avoid;
            }

            .card-header {
                background-color: #f8f9fa !important;
                border-bottom: 1px solid #ddd;
                padding: 10px 15px;
            }

            .card-body {
                padding: 15px;
            }

            /* Table styling for print */
            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .table-dark th {
                background-color: #343a40 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Badge styling for print */
            .badge {
                border: 1px solid #000;
                padding: 2px 6px;
                font-size: 10px;
            }

            .bg-primary { background-color: #007bff !important; color: #fff !important; }
            .bg-success { background-color: #28a745 !important; color: #fff !important; }
            .bg-info { background-color: #17a2b8 !important; color: #fff !important; }
            .bg-warning { background-color: #ffc107 !important; color: #000 !important; }
            .bg-danger { background-color: #dc3545 !important; color: #fff !important; }
            .bg-secondary { background-color: #6c757d !important; color: #fff !important; }
        }
    </style>

    <script>
        function printReport() {
            // Simply trigger print - CSS handles the styling
            window.print();
        }

        // Bulk Payment Functions
        function toggleAllBills() {
            const selectAllCheckbox = document.getElementById('selectAllBills');
            const selectAllHeader = document.getElementById('selectAllHeader');
            const billCheckboxes = document.querySelectorAll('.bill-checkbox');

            const isChecked = selectAllCheckbox.checked || selectAllHeader.checked;

            billCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });

            // Sync the two select all checkboxes
            selectAllCheckbox.checked = isChecked;
            selectAllHeader.checked = isChecked;

            updateSelectedTotal();
        }

        function updateSelectedTotal() {
            const billCheckboxes = document.querySelectorAll('.bill-checkbox:checked');
            const selectedTotalElement = document.getElementById('selectedTotal');
            const bulkPayBtn = document.getElementById('bulkPayBtn');

            let total = 0;
            const selectedBills = [];

            billCheckboxes.forEach(checkbox => {
                const balance = parseFloat(checkbox.dataset.balance);
                total += balance;

                selectedBills.push({
                    billId: checkbox.dataset.billId,
                    billType: checkbox.dataset.billType,
                    balance: balance,
                    patientName: checkbox.dataset.patientName,
                    billNumber: checkbox.dataset.billNumber
                });
            });

            selectedTotalElement.textContent = 'GHS ' + total.toFixed(2);
            bulkPayBtn.disabled = selectedBills.length === 0;

            // Store selected bills in session storage for the payment modal
            sessionStorage.setItem('selectedBills', JSON.stringify(selectedBills));
        }

        function processBulkPayment() {
            const selectedBills = JSON.parse(sessionStorage.getItem('selectedBills') || '[]');

            if (selectedBills.length === 0) {
                alert('Please select at least one bill to pay.');
                return;
            }

            // Calculate total amount
            const totalAmount = selectedBills.reduce((sum, bill) => sum + bill.balance, 0);

            // Show confirmation dialog
            const confirmMessage = `You are about to pay ${selectedBills.length} bills for a total of GHS ${totalAmount.toFixed(2)}.\n\nSelected Bills:\n` +
                selectedBills.map(bill => `- ${bill.billNumber} (${bill.patientName}): GHS ${bill.balance.toFixed(2)}`).join('\n') +
                '\n\nDo you want to proceed with the payment?';

            if (confirm(confirmMessage)) {
                // Show payment modal or redirect to payment page
                showBulkPaymentModal(selectedBills, totalAmount);
            }
        }

        function showBulkPaymentModal(selectedBills, totalAmount) {
            // Create modal HTML
            const modalHTML = `
                <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-credit-card me-2"></i>Bulk Payment Confirmation
                                </h5>
                                <button type="button" class="btn-close btn-close-white" onclick="closeBulkPaymentModal()"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Payment Summary:</strong> ${selectedBills.length} bills selected for a total of GHS ${totalAmount.toFixed(2)}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Bill #</th>
                                                <th>Patient</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${selectedBills.map(bill => `
                                                <tr>
                                                    <td>${bill.billNumber}</td>
                                                    <td>${bill.patientName}</td>
                                                    <td><span class="badge bg-primary">${bill.billType.replace('_', ' ').toUpperCase()}</span></td>
                                                    <td class="text-end">GHS ${bill.balance.toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <th colspan="3" class="text-end">Total Amount:</th>
                                                <th class="text-end text-white">GHS ${totalAmount.toFixed(2)}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Payment Method</label>
                                        <select class="form-select" id="paymentMethod">
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="mobile_money">Mobile Money</option>
                                            <option value="card">Card Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Reference Number</label>
                                        <input type="text" class="form-control" id="referenceNumber" placeholder="Payment reference (optional)">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Notes</label>
                                        <textarea class="form-control" id="paymentNotes" rows="3" placeholder="Additional payment notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeBulkPaymentModal()">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="button" class="btn btn-success" onclick="submitBulkPayment()">
                                    <i class="fas fa-check me-1"></i>Confirm Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to page
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeBulkPaymentModal() {
            const modal = document.querySelector('.modal.show');
            if (modal) {
                modal.remove();
            }
        }

        function submitBulkPayment() {
            const selectedBills = JSON.parse(sessionStorage.getItem('selectedBills') || '[]');
            const paymentMethod = document.getElementById('paymentMethod').value;
            const referenceNumber = document.getElementById('referenceNumber').value;
            const paymentNotes = document.getElementById('paymentNotes').value;

            // Show loading state
            const confirmBtn = document.querySelector('.modal-footer .btn-success');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            confirmBtn.disabled = true;

            // Prepare payment data
            const paymentData = {
                bills: selectedBills,
                payment_method: paymentMethod,
                reference_number: referenceNumber,
                notes: paymentNotes,
                company_id: {{ $company->id }},
                _token: '{{ csrf_token() }}'
            };

            // Submit payment via AJAX
            fetch('{{ route("companies.bulk-payment", $company->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Bulk payment processed successfully!');
                    closeBulkPaymentModal();
                    // Refresh the page to show updated balances
                    window.location.reload();
                } else {
                    alert('Error processing payment: ' + data.message);
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error processing payment. Please try again.');
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedTotal();
        });
    </script>
@endsection
