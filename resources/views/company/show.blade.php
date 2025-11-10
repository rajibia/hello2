@extends('layouts.app')
@section('title')
    {{ __('messages.patient.new_company') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div>
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="fas fa-credit-card me-1"></i>
                    Make Payment
                </button>
                <a href="{{ route('companies.claims', $company->id) }}"
                   class="btn btn-success me-2">Generate Claims Report</a>
                <a href="{{ route('companies.index') }}"
                   class="btn btn-outline-primary">View Company</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                @if(session('processed_invoices'))
                    <hr>
                    <h6>Payment Details:</h6>
                    <ul class="mb-0">
                        @foreach(session('processed_invoices') as $processedInvoice)
                            <li>
                                <strong>Invoice #{{ $processedInvoice['invoice_id'] }}</strong> - {{ $processedInvoice['patient_name'] }}
                                <br>
                                <small class="text-muted">
                                    Payment: ${{ number_format($processedInvoice['payment_amount'], 2) }} |
                                    Balance: ${{ number_format($processedInvoice['previous_balance'], 2) }} → ${{ number_format($processedInvoice['new_balance'], 2) }}
                                    @if($processedInvoice['status_changed'])
                                        <span class="badge bg-success ms-1">PAID</span>
                                    @endif
                                </small>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3>{{$company->name}} Details</h3>

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

        <div class="card mb-4">
            <div class="card-body">
                @php
                    $totalDebt = 0;
                    if ($company && $company->patients) {
                        foreach ($company->patients as $patient) {
                            // OPD Invoices
                            if ($patient && $patient->invoices) {
                                foreach ($patient->invoices as $invoice) {
                                    $totalDebt += $invoice->balance ?? 0;
                                }
                            }
                            // Medicine Bills
                            if ($patient && $patient->medicine_bills) {
                                foreach ($patient->medicine_bills as $medicineBill) {
                                    $totalDebt += $medicineBill->balance_amount ?? 0;
                                }
                            }
                            // IPD Bills
                            if ($patient && $patient->ipd_bills) {
                                foreach ($patient->ipd_bills as $ipdPatient) {
                                    if ($ipdPatient->bill) {
                                        $totalDebt += ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments) ?? 0;
                                    }
                                }
                            }
                            // Pathology Tests
                            if ($patient && $patient->pathologyTests) {
                                foreach ($patient->pathologyTests as $pathologyTest) {
                                    $totalDebt += $pathologyTest->balance ?? 0;
                                }
                            }
                            // Radiology Tests
                            if ($patient && $patient->radiologyTests) {
                                foreach ($patient->radiologyTests as $radiologyTest) {
                                    $totalDebt += $radiologyTest->balance ?? 0;
                                }
                            }
                            // Maternity Bills
                            if ($patient && $patient->maternity) {
                                foreach ($patient->maternity as $maternity) {
                                    $totalDebt += $maternity->balance ?? 0;
                                }
                            }
                        }
                    }
                @endphp
                <h5>Overview</h5>
                <p><strong>Total Outstanding
                        Debt:</strong> {{strtoupper(getCurrentCurrency())}} {{ number_format($totalDebt, 2) }}</p>
                <p><small>Click on bill number to view details (All bill types shown below each patient)</small></p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-tabs-custom border-0" id="companyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active d-flex align-items-center px-4 py-3" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices" aria-selected="true">
                            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                            <span class="fw-semibold">All Bills & Payments</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-4 py-3" id="payment-history-tab" data-bs-toggle="tab" data-bs-target="#payment-history" type="button" role="tab" aria-controls="payment-history" aria-selected="false">
                            <i class="fas fa-history me-2 text-success"></i>
                            <span class="fw-semibold">Payment History</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <style>
        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 0;
            background: transparent;
            color: #6c757d;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-tabs-custom .nav-link:hover {
            background: #f8f9fa;
            color: #495057;
        }
        .nav-tabs-custom .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        /* Payment Summary Cards */
        .payment-summary-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .payment-summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }

        .payment-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* Modern Table Styles */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .payment-row {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .payment-row:hover {
            background-color: #f8f9fa;
            border-left-color: #007bff;
            transform: translateX(2px);
        }

        .date-badge {
            text-align: center;
            min-width: 40px;
        }

        .patient-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important;
        }
        </style>

        <div class="tab-content" id="companyTabsContent">
            {{-- Invoices Tab --}}
            <div class="tab-pane fade show active" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-search me-2"></i>
                            <h6 class="mb-0 fw-semibold">Search & Filter All Bills</h6>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <form method="GET" action="#">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-semibold">Search Patient/Bill</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search patients or bill numbers..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold">From Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar-alt text-muted"></i>
                                        </span>
                                        <input type="date" name="from" class="form-control border-start-0" value="{{ request('from') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-semibold">To Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar-alt text-muted"></i>
                                        </span>
                                        <input type="date" name="to" class="form-control border-start-0" value="{{ request('to') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 btn-gradient">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>All Bills</th>
                </tr>
                </thead>
                <tbody>
                @forelse($company->patients as $patient)
                    <tr>
                        <td></td>
                        <td>{{$patient->user->first_name.' '.$patient->user->last_name}}</td>
                        <td>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" class="form-check-input" onchange="toggleAllBills()">
                                    </th>
                                    <th>Bill Type</th>
                                    <th>Bill No</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalAmount = 0;
                                    $totalBalance = 0;

                                    // Calculate totals for all bill types
                                    if ($patient->invoices) {
                                        $totalAmount += $patient->invoices->sum('amount');
                                        $totalBalance += $patient->invoices->sum('balance');
                                    }
                                    if ($patient->medicine_bills) {
                                        $totalAmount += $patient->medicine_bills->sum('total');
                                        $totalBalance += $patient->medicine_bills->sum('balance_amount');
                                    }
                                    if ($patient->ipd_bills) {
                                        $totalAmount += $patient->ipd_bills->sum(function($ipdPatient) {
                                            return $ipdPatient->bill ? $ipdPatient->bill->net_payable_amount : 0;
                                        });
                                        $totalBalance += $patient->ipd_bills->sum(function($ipdPatient) {
                                            if ($ipdPatient->bill) {
                                                return $ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments;
                                            }
                                            return 0;
                                        });
                                    }
                                    if ($patient->pathologyTests) {
                                        $totalAmount += $patient->pathologyTests->sum('total');
                                        $totalBalance += $patient->pathologyTests->sum('balance');
                                    }
                                    if ($patient->radiologyTests) {
                                        $totalAmount += $patient->radiologyTests->sum('total');
                                        $totalBalance += $patient->radiologyTests->sum('balance');
                                    }
                                    if ($patient->maternity) {
                                        $totalAmount += $patient->maternity->sum('standard_charge');
                                        $totalBalance += $patient->maternity->sum('balance');
                                    }

                                    $totalPaid = $totalAmount - $totalBalance;
                                @endphp

                                {{-- OPD Invoices --}}
                                @if($patient->invoices)
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
                                        <td><span class="badge bg-primary">OPD Invoice</span></td>
                                        <td>
                                            <a href="{{ route('patient.invoices.show', $invoice->id) }}">
                                                {{ $invoice->invoice_id }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($invoice->balance > 0)
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount, 2) }}</td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->balance, 2) }}</td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal"
                                               data-bs-target="#view{{ $invoice->invoice_id }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                {{-- Medicine Bills --}}
                                @if($patient->medicine_bills)
                                    @foreach($patient->medicine_bills as $medicineBill)
                                    <tr>
                                        <td class="text-center">
                                            @if($medicineBill->balance_amount > 0)
                                                <input type="checkbox" class="form-check-input bill-checkbox"
                                                       data-bill-id="{{ $medicineBill->id }}"
                                                       data-bill-type="medicine_bill"
                                                       data-balance="{{ $medicineBill->balance_amount }}"
                                                       data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                       data-bill-number="{{ $medicineBill->bill_number }}"
                                                       onchange="updateSelectedTotal()">
                                            @endif
                                        </td>
                                        <td><span class="badge bg-warning">Medicine Bill</span></td>
                                        <td>
                                            <a href="{{ route('medicine-bills.show', $medicineBill->id) }}">
                                                {{ $medicineBill->bill_number }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($medicineBill->balance_amount > 0)
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->total, 2) }}</td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->balance_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('medicine-bills.show', $medicineBill->id) }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                {{-- IPD Bills --}}
                                @if($patient->ipd_bills)
                                    @foreach($patient->ipd_bills as $ipdPatient)
                                        @if($ipdPatient->bill)
                                            @php
                                                $ipdBill = $ipdPatient->bill;
                                                $ipdBalance = $ipdBill->net_payable_amount - $ipdBill->total_payments;
                                            @endphp
                                            @if($ipdBalance > 0)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input bill-checkbox"
                                                           data-bill-id="{{ $ipdPatient->id }}"
                                                           data-bill-type="ipd_bill"
                                                           data-balance="{{ $ipdBalance }}"
                                                           data-patient-name="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                           data-bill-number="IPD-{{ $ipdPatient->ipd_number }}"
                                                           onchange="updateSelectedTotal()">
                                                </td>
                                                <td><span class="badge bg-info">IPD Bill</span></td>
                                                <td>
                                                    <a href="{{ route('ipd.patient.show', $ipdPatient->id) }}">
                                                        IPD-{{ $ipdPatient->ipd_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">Unpaid</span>
                                                </td>
                                                <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBill->net_payable_amount, 2) }}</td>
                                                <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBalance, 2) }}</td>
                                                <td>
                                                    <a href="{{ route('ipd.patient.show', $ipdPatient->id) }}"
                                                       class="btn btn-sm btn-info">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Pathology Tests --}}
                                @if($patient->pathologyTests)
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
                                        <td><span class="badge bg-secondary">Pathology Test</span></td>
                                        <td>
                                            <a href="{{ route('pathology.test.show', $pathologyTest->id) }}">
                                                {{ $pathologyTest->bill_no }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($pathologyTest->balance > 0)
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->total, 2) }}</td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('pathology.test.show', $pathologyTest->id) }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                {{-- Radiology Tests --}}
                                @if($patient->radiologyTests)
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
                                        <td><span class="badge bg-dark">Radiology Test</span></td>
                                        <td>
                                            <a href="{{ route('radiology.test.show', $radiologyTest->id) }}">
                                                {{ $radiologyTest->bill_no }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($radiologyTest->balance > 0)
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->total, 2) }}</td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('radiology.test.show', $radiologyTest->id) }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                {{-- Maternity Bills --}}
                                @if($patient->maternity)
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
                                        <td><span class="badge bg-success">Maternity</span></td>
                                        <td>
                                            <a href="{{ route('maternity.patient.show', $maternity->id) }}">
                                                MAT-{{ $maternity->id }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($maternity->balance > 0)
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->standard_charge, 2) }}</td>
                                        <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('maternity.patient.show', $maternity->id) }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </td>
                        <td>                                    <!-- Summary Button -->
                            <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal"
                                    data-bs-target="#summaryModal{{ $patient->id }}">
                                View Cost Summary
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            <p>No Patients</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Payment section removed - now in modal --}}
            </div>

            {{-- Payment History Tab --}}
            <div class="tab-pane fade" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-info text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line me-2"></i>
                                <h5 class="mb-0 fw-semibold">Payment Analytics & History</h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-1"></i>
                                <small>Last updated: {{ now()->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $allPayments = collect();
                            if ($company && $company->patients) {
                                foreach ($company->patients as $patient) {
                                    // Get payments from invoices
                                    if ($patient && $patient->invoices) {
                                        foreach ($patient->invoices as $invoice) {
                                            if ($invoice && $invoice->payments) {
                                                foreach ($invoice->payments as $payment) {
                                                    $allPayments->push([
                                                        'payment' => $payment,
                                                        'bill' => $invoice,
                                                        'bill_type' => 'OPD Invoice',
                                                        'bill_number' => $invoice->invoice_id,
                                                        'patient' => $patient
                                                    ]);
                                                }
                                            }
                                        }
                                    }

                                    // Get payments from medicine bills
                                    if ($patient && $patient->medicine_bills) {
                                        foreach ($patient->medicine_bills as $medicineBill) {
                                            if ($medicineBill && $medicineBill->payments) {
                                                foreach ($medicineBill->payments as $payment) {
                                                    $allPayments->push([
                                                        'payment' => $payment,
                                                        'bill' => $medicineBill,
                                                        'bill_type' => 'Medicine Bill',
                                                        'bill_number' => $medicineBill->bill_number,
                                                        'patient' => $patient
                                                    ]);
                                                }
                                            }
                                        }
                                    }

                                    // Get payments from pathology tests
                                    if ($patient && $patient->pathologyTests) {
                                        foreach ($patient->pathologyTests as $pathologyTest) {
                                            if ($pathologyTest && $pathologyTest->payments) {
                                                foreach ($pathologyTest->payments as $payment) {
                                                    $allPayments->push([
                                                        'payment' => $payment,
                                                        'bill' => $pathologyTest,
                                                        'bill_type' => 'Pathology Test',
                                                        'bill_number' => $pathologyTest->test_id,
                                                        'patient' => $patient
                                                    ]);
                                                }
                                            }
                                        }
                                    }

                                    // Get payments from radiology tests
                                    if ($patient && $patient->radiologyTests) {
                                        foreach ($patient->radiologyTests as $radiologyTest) {
                                            if ($radiologyTest && $radiologyTest->payments) {
                                                foreach ($radiologyTest->payments as $payment) {
                                                    $allPayments->push([
                                                        'payment' => $payment,
                                                        'bill' => $radiologyTest,
                                                        'bill_type' => 'Radiology Test',
                                                        'bill_number' => $radiologyTest->test_id,
                                                        'patient' => $patient
                                                    ]);
                                                }
                                            }
                                        }
                                    }

                                    // Get payments from maternity
                                    if ($patient && $patient->maternity) {
                                        foreach ($patient->maternity as $maternity) {
                                            if ($maternity && $maternity->payments) {
                                                foreach ($maternity->payments as $payment) {
                                                    $allPayments->push([
                                                        'payment' => $payment,
                                                        'bill' => $maternity,
                                                        'bill_type' => 'Maternity',
                                                        'bill_number' => 'MAT-' . $maternity->id,
                                                        'patient' => $patient
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $allPayments = $allPayments->sortByDesc(function($item) {
                                return $item['payment']->created_at;
                            });
                        @endphp

                        @if($allPayments->count() > 0)
                            {{-- Payment Summary Cards --}}
                            <div class="row g-4 mb-5">
                                @php
                                    $totalPayments = $allPayments->sum(function($item) { return $item['payment']->amount; });
                                    $cashPayments = $allPayments->where('payment.payment_type', 0)->sum(function($item) { return $item['payment']->amount; });
                                    $chequePayments = $allPayments->where('payment.payment_type', 1)->sum(function($item) { return $item['payment']->amount; });
                                    $otherPayments = $allPayments->where('payment.payment_type', 2)->sum(function($item) { return $item['payment']->amount; });
                                    $paymentCount = $allPayments->count();
                                @endphp
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 shadow-sm h-100 payment-summary-card">
                                        <div class="card-body text-center p-4">
                                            <div class="payment-icon mb-3">
                                                <i class="fas fa-chart-pie fa-2x text-primary"></i>
                                            </div>
                                            <h4 class="fw-bold text-primary mb-2">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($totalPayments, 2) }}</h4>
                                            <p class="text-muted mb-1 fw-semibold">Total Payments</p>
                                            <small class="text-muted">{{ $paymentCount }} transactions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 shadow-sm h-100 payment-summary-card">
                                        <div class="card-body text-center p-4">
                                            <div class="payment-icon mb-3">
                                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                            </div>
                                            <h4 class="fw-bold text-success mb-2">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($cashPayments, 2) }}</h4>
                                            <p class="text-muted mb-1 fw-semibold">Cash Payments</p>
                                            <small class="text-muted">{{ $allPayments->where('payment.payment_type', 0)->count() }} transactions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 shadow-sm h-100 payment-summary-card">
                                        <div class="card-body text-center p-4">
                                            <div class="payment-icon mb-3">
                                                <i class="fas fa-file-invoice fa-2x text-info"></i>
                                            </div>
                                            <h4 class="fw-bold text-info mb-2">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($chequePayments, 2) }}</h4>
                                            <p class="text-muted mb-1 fw-semibold">Cheque Payments</p>
                                            <small class="text-muted">{{ $allPayments->where('payment.payment_type', 1)->count() }} transactions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 shadow-sm h-100 payment-summary-card">
                                        <div class="card-body text-center p-4">
                                            <div class="payment-icon mb-3">
                                                <i class="fas fa-credit-card fa-2x text-warning"></i>
                                            </div>
                                            <h4 class="fw-bold text-warning mb-2">{{ strtoupper(getCurrentCurrency()) }} {{ number_format($otherPayments, 2) }}</h4>
                                            <p class="text-muted mb-1 fw-semibold">Other Payments</p>
                                            <small class="text-muted">{{ $allPayments->where('payment.payment_type', 2)->count() }} transactions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment History Table --}}
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-primary text-white py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-table me-2"></i>
                                        <h5 class="mb-0 fw-bold">Payment Transactions</h5>
                                        <div class="ms-auto">
                                            <small><i class="fas fa-clock me-1"></i>{{ $allPayments->count() }} total records</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 modern-table">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>Date
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-file-invoice text-muted me-2"></i>Bill #
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-user text-muted me-2"></i>Patient
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-credit-card text-muted me-2"></i>Payment Method
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-dollar-sign text-muted me-2"></i>Amount
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-check-circle text-muted me-2"></i>Status
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-sticky-note text-muted me-2"></i>Note
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allPayments as $paymentData)
                                                    @php
                                                        $payment = $paymentData['payment'];
                                                        $bill = $paymentData['bill'];
                                                        $patient = $paymentData['patient'];
                                                    @endphp
                                                    <tr class="payment-row">
                                                        <td class="py-3 px-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="date-badge me-2">
                                                                    <small class="text-muted">{{ $payment->created_at->format('M') }}</small>
                                                                    <div class="fw-bold">{{ $payment->created_at->format('d') }}</div>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold">{{ $payment->created_at->format('Y') }}</div>
                                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            @php
                                                                $billLink = '';
                                                                $billNumber = $paymentData['bill_number'];
                                                                $billType = $paymentData['bill_type'];

                                                                switch($billType) {
                                                                    case 'OPD Invoice':
                                                                        $billLink = route('patient.invoices.show', $bill->id);
                                                                        break;
                                                                    case 'Medicine Bill':
                                                                        $billLink = route('medicine-bills.show', $bill->id);
                                                                        break;
                                                                    case 'IPD Bill':
                                                                        $billLink = route('ipd.patient.show', $bill->ipd_patient_department_id);
                                                                        break;
                                                                    case 'Pathology Test':
                                                                        $billLink = route('pathology.test.show', $bill->id);
                                                                        break;
                                                                    case 'Radiology Test':
                                                                        $billLink = route('radiology.test.show', $bill->id);
                                                                        break;
                                                                    case 'Maternity':
                                                                        $billLink = route('maternity.patient.show', $bill->id);
                                                                        break;
                                                                    default:
                                                                        $billLink = '#';
                                                                }
                                                            @endphp
                                                            <a href="{{ $billLink }}" class="text-decoration-none">
                                                                <span class="badge bg-light text-dark border fw-semibold">{{ $billNumber }}</span>
                                                                <small class="text-muted d-block">{{ $billType }}</small>
                                                            </a>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="patient-avatar me-2">
                                                                    <i class="fas fa-user-circle fa-lg text-primary"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold">{{ $patient->user->first_name }} {{ $patient->user->last_name }}</div>
                                                                    <small class="text-muted">{{ $patient->user->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            @php
                                                                $paymentMethods = [
                                                                    0 => ['name' => 'Cash', 'icon' => 'money-bill-wave', 'class' => 'success'],
                                                                    1 => ['name' => 'Cheque', 'icon' => 'file-invoice', 'class' => 'info'],
                                                                    2 => ['name' => 'Other', 'icon' => 'credit-card', 'class' => 'warning']
                                                                ];
                                                                $method = $paymentMethods[$payment->payment_type] ?? ['name' => 'Unknown', 'icon' => 'question', 'class' => 'secondary'];
                                                            @endphp
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-{{ $method['icon'] }} text-{{ $method['class'] }} me-2"></i>
                                                                <span class="badge bg-{{ $method['class'] }}">{{ $method['name'] }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <div class="fw-bold text-success fs-6">
                                                                {{ strtoupper(getCurrentCurrency()) }} {{ number_format($payment->amount, 2) }}
                                                            </div>
                                                            @if($payment->change > 0)
                                                                <small class="text-muted">Change: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($payment->change, 2) }}</small>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                                <i class="fas fa-check-circle me-1"></i>Completed
                                                            </span>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            @if($payment->note)
                                                                <div class="text-muted" title="{{ $payment->note }}">
                                                                    <i class="fas fa-comment-alt me-1"></i>
                                                                    {{ Str::limit($payment->note, 30) }}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Payment History</h5>
                                <p class="text-muted">No payments have been made for this company yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoice Detail Modals --}}
    @forelse($company->patients as $patient)
        @foreach($patient->invoices as $invoice)
            <div class="modal fade" id="view{{ $invoice->invoice_id }}" tabindex="-1"
                 aria-labelledby="modalLabel{{ $invoice->invoice_id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel{{ $invoice->invoice_id }}">
                                Invoice Details - {{ $invoice->invoice_id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Visit Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Visit Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                                    <p><strong>Patient:</strong> {{ $patient->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Invoice ID:</strong> {{ $invoice->invoice_id }}</p>
                                    <p><strong>Status:</strong>
                                        @if($invoice->balance > 0)
                                            <span class="badge bg-danger">Unpaid</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Services Provided -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Services Provided</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($invoice->invoiceItems as $item)
                                                <tr>
                                                    <td>{{ $item->category ?? 'General' }}</td>
                                                    <td>{{ $item->description ?? $item->item_name }}</td>
                                                    <td>{{ $item->quantity ?? 1 }}</td>
                                                    <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format($item->price ?? $item->rate, 2) }}</td>
                                                    <td>{{ strtoupper(getCurrentCurrency()) }} {{ number_format(($item->quantity ?? 1) * ($item->price ?? $item->rate), 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No detailed services available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">Payment Summary</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Subtotal:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount, 2) }}</p>
                                            <p class="mb-1"><strong>Discount:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->discount ?? 0, 2) }}</p>
                                            <p class="mb-1"><strong>Total Amount:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount, 2) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Amount Paid:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->amount - $invoice->balance, 2) }}</p>
                                            <p class="mb-1"><strong>Outstanding Balance:</strong>
                                                <span class="{{ $invoice->balance > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                    {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->balance, 2) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Patient Summary Modal --}}
        @php
            $totalAmount = 0;
            $totalBalance = 0;

            // Calculate totals for all bill types
            if ($patient->invoices) {
                $totalAmount += $patient->invoices->sum('amount');
                $totalBalance += $patient->invoices->sum('balance');
            }
            if ($patient->medicine_bills) {
                $totalAmount += $patient->medicine_bills->sum('total');
                $totalBalance += $patient->medicine_bills->sum('balance_amount');
            }
            if ($patient->ipd_bills) {
                $totalAmount += $patient->ipd_bills->sum(function($ipdPatient) {
                    return $ipdPatient->bill ? $ipdPatient->bill->net_payable_amount : 0;
                });
                $totalBalance += $patient->ipd_bills->sum(function($ipdPatient) {
                    if ($ipdPatient->bill) {
                        return $ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments;
                    }
                    return 0;
                });
            }
            if ($patient->pathologyTests) {
                $totalAmount += $patient->pathologyTests->sum('total');
                $totalBalance += $patient->pathologyTests->sum('balance');
            }
            if ($patient->radiologyTests) {
                $totalAmount += $patient->radiologyTests->sum('total');
                $totalBalance += $patient->radiologyTests->sum('balance');
            }
            if ($patient->maternity) {
                $totalAmount += $patient->maternity->sum('standard_charge');
                $totalBalance += $patient->maternity->sum('balance');
            }

            $totalPaid = $totalAmount - $totalBalance;
        @endphp
        <div class="modal fade" id="summaryModal{{ $patient->id }}" tabindex="-1"
             aria-labelledby="summaryModalLabel{{ $patient->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="summaryModalLabel{{ $patient->id }}">Patient
                            Cost Summary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Total
                                Amount:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($totalAmount, 2) }}
                        </p>
                        <p><strong>Total
                                Paid:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($totalPaid, 2) }}
                        </p>
                        <p><strong>Total
                                Due:</strong> {{ strtoupper(getCurrentCurrency()) }} {{ number_format($totalBalance, 2) }}
                        </p>

                        @if($totalBalance > 0)
                            <span class="badge bg-warning">Pending Payment</span>
                        @else
                            <span class="badge bg-success">Fully Paid</span>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedInvoiceDropdown = document.getElementById('selectedInvoice');
    const totalBalanceInput = document.getElementById('totalBalance');
    const paymentAmountInput = document.getElementById('paymentAmount');
    const processPaymentBtn = document.getElementById('processPaymentBtn');
    const paymentForm = document.getElementById('paymentForm');

    // Handle invoice selection from dropdown
    selectedInvoiceDropdown.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        console.log('Selected option:', selectedOption);
        console.log('Selected value:', this.value);

        if (this.value === '') {
            // No invoice selected
            totalBalanceInput.value = '{{ strtoupper(getCurrentCurrency()) }} 0.00';
            paymentAmountInput.max = '0';
            paymentAmountInput.value = '';
            processPaymentBtn.disabled = true;
            console.log('No bill selected');
        } else {
            // Invoice selected
            const balance = parseFloat(selectedOption.dataset.balance);
            console.log('Balance from dataset:', selectedOption.dataset.balance);
            console.log('Parsed balance:', balance);
            console.log('Is NaN:', isNaN(balance));

            if (isNaN(balance)) {
                console.error('Invalid balance value:', selectedOption.dataset.balance);
                totalBalanceInput.value = '{{ strtoupper(getCurrentCurrency()) }} 0.00';
                paymentAmountInput.max = '0';
                processPaymentBtn.disabled = true;
            } else {
            totalBalanceInput.value = `{{ strtoupper(getCurrentCurrency()) }} ${balance.toFixed(2)}`;
            paymentAmountInput.max = balance.toFixed(2);
            processPaymentBtn.disabled = false;
                console.log('Updated total balance to:', totalBalanceInput.value);

            // Clear payment amount if it exceeds new balance
            if (parseFloat(paymentAmountInput.value) > balance) {
                paymentAmountInput.value = '';
                }
            }
        }
    });

    // Handle paid amount and change calculation
    const paidAmountInput = document.getElementById('paidAmount');
    const changeAmountInput = document.getElementById('changeAmount');
    const changeHiddenInput = document.getElementById('changeHidden');
    const paymentTypeSelect = document.getElementById('paymentType');

    function calculateChange() {
        const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
        const paidAmount = parseFloat(paidAmountInput.value) || 0;
        const change = Math.max(0, paidAmount - paymentAmount);

        changeAmountInput.value = `{{ strtoupper(getCurrentCurrency()) }} ${change.toFixed(2)}`;
        changeHiddenInput.value = change.toFixed(2);
    }

    // Update change when payment amount or paid amount changes
    paymentAmountInput.addEventListener('input', calculateChange);
    paidAmountInput.addEventListener('input', calculateChange);

    // Handle payment type selection
    paymentTypeSelect.addEventListener('change', function() {
        const paymentType = this.value;

        // Hide paid amount and change fields for all payment types
            paidAmountInput.parentElement.style.display = 'none';
            changeAmountInput.parentElement.style.display = 'none';
            paidAmountInput.required = false;
            paidAmountInput.value = '';
            changeAmountInput.value = `{{ strtoupper(getCurrentCurrency()) }} 0.00`;
            changeHiddenInput.value = '0';
    });

    // Initialize payment type behavior
    paymentTypeSelect.dispatchEvent(new Event('change'));

    // Handle form submission
    paymentForm.addEventListener('submit', function(e) {
        if (selectedInvoiceDropdown.value === '') {
            e.preventDefault();
            alert('Please select an invoice to pay.');
            return;
        }

        const paymentAmount = parseFloat(paymentAmountInput.value);
        const selectedOption = selectedInvoiceDropdown.options[selectedInvoiceDropdown.selectedIndex];
        const invoiceBalance = parseFloat(selectedOption.dataset.balance);
        const paymentType = paymentTypeSelect.value;
        const paidAmount = parseFloat(paidAmountInput.value) || 0;

        if (paymentAmount > invoiceBalance) {
            e.preventDefault();
            alert('Payment amount cannot exceed invoice balance.');
            return;
        }

        // No validation needed for paid amount since we're not using it

        // Confirm payment
        const paymentMethodText = paymentTypeSelect.options[paymentTypeSelect.selectedIndex].text;
        const invoiceNo = selectedOption.text.split(' - ')[0];
        if (!confirm(`Are you sure you want to process payment of {{ strtoupper(getCurrentCurrency()) }} ${paymentAmount.toFixed(2)} for invoice ${invoiceNo} via ${paymentMethodText}?`)) {
            e.preventDefault();
        }
    });

    // Initialize bulk payment functionality
    updateSelectedTotal();
});

