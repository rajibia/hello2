<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
            .badge {
                background-color: transparent !important;
                color: #000 !important;
                border: 1px solid #ddd;
            }
            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .print-table th, .print-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            .print-header {
                text-align: center;
                margin-bottom: 20px;
            }
            body * {
                visibility: hidden;
            }
            #companyClaimPrintSection, #companyClaimPrintSection * {
                visibility: visible;
            }
            #companyClaimPrintSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .card-body {
                padding: 0 !important;
            }
            .container-fluid {
                padding: 0 !important;
            }
            .table-responsive {
                overflow-x: visible !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
        .print-only {
            display: none;
        }
    </style>

    <!-- <div class="container-fluid"> -->

        <!-- Header with title and actions -->
        <div class="mb-5 no-print">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label text-gray-800">{{ __('Company Claim Report') }}</span>
                    <span class="mt-1 fw-bold fs-7">: {{ $company->name }}</span>
                </h3>
                <div class="d-flex">
                    <button type="button" class="btn btn-primary me-3" id="printBtn" wire:click="$emit('print-company-claim')">
                        <i class="fas fa-print"></i> {{ __('Print') }}
                    </button>
                    <a href="{{ route('reports.company-claim') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Companies') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">

            <!-- Filter Options -->
            <div class="mb-5 mb-xl-10 no-print">
                <div class="pt-3">
                    <!-- Date Filter Section -->
                    <div class="row mb-5">
                        <div class="col-lg-4 col-md-12">
                            <div class="d-flex flex-wrap mb-5">
                                <div class="btn-group me-5 mb-2" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'today' ? 'active' : '' }}" 
                                        wire:click="changeDateFilter('today')">
                                        <span class="fw-bold">{{ __('Today') }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'yesterday' ? 'active' : '' }}" 
                                        wire:click="changeDateFilter('yesterday')">
                                        <span class="fw-bold">{{ __('Yesterday') }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_week' ? 'active' : '' }}" 
                                        wire:click="changeDateFilter('this_week')">
                                        <span class="fw-bold">{{ __('This Week') }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_month' ? 'active' : '' }}" 
                                        wire:click="changeDateFilter('this_month')">
                                        <span class="fw-bold">{{ __('This Month') }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'last_month' ? 'active' : '' }}" 
                                        wire:click="changeDateFilter('last_month')">
                                        <span class="fw-bold">{{ __('Last Month') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group">
                                <select class="form-select" id="patient_id" wire:model="patientId">
                                    <option value="">{{ __('All Patients') }}</option>
                                    @foreach($companyPatients as $patient)
                                        <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-12">
                            <div class="d-flex align-items-center">
                                <div class="position-relative w-100">
                                    <div class="input-group date-range-picker">
                                        <span class="input-group-text bg-primary">
                                            <i class="fas fa-calendar-alt text-white"></i>
                                        </span>
                                        <input type="date" class="form-control" placeholder="Start Date" id="fromDate"
                                            wire:model="fromDate" max="{{ date('Y-m-d') }}">
                                        <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                        <input type="date" class="form-control" placeholder="End Date" id="toDate"
                                            wire:model="toDate" max="{{ date('Y-m-d') }}">
                                        <button type="button" class="btn btn-light-secondary" wire:click="resetFilters">
                                            <i class="fas fa-times"></i> {{ __('Clear') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <!-- <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="bill_type" class="form-label fw-bold">{{ __('Bill Type') }}</label>
                                <select class="form-select" id="bill_type" wire:model="billType">
                                    <option value="">{{ __('All Types') }}</option>
                                    <option value="opd_invoice">{{ __('OPD Invoice') }}</option>
                                    <option value="medicine_bill">{{ __('Medicine Bill') }}</option>
                                    <option value="ipd_bill">{{ __('IPD Bill') }}</option>
                                    <option value="pathology_test">{{ __('Pathology Test') }}</option>
                                    <option value="radiology_test">{{ __('Radiology Test') }}</option>
                                    <option value="maternity">{{ __('Maternity') }}</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="payment_status" class="form-label fw-bold">{{ __('Payment Status') }}</label>
                                <select class="form-select" id="payment_status" wire:model="paymentStatus">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="paid">{{ __('Paid') }}</option>
                                    <option value="unpaid">{{ __('Unpaid') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-5">
                <div class="col-md-3 mb-3">
                    <div class="card bg-light-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                            <h6 class="text-muted mb-2">{{ __('Total Patients') }}</h6>
                            <h2 class="fs-1 fw-bolder mb-0">{{ $patientBills['paginator']->total() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light-success h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                            <h6 class="text-muted mb-2">{{ __('Total Bills') }}</h6>
                            <h2 class="fs-1 fw-bolder mb-0">{{ $summaryData['total_bills'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light-info h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                            <h6 class="text-muted mb-2">{{ __('Total Amount') }}</h6>
                            <h2 class="fs-1 fw-bolder mb-0">{{ getCurrencySymbol() }} {{ number_format($summaryData['total_amount'], 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light-warning h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                            <h6 class="text-muted mb-2">{{ __('Unpaid Amount') }}</h6>
                            <h2 class="fs-1 fw-bolder mb-0">{{ getCurrencySymbol() }} {{ number_format($summaryData['total_due'], 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Claims -->
            <div class="card mb-5">
                <div class="card-header border-0 pt-5 pb-3">
                    <h3 class="mb-4">{{ __('Patient Claims') }}</h3>
                </div>
                <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
                    <div class="table-responsive" id="companyClaimDetailTable">
                        @forelse($patientBills['patients'] as $patient)
                            <div class="patient-section mb-5">
                                <h4 class="mb-3">{{ $patient->user->full_name }} ({{ $patient->patient_unique_id }})</h4>
                                
                                @if($patient->invoices->count() > 0 || $patient->medicine_bills->count() > 0 || $patient->ipd_bills->count() > 0 || $patient->pathologyTests->count() > 0 || $patient->radiologyTests->count() > 0 || $patient->maternity->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Bill Type') }}</th>
                                                    <th>{{ __('Bill/Invoice #') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Paid') }}</th>
                                                    <th>{{ __('Balance') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($patient->invoices as $invoice)
                                                    <tr>
                                                        <td><span class="badge bg-primary">{{ __('OPD Invoice') }}</span></td>
                                                        <td>{{ $invoice->invoice_id }}</td>
                                                        <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ number_format($invoice->amount, 2) }}</td>
                                                        <td>{{ number_format($invoice->amount - $invoice->balance, 2) }}</td>
                                                        <td>{{ number_format($invoice->balance, 2) }}</td>
                                                        <td>
                                                            @if($invoice->balance <= 0)
                                                                <span class="badge bg-success">{{ __('Paid') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                @foreach($patient->medicine_bills as $bill)
                                                    <tr>
                                                        <td><span class="badge bg-info">{{ __('Medicine Bill') }}</span></td>
                                                        <td>{{ $bill->bill_number }}</td>
                                                        <td>{{ $bill->created_at ? $bill->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ number_format($bill->net_amount, 2) }}</td>
                                                        <td>{{ number_format($bill->net_amount - $bill->balance, 2) }}</td>
                                                        <td>{{ number_format($bill->balance, 2) }}</td>
                                                        <td>
                                                            @if($bill->balance <= 0)
                                                                <span class="badge bg-success">{{ __('Paid') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                @foreach($patient->ipd_bills as $ipdPatient)
                                                    @if($ipdPatient->bill)
                                                        <tr>
                                                            <td><span class="badge bg-warning">{{ __('IPD Bill') }}</span></td>
                                                            <td>{{ $ipdPatient->bill->bill_id }}</td>
                                                            <td>{{ $ipdPatient->created_at ? $ipdPatient->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                            <td>{{ number_format($ipdPatient->bill->net_payable_amount, 2) }}</td>
                                                            <td>{{ number_format($ipdPatient->bill->total_payments, 2) }}</td>
                                                            <td>{{ number_format($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments, 2) }}</td>
                                                            <td>
                                                                @if(($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments) <= 0)
                                                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                                                @else
                                                                    <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                
                                                @foreach($patient->pathologyTests as $test)
                                                    <tr>
                                                        <td><span class="badge bg-secondary">{{ __('Pathology Test') }}</span></td>
                                                        <td>{{ $test->test_id }}</td>
                                                        <td>{{ $test->created_at ? $test->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ number_format($test->total, 2) }}</td>
                                                        <td>{{ number_format($test->total - $test->balance, 2) }}</td>
                                                        <td>{{ number_format($test->balance, 2) }}</td>
                                                        <td>
                                                            @if($test->balance <= 0)
                                                                <span class="badge bg-success">{{ __('Paid') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                @foreach($patient->radiologyTests as $test)
                                                    <tr>
                                                        <td><span class="badge bg-dark">{{ __('Radiology Test') }}</span></td>
                                                        <td>{{ $test->test_id }}</td>
                                                        <td>{{ $test->created_at ? $test->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ number_format($test->total, 2) }}</td>
                                                        <td>{{ number_format($test->total - $test->balance, 2) }}</td>
                                                        <td>{{ number_format($test->balance, 2) }}</td>
                                                        <td>
                                                            @if($test->balance <= 0)
                                                                <span class="badge bg-success">{{ __('Paid') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                @foreach($patient->maternity as $maternity)
                                                    <tr>
                                                        <td><span class="badge bg-danger">{{ __('Maternity') }}</span></td>
                                                        <td>{{ $maternity->case_id }}</td>
                                                        <td>{{ $maternity->created_at ? $maternity->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ number_format($maternity->standard_charge, 2) }}</td>
                                                        <td>{{ number_format($maternity->standard_charge - $maternity->balance, 2) }}</td>
                                                        <td>{{ number_format($maternity->balance, 2) }}</td>
                                                        <td>
                                                            @if($maternity->balance <= 0)
                                                                <span class="badge bg-success">{{ __('Paid') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @php
                                                    $totalAmount = 0;
                                                    $totalPaid = 0;
                                                    $totalBalance = 0;
                                                    
                                                    // Calculate totals for invoices
                                                    foreach($patient->invoices as $invoice) {
                                                        $totalAmount += $invoice->amount;
                                                        $totalPaid += ($invoice->amount - $invoice->balance);
                                                        $totalBalance += $invoice->balance;
                                                    }
                                                    
                                                    // Calculate totals for medicine bills
                                                    foreach($patient->medicine_bills as $bill) {
                                                        $totalAmount += $bill->net_amount;
                                                        $totalPaid += ($bill->net_amount - $bill->balance);
                                                        $totalBalance += $bill->balance;
                                                    }
                                                    
                                                    // Calculate totals for IPD bills
                                                    foreach($patient->ipd_bills as $ipdPatient) {
                                                        if($ipdPatient->bill) {
                                                            $totalAmount += $ipdPatient->bill->net_payable_amount;
                                                            $totalPaid += $ipdPatient->bill->total_payments;
                                                            $totalBalance += ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments);
                                                        }
                                                    }
                                                    
                                                    // Calculate totals for pathology tests
                                                    foreach($patient->pathologyTests as $test) {
                                                        $totalAmount += $test->total;
                                                        $totalPaid += ($test->total - $test->balance);
                                                        $totalBalance += $test->balance;
                                                    }
                                                    
                                                    // Calculate totals for radiology tests
                                                    foreach($patient->radiologyTests as $test) {
                                                        $totalAmount += $test->total;
                                                        $totalPaid += ($test->total - $test->balance);
                                                        $totalBalance += $test->balance;
                                                    }
                                                    
                                                    // Calculate totals for maternity
                                                    foreach($patient->maternity as $maternity) {
                                                        $totalAmount += $maternity->standard_charge;
                                                        $totalPaid += ($maternity->standard_charge - $maternity->balance);
                                                        $totalBalance += $maternity->balance;
                                                    }
                                                @endphp
                                                <tr class="table-primary fw-bold">
                                                    <td colspan="3" class="text-end">{{ __('Total') }}</td>
                                                    <td>{{ number_format($totalAmount, 2) }}</td>
                                                    <td>{{ number_format($totalPaid, 2) }}</td>
                                                    <td>{{ number_format($totalBalance, 2) }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        {{ __('No data found in the selected date range.') }}
                                    </div>
                                @endif
                                
                                <hr class="mt-4">
                            </div>
                        @empty
                            <div class="text-center">
                                {{ __('No data found in the selected date range.') }}
                            </div>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-end pt-5 no-print">
                        {{ $patientBills['paginator']->links() }}
                    </div>
                </div>
            </div>
            
            <!-- Print Section (Hidden) -->
            <div class="d-none print-only" id="companyClaimPrintSection">
                <div class="print-header">
                    <h1>{{env('APP_NAME')}}</h1>
                    <h2>{{ __('Company Claim Report') }}</h2>
                    <h3>{{ $company->name }}</h3>
                    <h5>{{ __('Period') }}: {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M, Y') : __('All time') }} - {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M, Y') : __('Present') }}</h5>
                </div>
                
                <div class="summary mb-4">
                    <h4>{{ __('Summary') }}</h4>
                    <table class="print-table">
                        <tr>
                            <th>{{ __('Total Patients') }}</th>
                            <th>{{ __('Total Bills') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Unpaid Amount') }}</th>
                        </tr>
                        <tr>
                            <td>{{ $patientBills['paginator']->total() }}</td>
                            <td>{{ $summaryData['total_bills'] }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($summaryData['total_amount'], 2) }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($summaryData['total_due'], 2) }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="patient-claims mb-4">
                    <h4>{{ __('Patient Claims') }}</h4>
                    
                    @forelse($patientBills['patients'] as $patient)
                        <div class="patient-section mb-4">
                            <h5 class="mb-3">{{ $patient->user->full_name }} ({{ $patient->patient_unique_id }})</h5>
                            
                            @if($patient->invoices->count() > 0 || $patient->medicine_bills->count() > 0 || $patient->ipd_bills->count() > 0 || $patient->pathologyTests->count() > 0 || $patient->radiologyTests->count() > 0 || $patient->maternity->count() > 0)
                                <table class="print-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Bill Type') }}</th>
                                            <th>{{ __('Bill/Invoice #') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Paid') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($patient->invoices as $invoice)
                                            <tr>
                                                <td>{{ __('OPD Invoice') }}</td>
                                                <td>{{ $invoice->invoice_id }}</td>
                                                <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : 'N/A' }}</td>
                                                <td>{{ number_format($invoice->amount, 2) }}</td>
                                                <td>{{ number_format($invoice->amount - $invoice->balance, 2) }}</td>
                                                <td>{{ number_format($invoice->balance, 2) }}</td>
                                                <td>
                                                    @if($invoice->balance <= 0)
                                                        {{ __('Paid') }}
                                                    @else
                                                        {{ __('Unpaid') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @foreach($patient->medicine_bills as $bill)
                                            <tr>
                                                <td>{{ __('Medicine Bill') }}</td>
                                                <td>{{ $bill->bill_number }}</td>
                                                <td>{{ $bill->created_at ? $bill->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>{{ number_format($bill->net_amount, 2) }}</td>
                                                <td>{{ number_format($bill->net_amount - $bill->balance, 2) }}</td>
                                                <td>{{ number_format($bill->balance, 2) }}</td>
                                                <td>
                                                    @if($bill->balance <= 0)
                                                        {{ __('Paid') }}
                                                    @else
                                                        {{ __('Unpaid') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @foreach($patient->ipd_bills as $ipdPatient)
                                            @if($ipdPatient->bill)
                                                <tr>
                                                    <td>{{ __('IPD Bill') }}</td>
                                                    <td>{{ $ipdPatient->bill->bill_id }}</td>
                                                    <td>{{ $ipdPatient->created_at ? $ipdPatient->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                    <td>{{ number_format($ipdPatient->bill->net_payable_amount, 2) }}</td>
                                                    <td>{{ number_format($ipdPatient->bill->total_payments, 2) }}</td>
                                                    <td>{{ number_format($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments, 2) }}</td>
                                                    <td>
                                                        @if(($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments) <= 0)
                                                            {{ __('Paid') }}
                                                        @else
                                                            {{ __('Unpaid') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        
                                        @foreach($patient->pathologyTests as $test)
                                            <tr>
                                                <td>{{ __('Pathology Test') }}</td>
                                                <td>{{ $test->test_id }}</td>
                                                <td>{{ $test->created_at ? $test->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>{{ number_format($test->total, 2) }}</td>
                                                <td>{{ number_format($test->total - $test->balance, 2) }}</td>
                                                <td>{{ number_format($test->balance, 2) }}</td>
                                                <td>
                                                    @if($test->balance <= 0)
                                                        {{ __('Paid') }}
                                                    @else
                                                        {{ __('Unpaid') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @foreach($patient->radiologyTests as $test)
                                            <tr>
                                                <td>{{ __('Radiology Test') }}</td>
                                                <td>{{ $test->test_id }}</td>
                                                <td>{{ $test->created_at ? $test->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>{{ number_format($test->total, 2) }}</td>
                                                <td>{{ number_format($test->total - $test->balance, 2) }}</td>
                                                <td>{{ number_format($test->balance, 2) }}</td>
                                                <td>
                                                    @if($test->balance <= 0)
                                                        {{ __('Paid') }}
                                                    @else
                                                        {{ __('Unpaid') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @foreach($patient->maternity as $maternity)
                                            <tr>
                                                <td>{{ __('Maternity') }}</td>
                                                <td>{{ $maternity->case_id }}</td>
                                                <td>{{ $maternity->created_at ? $maternity->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>{{ number_format($maternity->standard_charge, 2) }}</td>
                                                <td>{{ number_format($maternity->standard_charge - $maternity->balance, 2) }}</td>
                                                <td>{{ number_format($maternity->balance, 2) }}</td>
                                                <td>
                                                    @if($maternity->balance <= 0)
                                                        {{ __('Paid') }}
                                                    @else
                                                        {{ __('Unpaid') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @php
                                            $totalAmount = 0;
                                            $totalPaid = 0;
                                            $totalBalance = 0;
                                            
                                            // Calculate totals for invoices
                                            foreach($patient->invoices as $invoice) {
                                                $totalAmount += $invoice->amount;
                                                $totalPaid += ($invoice->amount - $invoice->balance);
                                                $totalBalance += $invoice->balance;
                                            }
                                            
                                            // Calculate totals for medicine bills
                                            foreach($patient->medicine_bills as $bill) {
                                                $totalAmount += $bill->net_amount;
                                                $totalPaid += ($bill->net_amount - $bill->balance);
                                                $totalBalance += $bill->balance;
                                            }
                                            
                                            // Calculate totals for IPD bills
                                            foreach($patient->ipd_bills as $ipdPatient) {
                                                if($ipdPatient->bill) {
                                                    $totalAmount += $ipdPatient->bill->net_payable_amount;
                                                    $totalPaid += $ipdPatient->bill->total_payments;
                                                    $totalBalance += ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments);
                                                }
                                            }
                                            
                                            // Calculate totals for pathology tests
                                            foreach($patient->pathologyTests as $test) {
                                                $totalAmount += $test->total;
                                                $totalPaid += ($test->total - $test->balance);
                                                $totalBalance += $test->balance;
                                            }
                                            
                                            // Calculate totals for radiology tests
                                            foreach($patient->radiologyTests as $test) {
                                                $totalAmount += $test->total;
                                                $totalPaid += ($test->total - $test->balance);
                                                $totalBalance += $test->balance;
                                            }
                                            
                                            // Calculate totals for maternity
                                            foreach($patient->maternity as $maternity) {
                                                $totalAmount += $maternity->standard_charge;
                                                $totalPaid += ($maternity->standard_charge - $maternity->balance);
                                                $totalBalance += $maternity->balance;
                                            }
                                        @endphp
                                        <tr style="font-weight: bold; background-color: #e9ecef;">
                                            <td colspan="3" style="text-align: right;">{{ __('Total for Patient') }}</td>
                                            <td>{{ number_format($totalAmount, 2) }}</td>
                                            <td>{{ number_format($totalPaid, 2) }}</td>
                                            <td>{{ number_format($totalBalance, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">
                                    {{ __('No data found for this patient in the selected date range.') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center">
                            {{ __('No patient claims found in the selected date range.') }}
                        </div>
                    @endforelse
                </div>
                
                <!-- Grand Total Section -->
                <div class="grand-total mb-4">
                    <h4>{{ __('Grand Total') }}</h4>
                    <table class="print-table">
                        <tr style="font-weight: bold; background-color: #e9ecef;">
                            <td style="width: 40%; text-align: right;">{{ __('Total Amount') }}</td>
                            <td style="width: 20%;">{{ getCurrencySymbol() }} {{ number_format($summaryData['total_amount'], 2) }}</td>
                            <td style="width: 20%; text-align: right;">{{ __('Total Paid') }}</td>
                            <td style="width: 20%;">{{ getCurrencySymbol() }} {{ number_format($summaryData['total_paid'], 2) }}</td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #e9ecef;">
                            <td colspan="2" style="text-align: right;">{{ __('Total Due Amount') }}</td>
                            <td colspan="2" style="color: #dc3545;">{{ getCurrencySymbol() }} {{ number_format($summaryData['total_due'], 2) }}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Print footer -->
                <div class="print-footer">
                    <p>{{ __('Generated on') }}: {{ now()->format('d M, Y H:i:s') }}</p>
                </div>
            </div>
            

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle date filter changes
            const fromDateInput = document.getElementById('fromDate');
            const toDateInput = document.getElementById('toDate');
            
            if (fromDateInput && toDateInput) {
                fromDateInput.addEventListener('change', function() {
                    if (toDateInput.value && this.value > toDateInput.value) {
                        toDateInput.value = this.value;
                        @this.set('toDate', this.value);
                    }
                });
                
                toDateInput.addEventListener('change', function() {
                    if (fromDateInput.value && this.value < fromDateInput.value) {
                        fromDateInput.value = this.value;
                        @this.set('fromDate', this.value);
                    }
                });
            }
            
            // Handle print functionality
            window.Livewire.on('print-company-claim', function() {
                const printContent = document.getElementById('companyClaimPrintSection').innerHTML;
                printReport(printContent);
            });
        });
        
        function printReport(printContent) {
            // Open a new tab for printing
            const printWindow = window.open('', '_blank');
            
            // Write the print content to the new window
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>{{ __('Company Claim Report') }}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            max-width: 1000px; 
                            margin: 0 auto;
                        }
                        .print-header { 
                            text-align: center; 
                            margin-bottom: 30px; 
                        }
                        .print-header h2 { 
                            margin-bottom: 5px; 
                        }
                        .print-header h3 { 
                            margin-bottom: 5px; 
                        }
                        .print-header h4 { 
                            margin-bottom: 5px; 
                            font-weight: normal; 
                        }
                        .print-header h5 { 
                            margin-bottom: 5px; 
                            font-weight: normal; 
                        }
                        .print-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        .print-table th, .print-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        .print-table th {
                            background-color: #f2f2f2;
                        }
                        .print-footer {
                            margin-top: 30px;
                            text-align: center;
                            font-size: 12px;
                            color: #666;
                        }
                        .grand-total {
                            margin-top: 30px;
                            margin-bottom: 30px;
                        }
                        .grand-total h4 {
                            margin-bottom: 10px;
                            font-weight: bold;
                        }
                        .grand-total .print-table tr {
                            background-color: #f8f9fa;
                        }
                        .grand-total .print-table td {
                            padding: 10px;
                            font-weight: bold;
                        }
                        /* Print buttons */
                                .no-print {
                                    text-align: center;
                                    margin-top: 30px;
                                    margin-bottom: 20px;
                                }
                                /* Reset button styles for print buttons */
                                .no-print .btn {
                                    display: inline-block !important;
                                    font-weight: 500 !important;
                                    text-align: center !important;
                                    vertical-align: middle !important;
                                    user-select: none !important;
                                    padding: 0.65rem 1rem !important;
                                    font-size: 1rem !important;
                                    line-height: 1.5 !important;
                                    border-radius: 0.42rem !important;
                                    cursor: pointer !important;
                                    margin: 0 5px !important;
                                }
                                .no-print .btn-primary {
                                    color: #fff !important;
                                    background-color: #3699FF !important;
                                    border: 1px solid #3699FF !important;
                                }
                                .no-print .btn-secondary {
                                    color: #3F4254 !important;
                                    background-color: #E4E6EF !important;
                                    border: 1px solid #E4E6EF !important;
                                }
                        @media print {
                            .no-print {
                                display: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        ${printContent}
                    </div>
                    <div class="text-center mt-4 no-print">
                        <button type="button" class="btn btn-primary btn-print" onclick="window.print();" style="display: inline-block !important;">
                            Print Now
                        </button>
                        <button type="button" class="btn btn-secondary btn-close" onclick="window.close();" style="display: inline-block !important;">
                            Close
                        </button>
                    </div>
                </body>
                </html>
            `);
            
            // Finish loading the page
            printWindow.document.close();
            printWindow.focus();
            // Add a small delay before printing to ensure content is fully loaded
            setTimeout(function() {
                printWindow.print();
            }, 500);
        }
    </script>
        </div>
    <!-- </div> -->
</div>
