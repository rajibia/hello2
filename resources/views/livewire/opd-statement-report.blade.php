<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }
    </style>
    <div class="mb-5 mb-xl-10">
        <div class="pt-3">
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
                                <button type="button" class="btn btn-light-secondary" wire:click="changeDateFilter('today')">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h5 class="text-muted fw-normal mb-0 date-range-display">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    {{ $formattedStartDate }} - {{ $formattedEndDate }}
                </h5>
            </div>

            <!-- OPD Statement Table in Card -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div id="opdStatementPrintSection">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Patient</th>
                                        <th>OPD Number</th>
                                        <th>Doctor</th>
                                        <th>Appointment Date</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($opdStatements as $opd)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        <a href="{{ route('patients.show', $opd->patient->id) }}">
                                                            <img src="{{ $opd->patient->patientUser->image_url }}" alt=""
                                                                class="user-img object-contain image rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('patients.show', $opd->patient->id) }}"
                                                            class="text-decoration-none mb-1">{{ $opd->patient->patientUser->full_name }}</a>
                                                        <span>{{ $opd->patient->patientUser->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center mt-2">
                                                    <a href="{{ route('opd.patient.show', $opd->id) }}" class="badge bg-light-info text-decoration-none">{{ $opd->opd_number }}</a>    
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        <a href="{{ url('doctors') . '/' . $opd->doctor->id }}">
                                                            <img src="{{$opd->doctor->doctorUser->image_url}}" alt=""
                                                                class="user-img object-contain image rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ url('doctors') . '/' . $opd->doctor->id }}"
                                                            class="text-decoration-none mb-1">{{$opd->doctor->doctorUser->full_name}}</a>
                                                        <span>{{ $opd->doctor->doctorUser->email}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($opd->appointment_date === null)
                                                    {{ __('messages.common.n/a') }}
                                                @else
                                                <div class="badge bg-light-info">
                                                    <div class="mb-2">{{ \Carbon\Carbon::parse($opd->appointment_date)->format('h:i A')}}
                                                    </div>
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($opd->appointment_date)->translatedFormat('jS M,Y')}}
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ $opd->invoice_number ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $opd->currency_symbol ?? '$' }} {{ number_format($opd->standard_charge, 2) }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $opd->currency_symbol ?? '$' }} {{ number_format($opd->paid_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $opd->currency_symbol ?? '$' }} {{ number_format($this->calculateBalance($opd->standard_charge, $opd->paid_amount), 2) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center mt-2">
                                                    @php
                                                        $paymentStatus = $this->getPaymentStatus($opd->standard_charge, $opd->paid_amount);
                                                    @endphp
                                                    <span class="badge bg-light-{{ $paymentStatus['badge'] }}">{{ $paymentStatus['text'] }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No OPD statement records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div>
                                {{ $opdStatements->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Section (Hidden) -->
    <div class="d-none" id="opdStatementPrintSection">
        <div class="print-header">
            <h2>OPD Statement Report</h2>
            <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
        </div>
        
        <table class="print-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>OPD Number</th>
                    <th>Doctor</th>
                    <th>Appointment Date</th>
                    <th>Invoice</th>
                    <th>Charge</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opdStatements as $opd)
                    <tr>
                        <td>{{ $opd->patient->patientUser->full_name }}</td>
                        <td>{{ $opd->opd_number }}</td>
                        <td>{{ $opd->doctor->doctorUser->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($opd->appointment_date)->translatedFormat('jS M, Y') }}</td>
                        <td>{{ $opd->invoice_number ?? 'N/A' }}</td>
                        <td>{{ $opd->currency_symbol ?? '$' }} {{ number_format($opd->standard_charge, 2) }}</td>
                        <td>{{ $opd->currency_symbol ?? '$' }} {{ number_format($opd->paid_amount ?? 0, 2) }}</td>
                        <td>{{ $opd->currency_symbol ?? '$' }} {{ number_format($this->calculateBalance($opd->standard_charge, $opd->paid_amount), 2) }}</td>
                        <td>{{ $this->getPaymentStatus($opd->standard_charge, $opd->paid_amount)['text'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No OPD statement records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Print Buttons -->
        <div class="no-print" style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 20px;">
            <button class="btn btn-primary me-2" onclick="window.print();" style="display: inline-block !important;">Print Report</button>
            <button class="btn btn-secondary" onclick="window.close();" style="display: inline-block !important;">Close</button>
        </div>
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
        });
    </script>
</div>