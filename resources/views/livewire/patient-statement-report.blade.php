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
            #patientStatementPrintSection, #patientStatementPrintSection * {
                visibility: visible;
            }
            #patientStatementPrintSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    <div class="mb-5 mb-xl-10 no-print">
        <div class="pt-3">
            <!-- Date Filter Section -->
            <div class="row mb-5">
                <div class="col-lg-6 col-md-12">
                    <div class="d-flex flex-wrap mb-5">
                        <div class="btn-group me-5 mb-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'today' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('today')">
                                <span class="fw-bold">Today</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'yesterday' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('yesterday')">
                                <span class="fw-bold">Yesterday</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_week' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_week')">
                                <span class="fw-bold">This Week</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_month' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_month')">
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
                                <input type="date" class="form-control" placeholder="Start Date" id="startDate"
                                    wire:model="startDate" max="{{ date('Y-m-d') }}">
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                <input type="date" class="form-control" placeholder="End Date" id="endDate"
                                    wire:model="endDate" max="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-light-secondary" wire:click="changeDateFilter('this_month')">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Patient Search Section (only shown if no patient is selected) -->
            @if(!$patientId)
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-5">
                                <input type="text" class="form-control" placeholder="Search patients by name or email" 
                                    wire:model.debounce.500ms="searchTerm">
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th>Patient</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patientStatements as $patient)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="image image-circle image-mini me-3">
                                                            <a href="{{ route('patients.show', $patient->id) }}">
                                                                <img src="{{ $patient->patientUser->image_url ?? asset('assets/img/avatar.png') }}" 
                                                                    alt="" class="user-img object-contain image rounded-circle">
                                                            </a>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('patients.show', $patient->id) }}" 
                                                                class="text-decoration-none mb-1">
                                                                {{ $patient->patientUser->full_name }}
                                                            </a>
                                                            <span>{{ $patient->patient_unique_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $patient->patientUser->email }}</td>
                                                <td>{{ $patient->patientUser->phone ?? 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" 
                                                        wire:click="patientSelected({{ $patient->id }})">
                                                        View Statement
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No patients found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div>
                                    {{ $patientStatements->onEachSide(1)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <h5 class="text-muted fw-normal mb-4 mt-3 date-range-display">
                <i class="fas fa-calendar-alt me-1"></i> 
                {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </h5>
            
            @foreach($patientStatements as $patient)
                <!-- Patient Info Card -->
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="image image-circle image-mini me-3">
                                    <img src="{{ $patient->patientUser->image_url ?? asset('assets/img/avatar.png') }}" 
                                        alt="" class="user-img object-contain image rounded-circle">
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $patient->patientUser->full_name }}</h3>
                                    <p class="text-muted mb-0">{{ $patient->patient_unique_id }}</p>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-secondary" wire:click="$set('patientId', null)">
                                <i class="fas fa-arrow-left"></i> Back to Patient List
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> {{ $patient->patientUser->email }}</p>
                                <p><strong>Phone:</strong> {{ $patient->patientUser->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Gender:</strong> {{ ucfirst($patient->patientUser->gender == 0 ? 'Male' : 'Female') }}</p>
                                <p><strong>Age:</strong> {{ $patient->patientUser->age_new ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Cards -->
                @php
                    $totals = $this->calculateTotals($patient);
                @endphp
                <div class="row g-5 g-xl-8 mb-5">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center h-50px w-50px ms-n2 me-3 bg-primary rounded-circle">
                                        <i class="fas fa-money-bill-wave text-white fs-1"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bolder">{{ getCurrencySymbol() }} {{ number_format($totals['total_charges'], 2) }}</div>
                                        <div class="fs-6 text-gray-600">Total Charges</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center h-50px w-50px ms-n2 me-3 bg-info rounded-circle">
                                        <i class="fas fa-percentage text-white fs-1"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bolder">{{ getCurrencySymbol() }} {{ number_format($totals['total_discount'], 2) }}</div>
                                        <div class="fs-6 text-gray-600">Total Discount</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center h-50px w-50px ms-n2 me-3 bg-success rounded-circle">
                                        <i class="fas fa-check-circle text-white fs-1"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bolder">{{ getCurrencySymbol() }} {{ number_format($totals['total_paid'], 2) }}</div>
                                        <div class="fs-6 text-gray-600">Total Paid</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center h-50px w-50px ms-n2 me-3 bg-danger rounded-circle">
                                        <i class="fas fa-exclamation-circle text-white fs-1"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bolder">{{ getCurrencySymbol() }} {{ number_format($totals['total_due'], 2) }}</div>
                                        <div class="fs-6 text-gray-600">Total Due</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Invoices Section -->
                <div class="card mb-5">
                    <div class="card-header border-0 pt-5 pb-3">
                        <div class="card-title">
                            <h3 class="card-label fw-bolder text-gray-800">Invoices</h3>
                        </div>
                    </div>
                    <div class="card-body pb-3 pt-0">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->invoices as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-decoration-none">
                                                    {{ $invoice->invoice_id }}
                                                </a>
                                            </td>
                                            <td>{{ $this->formatDate($invoice->invoice_date) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->amount, 2) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0, 2) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->amount - ($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0), 2) }}</td>
                                            <td>
                                                @php
                                                    $status = $invoice->status == 0 ? 'paid' : 'pending';
                                                    $badgeClass = $status == 'paid' ? 'success' : 'danger';
                                                @endphp
                                                <span class="badge bg-light-{{ $badgeClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No invoices found for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Bills Section -->
                <div class="card mb-5">
                    <div class="card-header border-0 pt-5 pb-3">
                        <div class="card-title">
                            <h3 class="card-label fw-bolder text-gray-800">Bills</h3>
                        </div>
                    </div>
                    <div class="card-body pb-3 pt-0">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th>Bill #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($patient->bills as $bill)
                                        <tr>
                                            <td>
                                                <a href="{{ route('bills.show', $bill->id) }}" class="text-decoration-none">
                                                    {{ $bill->bill_id }}
                                                </a>
                                            </td>
                                            <td>{{ $this->formatDate($bill->bill_date) }}</td>
                                            <td>{{ getCurrencySymbol() }} {{ number_format($bill->amount, 2) }}</td>
                                            <td>
                                                @php
                                                    $paidAmount = $bill->manualBillPayment->sum('amount') ?? 0;
                                                    $status = $this->getPaymentStatus($bill->amount, $paidAmount);
                                                @endphp
                                                <span class="badge bg-light-{{ $status['badge'] }}">
                                                    {{ $status['text'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No bills found for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
    
    <!-- Print Section (Hidden) -->
    <div class="d-none" id="patientStatementPrintSection">
        <div class="print-header">
            <h2>Patient Statement Report</h2>
            <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
        </div>
        
        @if($patientId && count($patientStatements) > 0)
            @php
                $patient = $patientStatements[0];
                $totals = $this->calculateTotals($patient);
            @endphp
            
            <div class="patient-info mb-4">
                <h3>{{ $patient->patientUser->full_name }}</h3>
                <p>Patient ID: {{ $patient->patient_unique_id }}</p>
                <p>Email: {{ $patient->patientUser->email }}</p>
                <p>Phone: {{ $patient->patientUser->phone ?? 'N/A' }}</p>
            </div>
            
            <div class="summary mb-4">
                <h4>Summary</h4>
                <table class="print-table">
                    <tr>
                        <th>Total Charges</th>
                        <th>Total Discount</th>
                        <th>Total Paid</th>
                        <th>Total Due</th>
                    </tr>
                    <tr>
                        <td>{{ getCurrencySymbol() }} {{ number_format($totals['total_charges'], 2) }}</td>
                        <td>{{ getCurrencySymbol() }} {{ number_format($totals['total_discount'], 2) }}</td>
                        <td>{{ getCurrencySymbol() }} {{ number_format($totals['total_paid'], 2) }}</td>
                        <td>{{ getCurrencySymbol() }} {{ number_format($totals['total_due'], 2) }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="invoices mb-4">
                <h4>Invoices</h4>
                <table class="print-table">
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                    @forelse($patient->invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_id }}</td>
                            <td>{{ $this->formatDate($invoice->invoice_date) }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->amount, 2) }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0, 2) }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($invoice->amount - ($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0), 2) }}</td>
                            <td>{{ $invoice->status ? 'Paid' : 'Unpaid' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No invoices found for this period.</td>
                        </tr>
                    @endforelse
                </table>
            </div>
            
            <div class="bills">
                <h4>Bills</h4>
                <table class="print-table">
                    <tr>
                        <th>Bill #</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    @forelse($patient->bills as $bill)
                        <tr>
                            <td>{{ $bill->bill_id }}</td>
                            <td>{{ $this->formatDate($bill->bill_date) }}</td>
                            <td>{{ getCurrencySymbol() }} {{ number_format($bill->amount, 2) }}</td>
                            <td>
                                @php
                                    $paidAmount = $bill->manualBillPayment->sum('amount') ?? 0;
                                    $status = $this->getPaymentStatus($bill->amount, $paidAmount);
                                @endphp
                                {{ $status['text'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No bills found for this period.</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        @endif
        

    </div>
    
    <script>
        document.addEventListener('livewire:load', function () {
            // Handle date constraints
            const startDateInput = document.querySelector('input[wire\\:model="startDate"]');
            const endDateInput = document.querySelector('input[wire\\:model="endDate"]');
            
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (endDateInput.value && this.value > endDateInput.value) {
                        endDateInput.value = this.value;
                        @this.set('endDate', this.value);
                    }
                });
                
                endDateInput.addEventListener('change', function() {
                    if (startDateInput.value && this.value < startDateInput.value) {
                        startDateInput.value = this.value;
                        @this.set('startDate', this.value);
                    }
                });
            }
            
            // Handle print functionality
            window.Livewire.on('print-patient-statement', function() {
                const printContent = document.getElementById('patientStatementPrintSection').innerHTML;
                printReport(printContent);
            });
        });
        
        function printReport(printContent) {
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div class="print-container">
                    ${printContent}
                </div>
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            max-width: 1000px; 
                            margin: 0 auto;
                        }
                        .no-print {
                            text-align: center;
                        }
                        .print-header { 
                            text-align: center; 
                            margin-bottom: 30px; 
                        }
                        .print-header h2 { 
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
                    }
                </style>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
    </script>
</div>