// Bulk Payment Functions
function toggleAllBills() {
    const selectAllCheckbox = document.getElementById('selectAllBills');
    const billCheckboxes = document.querySelectorAll('.bill-checkbox');

    const isChecked = selectAllCheckbox.checked;

    billCheckboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });

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
</script>

{{-- Payment Modal --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-credit-card me-2"></i>
                    Process Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" action="{{ route('companies.pay', $company->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="selectedInvoice" class="form-label">Select Unpaid Bill <span class="text-danger">*</span></label>
                                <select name="bill_id" id="selectedInvoice" class="form-select" required>
                                    <option value="">Select an unpaid bill</option>
                                    @if($company && $company->patients)
                                        @foreach($company->patients as $patient)
                                            {{-- OPD Invoices --}}
                                            @if($patient && $patient->invoices)
                                                @foreach($patient->invoices as $invoice)
                                                    @if($invoice->balance > 0)
                                                        <option value="invoice_{{ $invoice->id }}"
                                                                data-balance="{{ $invoice->balance }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="OPD Invoice">
                                                            {{ $invoice->invoice_id }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (OPD Invoice)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($invoice->balance, 2) }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{-- Medicine Bills --}}
                                            @if($patient && $patient->medicine_bills)
                                                @foreach($patient->medicine_bills as $medicineBill)
                                                    @if($medicineBill->balance_amount > 0)
                                                        <option value="medicine_{{ $medicineBill->id }}"
                                                                data-balance="{{ $medicineBill->balance_amount }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="Medicine Bill">
                                                            {{ $medicineBill->bill_number }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (Medicine Bill)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($medicineBill->balance_amount, 2) }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{-- IPD Bills --}}
                                            @if($patient && $patient->ipd_bills)
                                                @foreach($patient->ipd_bills as $ipdPatient)
                                                    @if($ipdPatient->bill)
                                                        @php
                                                            $ipdBill = $ipdPatient->bill;
                                                            $ipdBalance = $ipdBill->net_payable_amount - $ipdBill->total_payments;
                                                        @endphp
                                                        @if($ipdBalance > 0)
                                                        <option value="ipd_{{ $ipdPatient->id }}"
                                                                data-balance="{{ $ipdBalance }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="IPD Bill">
                                                            IPD-{{ $ipdPatient->ipd_number }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (IPD Bill)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($ipdBalance, 2) }})
                                                        </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{-- Pathology Tests --}}
                                            @if($patient && $patient->pathologyTests)
                                                @foreach($patient->pathologyTests as $pathologyTest)
                                                    @if($pathologyTest->balance > 0)
                                                        <option value="pathology_{{ $pathologyTest->id }}"
                                                                data-balance="{{ $pathologyTest->balance }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="Pathology Test">
                                                            {{ $pathologyTest->bill_no }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (Pathology Test)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($pathologyTest->balance, 2) }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{-- Radiology Tests --}}
                                            @if($patient && $patient->radiologyTests)
                                                @foreach($patient->radiologyTests as $radiologyTest)
                                                    @if($radiologyTest->balance > 0)
                                                        <option value="radiology_{{ $radiologyTest->id }}"
                                                                data-balance="{{ $radiologyTest->balance }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="Radiology Test">
                                                            {{ $radiologyTest->bill_no }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (Radiology Test)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($radiologyTest->balance, 2) }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{-- Maternity Bills --}}
                                            @if($patient && $patient->maternity)
                                                @foreach($patient->maternity as $maternity)
                                                    @if($maternity->balance > 0)
                                                        <option value="maternity_{{ $maternity->id }}"
                                                                data-balance="{{ $maternity->balance }}"
                                                                data-patient="{{ $patient->user->first_name }} {{ $patient->user->last_name }}"
                                                                data-bill-type="Maternity">
                                                            MAT-{{ $maternity->id }} - {{ $patient->user->first_name }} {{ $patient->user->last_name }} (Maternity)
                                                            (Balance: {{ strtoupper(getCurrentCurrency()) }} {{ number_format($maternity->balance, 2) }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="totalBalance" class="form-label">Total Balance</label>
                                <input type="text" class="form-control" id="totalBalance" readonly value="{{ strtoupper(getCurrentCurrency()) }} 0.00">
                            </div>
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label">Payment Amount</label>
                                <input type="number" class="form-control" id="paymentAmount" name="payment_amount"
                                       step="0.01" min="0.01" placeholder="Enter payment amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="paymentType" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_type" id="paymentType" class="form-select" required>
                                    <option value="">Select Payment Method</option>
                                    @foreach(App\Models\Payment::PAYMENT_METHOD as $key => $method)
                                        <option value="{{ $key }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paidAmount" class="form-label">Amount Paid</label>
                                <input type="number" class="form-control" id="paidAmount" name="paid_amount"
                                       step="0.01" min="0" placeholder="Amount received from customer">
                            </div>
                            <div class="mb-3">
                                <label for="changeAmount" class="form-label">Change</label>
                                <input type="text" class="form-control" id="changeAmount" readonly value="{{ strtoupper(getCurrentCurrency()) }} 0.00">
                                <input type="hidden" name="change" id="changeHidden" value="0">
                            </div>
                            <div class="mb-3">
                                <label for="paymentNote" class="form-label">Payment Note</label>
                                <textarea name="payment_note" id="paymentNote" class="form-control" rows="2" placeholder="Optional payment note"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="paymentForm" class="btn btn-success" id="processPaymentBtn" disabled>
                    <i class="fas fa-credit-card me-1"></i>
                    Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
