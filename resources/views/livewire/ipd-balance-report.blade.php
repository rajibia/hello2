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
            
            <!-- <div class="row mb-5">
                <div class="col-md-12 d-flex flex-wrap">
                    <div class="input-group me-2 mb-3" style="max-width: 300px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-user-md text-white"></i>
                        </span>
                        <select class="form-select" wire:model="doctorFilter">
                            <option value="all">All Doctors</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->doctorUser->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="input-group mb-3" style="max-width: 300px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-search text-white"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search Patient or IPD Number"
                               wire:model.debounce.500ms="searchQuery">
                    </div>
                </div>
            </div> -->
            
        </div>
        
        <!-- IPD Balance Table in Card -->
        <div class="card">
            <div class="card-body pb-3 pt-5">
                <div id="ipdBalancePrintSection">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">IPD Number</th>
                                    <th class="min-w-100px">Admission Date</th>
                                    <th class="min-w-150px">Patient</th>
                                    <th class="min-w-150px">Doctor</th>
                                    <th class="min-w-100px text-end">Charges</th>
                                    <th class="min-w-100px text-end">Paid</th>
                                    <th class="min-w-100px text-end">Balance</th>
                                    <th class="min-w-100px text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ipdBalances['data'] as $ipdBalance)
                                    <tr>
                                        <td>{{ $ipdBalance['ipd_number'] }}</td>
                                        <td>{{ $ipdBalance['admission_date'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="image image-circle image-mini me-3">
                                                    <a href="{{ route('patients.show', $ipdBalance['patient']['id']) }}">
                                                        <img src="{{ $ipdBalance['patient']['image_url'] }}" alt="" class="user-img object-contain image rounded-circle">
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('patients.show', $ipdBalance['patient']['id']) }}" class="text-decoration-none mb-1">{{ $ipdBalance['patient']['name'] }}</a>
                                                    <span>{{ $ipdBalance['patient']['email'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($ipdBalance['doctor']['id'])
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        <a href="{{ url('doctors') . '/' . $ipdBalance['doctor']['id'] }}">
                                                            <img src="{{ $ipdBalance['doctor']['image_url'] }}" alt="" class="user-img object-contain image rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ url('doctors') . '/' . $ipdBalance['doctor']['id'] }}" class="text-decoration-none mb-1">{{ $ipdBalance['doctor']['name'] }}</a>
                                                        <span>{{ $ipdBalance['doctor']['email'] }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalance['total_charges'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalance['paid_amount'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalance['balance'], 2) }}</td>
                                        <td class="text-center">
                                            @if($ipdBalance['status'] === 'paid')
                                                <span class="badge bg-light-success">Paid</span>
                                            @else
                                                <span class="badge bg-light-danger">Pending</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No IPD balance records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <!-- <tr class="fw-bold">
                                    <td colspan="4" class="text-end">Total:</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_amount'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_paid'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_balance'], 2) }}</td>
                                    <td></td>
                                </tr> -->
                            </tfoot>
                        </table>
                    </div>
                
                    @if($ipdBalances['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div>
                                {{ $ipdBalances['paginator']->onEachSide(1)->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="ipdBalancePrintSection">
    <div class="print-header">
        <h2>IPD Balance Report</h2>
        <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
        <p class="generated-on">Generated on: {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
    </div>
    
    <table class="print-table">
        <thead>
            <tr>
                <th>IPD Number</th>
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
            @forelse($ipdBalances['data'] as $ipdBalance)
                <tr>
                    <td>{{ $ipdBalance['ipd_number'] }}</td>
                    <td>{{ $ipdBalance['admission_date'] }}</td>
                    <td>{{ $ipdBalance['patient']['name'] }}</td>
                    <td>{{ $ipdBalance['doctor']['name'] ?? 'N/A' }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalance['total_charges'], 2) }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalance['paid_amount'], 2) }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalance['balance'], 2) }}</td>
                    <td>{{ $ipdBalance['status'] === 'paid' ? 'Paid' : 'Pending' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No IPD balance records found.</td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td colspan="4" class="text-end">Total:</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_amount'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_paid'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($ipdBalances['total_balance'], 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="print-footer">
        <p>&copy; {{ date('Y') }} {{ getAppName() }}. All rights reserved.</p>
    </div>
    
    <!-- Print Buttons -->
    <div class="no-print" style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 20px;">
        <button class="btn btn-primary me-2" onclick="window.print();" style="display: inline-block !important;">Print Report</button>
        <button class="btn btn-secondary" onclick="window.close();" style="display: inline-block !important;">Close</button>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        // Handle date constraints - add null checks to prevent errors
        try {
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
        } catch (e) {
            console.log('Date input initialization error:', e);
        }
    });
</script>
