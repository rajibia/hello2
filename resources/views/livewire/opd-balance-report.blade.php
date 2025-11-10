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
                <div class="col-lg-4 col-md-12">
                    <div class="d-flex flex-wrap mb-5">
                        <div class="btn-group me-5 mb-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'today' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('today')">
                                <span class="fw-bold">Today</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'yesterday' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('yesterday')">
                                <span class="fw-bold">Yesterday</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'this_week' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_week')">
                                <span class="fw-bold">This Week</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'this_month' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_month')">
                                <span class="fw-bold">This Month</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-money-check-alt text-white"></i>
                            </span>
                            <select class="form-select" wire:model="paymentStatusFilter">
                                <option value="all">All Status</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-5 col-md-12">
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
                                <button type="button" class="btn btn-light-secondary" wire:click="clearFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <h5 class="text-muted fw-normal mb-3 date-range-display">
                <i class="fas fa-calendar-alt me-1"></i> {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </h5>
            
            <div class="row mb-5">
                <div class="col-md-12 d-flex flex-wrap">
                    <div class="input-group mb-0">
                        <input type="text" class="form-control" placeholder="Search Patient or OPD Number"
                               wire:model.debounce.500ms="searchQuery">
                    </div>
            </div>
            
        </div>
        
        <!-- OPD Balance Table in Card -->
        <div class="card">
            <div class="card-body pb-3 pt-5">
                <div id="opdBalancePrintSection">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">OPD Number</th>
                                    <th class="min-w-150px">Date</th>
                                    <th class="min-w-200px">Patient</th>
                                    <th class="min-w-200px">Doctor</th>
                                    <th class="min-w-100px text-end">Charge</th>
                                    <th class="min-w-100px text-end">Paid</th>
                                    <th class="min-w-100px text-end">Balance</th>
                                    <th class="min-w-100px text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opdBalances['data'] as $opdBalance)
                                    <tr>
                                        <td>{{ $opdBalance['opd_number'] }}</td>
                                        <td>{{ $opdBalance['appointment_date'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="image image-circle image-mini me-3">
                                                    <a href="{{ route('patients.show', $opdBalance['patient']['id']) }}">
                                                        <img src="{{ $opdBalance['patient']['image_url'] }}" alt="" class="user-img object-contain image rounded-circle">
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('patients.show', $opdBalance['patient']['id']) }}" class="text-decoration-none mb-1">{{ $opdBalance['patient']['name'] }}</a>
                                                    <span>{{ $opdBalance['patient']['email'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($opdBalance['doctor']['id'])
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        <a href="{{ url('doctors') . '/' . $opdBalance['doctor']['id'] }}">
                                                            <img src="{{ $opdBalance['doctor']['image_url'] }}" alt="" class="user-img object-contain image rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ url('doctors') . '/' . $opdBalance['doctor']['id'] }}" class="text-decoration-none mb-1">{{ $opdBalance['doctor']['name'] }}</a>
                                                        <span>{{ $opdBalance['doctor']['email'] }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalance['standard_charge'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalance['paid_amount'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalance['balance'], 2) }}</td>
                                        <td class="text-center">
                                            @if($opdBalance['status'] === 'paid')
                                                <span class="badge bg-light-success">Paid</span>
                                            @else
                                                <span class="badge bg-light-danger">Pending</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No OPD balance records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <!-- <tr class="fw-bold">
                                    <td colspan="4" class="text-end">Total:</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_amount'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_paid'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_balance'], 2) }}</td>
                                    <td></td>
                                </tr> -->
                            </tfoot>
                        </table>
                    </div>
                
                    @if($opdBalances['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div>
                                {{ $opdBalances['paginator']->onEachSide(1)->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="opdBalancePrintSection">
    <div class="print-header">
        <h2>OPD Balance Report</h2>
        <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
    </div>
    
    <table class="print-table">
        <thead>
            <tr>
                <th>OPD Number</th>
                <th>Date</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Charge</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($opdBalances['data'] as $opdBalance)
                <tr>
                    <td>{{ $opdBalance['opd_number'] }}</td>
                    <td>{{ $opdBalance['appointment_date'] }}</td>
                    <td>{{ $opdBalance['patient']['name'] }}</td>
                    <td>{{ $opdBalance['doctor']['name'] ?? 'N/A' }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($opdBalance['standard_charge'], 2) }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($opdBalance['paid_amount'], 2) }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($opdBalance['balance'], 2) }}</td>
                    <td>{{ $opdBalance['status'] === 'paid' ? 'Paid' : 'Pending' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No OPD balance records found.</td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td colspan="4" class="text-end">Total:</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_amount'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_paid'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($opdBalances['total_balance'], 2) }}</td>
                <td></td>
            </tr>
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
