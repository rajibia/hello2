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
        }
        .print-only {
            display: none;
        }
        
        /* Ensure print section is visible when printing */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>{{ __('Filter Options') }}</h3>
            </div>
        </div>
        <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
            <!-- Date Filter Buttons -->
            <div class="row mb-5">
                <div class="col-lg-6 col-md-12">
                    <div class="d-flex flex-wrap mb-3">
                        <div class="btn-group me-5 mb-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'today' ? 'active' : '' }}" 
                                wire:click="$set('dateFilter', 'today')">
                                <span class="fw-bold">Today</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'yesterday' ? 'active' : '' }}" 
                                wire:click="$set('dateFilter', 'yesterday')">
                                <span class="fw-bold">Yesterday</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_week' ? 'active' : '' }}" 
                                wire:click="$set('dateFilter', 'this_week')">
                                <span class="fw-bold">This Week</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_month' ? 'active' : '' }}" 
                                wire:click="$set('dateFilter', 'this_month')">
                                <span class="fw-bold">This Month</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="d-flex align-items-center">
                        <div class="position-relative w-100">
                            <div class="input-group date-range-picker">
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </span>
                                <input type="date" class="form-control" placeholder="Start Date" wire:model="fromDate" max="{{ date('Y-m-d') }}">
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                <input type="date" class="form-control" placeholder="End Date" wire:model="toDate" max="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-light-secondary" wire:click="resetFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="bill_type" class="form-label">{{ __('Bill Type') }}</label>
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
                <div class="col-md-3 mb-3">
                    <label for="payment_status" class="form-label">{{ __('Payment Status') }}</label>
                    <select class="form-select" id="payment_status" wire:model="paymentStatus">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="paid">{{ __('Paid') }}</option>
                        <option value="partially_paid">{{ __('Partially Paid') }}</option>
                        <option value="unpaid">{{ __('Unpaid') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="per_page" class="form-label">{{ __('Items Per Page') }}</label>
                    <select class="form-select" id="per_page" wire:model="perPage">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Display -->
    <div class="card mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ __('Company') }}: <span class="text-primary">{{ $company->name }}</span></h4>
                    <p class="text-muted mb-0 date-range-display">
                        {{ __('Period') }}: {{ \Carbon\Carbon::parse($summaryData['from_date'])->format('M d, Y') }} - 
                        {{ \Carbon\Carbon::parse($summaryData['to_date'])->format('M d, Y') }}
                    </p>
                </div>
                <div class="text-end">
                    <h5 class="mb-0">{{ __('Total Bills') }}: <span class="text-primary">{{ $summaryData['total_bills'] }}</span></h5>
                    <p class="text-muted mb-0">{{ __('Total Amount') }}: {{ number_format($summaryData['total_amount'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>{{ __('Summary') }}</h3>
            </div>
        </div>
        <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="min-w-150px">{{ __('Item') }}</th>
                            <th class="min-w-100px text-end">{{ __('Value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('Total Bills') }}</td>
                            <td class="text-end">{{ $summaryData['total_bills'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Total Amount') }}</td>
                            <td class="text-end">{{ number_format($summaryData['total_amount'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Total Paid') }}</td>
                            <td class="text-end">{{ number_format($summaryData['total_paid'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Total Due') }}</td>
                            <td class="text-end">{{ number_format($summaryData['total_due'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Patient Bills -->
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>{{ __('Patient Bills') }}</h3>
            </div>
        </div>
        <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
            @if(count($patientBills['patients']) > 0)
                @foreach($patientBills['patients'] as $patient)
                    <div class="patient-section mb-5">
                        <div class="patient-info mb-3">
                            <h3>{{ $patient->user->full_name }}</h3>
                            <p>{{ __('Patient ID') }}: {{ $patient->patient_unique_id }}</p>
                            <p>{{ __('Phone') }}: {{ $patient->user->phone ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- OPD Invoices -->
                        @if(count($patient->invoices) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('OPD Invoices') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Invoice #') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Doctor') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                                                    <td>{{ $invoice->doctor->user->full_name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($invoice->amount, 2) }}</td>
                                                    <td>{{ number_format($invoice->amount_paid, 2) }}</td>
                                                    <td>{{ number_format($invoice->amount_due, 2) }}</td>
                                                    <td>
                                                        @if($invoice->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($invoice->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Medicine Bills -->
                        @if(count($patient->medicine_bills) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('Medicine Bills') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Bill #') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->medicine_bills as $bill)
                                                <tr>
                                                    <td>{{ $bill->bill_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($bill->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ number_format($bill->total, 2) }}</td>
                                                    <td>{{ number_format($bill->paid_amount, 2) }}</td>
                                                    <td>{{ number_format($bill->due_amount, 2) }}</td>
                                                    <td>
                                                        @if($bill->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($bill->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <!-- IPD Bills -->
                        @if(count($patient->ipd_bills) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('IPD Bills') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Bill #') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->ipd_bills as $bill)
                                                <tr>
                                                    <td>{{ $bill->bill_id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($bill->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ number_format($bill->total_amount, 2) }}</td>
                                                    <td>{{ number_format($bill->paid_amount, 2) }}</td>
                                                    <td>{{ number_format($bill->due_amount, 2) }}</td>
                                                    <td>
                                                        @if($bill->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($bill->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Pathology Tests -->
                        @if(count($patient->pathologyTests) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('Pathology Tests') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Test') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->pathologyTests as $test)
                                                <tr>
                                                    <td>{{ $test->test->test_name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($test->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ number_format($test->charge, 2) }}</td>
                                                    <td>{{ number_format($test->paid_amount, 2) }}</td>
                                                    <td>{{ number_format($test->charge - $test->paid_amount, 2) }}</td>
                                                    <td>
                                                        @if($test->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($test->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Radiology Tests -->
                        @if(count($patient->radiologyTests) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('Radiology Tests') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Test') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->radiologyTests as $test)
                                                <tr>
                                                    <td>{{ $test->test->test_name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($test->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ number_format($test->charge, 2) }}</td>
                                                    <td>{{ number_format($test->paid_amount, 2) }}</td>
                                                    <td>{{ number_format($test->charge - $test->paid_amount, 2) }}</td>
                                                    <td>
                                                        @if($test->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($test->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Maternity -->
                        @if(count($patient->maternity) > 0)
                            <div class="mb-4">
                                <h4 class="mb-3">{{ __('Maternity') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th>{{ __('Case ID') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Package') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Paid') }}</th>
                                                <th>{{ __('Due') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($patient->maternity as $maternity)
                                                <tr>
                                                    <td>{{ $maternity->case_id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($maternity->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ $maternity->package->name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($maternity->package_price, 2) }}</td>
                                                    <td>{{ number_format($maternity->paid_amount, 2) }}</td>
                                                    <td>{{ number_format($maternity->due_amount, 2) }}</td>
                                                    <td>
                                                        @if($maternity->payment_status == 'paid')
                                                            <span class="badge bg-light-success">{{ __('Paid') }}</span>
                                                        @elseif($maternity->payment_status == 'partially_paid')
                                                            <span class="badge bg-light-warning">{{ __('Partially Paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('Unpaid') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <hr class="mb-5">
                @endforeach
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $patientBills['paginator']->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    {{ __('No bills found for the selected filters.') }}
                </div>
            @endif
        </div>
    </div>
</div>
