<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }
        .date-range-display {
            margin-bottom: 1.5rem;
            color: #5E6278;
            font-size: 1rem;
        }
        .badge-light-danger {
            color: #F64E60;
            background-color: #FFE2E5;
        }
        .badge-light-warning {
            color: #FFA800;
            background-color: #FFF4DE;
        }
        .badge-light-success {
            color: #1BC5BD;
            background-color: #C9F7F5;
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
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'this_month' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_month')">
                                <span class="fw-bold">Next Month</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'next_3_months' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('next_3_months')">
                                <span class="fw-bold">Next 3 Months</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'next_6_months' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('next_6_months')">
                                <span class="fw-bold">Next 6 Months</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </span>
                            <select class="form-select" wire:model="expiryStatusFilter">
                                <option value="all">All Expiry Status</option>
                                <option value="expired">Expired</option>
                                <option value="expiring_soon">Expiring Soon (30 days)</option>
                                <option value="expiring_later">Expiring Later</option>
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
                                    wire:model="endDate">
                                <button type="button" class="btn btn-light-secondary" wire:click="clearFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <h5 class="text-muted fw-normal mb-3 date-range-display">
                <i class="fas fa-calendar-alt me-1"></i> Expiry Date Range: {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </h5>
            
            <div class="row mb-5">
                <div class="col-md-3 mb-3">
                    <div class="input-group">
                        <select class="form-select" wire:model="categoryFilter">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="input-group">
                        <select class="form-select" wire:model="brandFilter">
                            <option value="all">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Medicine Name, Salt Composition, Category or Brand"
                               wire:model.debounce.500ms="searchQuery">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Expiry Medicine Report Table in Card -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Expiry Medicine Report</span>
                </h3>
                <!-- Print button moved to main report page -->
            </div>
            <div class="card-body pb-3 pt-0">
                <div id="expiryMedicineReportTable">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Medicine</th>
                                    <th class="min-w-100px">Category</th>
                                    <th class="min-w-100px">Brand</th>
                                    <th class="min-w-100px text-end">Buying Price</th>
                                    <th class="min-w-100px text-end">Selling Price</th>
                                    <th class="min-w-70px text-end">Available</th>
                                    <th class="min-w-100px">Expiry Date</th>
                                    <th class="min-w-100px text-center">Days Until Expiry</th>
                                    <th class="min-w-100px text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicines['data'] as $medicine)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $medicine['name'] }}</span>
                                                @if($medicine['salt_composition'])
                                                    <span class="text-muted">{{ $medicine['salt_composition'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $medicine['category']['name'] }}</td>
                                        <td>{{ $medicine['brand']['name'] }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicine['buying_price'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicine['selling_price'], 2) }}</td>
                                        <td class="text-end">{{ $medicine['available_quantity'] }}</td>
                                        <td>{{ $medicine['expiry_date'] }}</td>
                                        <td class="text-center">
                                            @if($medicine['days_until_expiry'] < 0)
                                                <span class="badge bg-light-danger">Expired {{ abs($medicine['days_until_expiry']) }} days ago</span>
                                            @elseif($medicine['days_until_expiry'] == 0)
                                                <span class="badge bg-light-danger">Expires today</span>
                                            @else
                                                <span class="badge bg-light-{{ $medicine['days_until_expiry'] <= 30 ? 'warning' : 'success' }}">
                                                    {{ $medicine['days_until_expiry'] }} days
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($medicine['expiry_status'] === 'expired')
                                                <span class="badge bg-light-danger">Expired</span>
                                            @elseif($medicine['expiry_status'] === 'expiring_soon')
                                                <span class="badge bg-light-warning">Expiring Soon</span>
                                            @else
                                                <span class="badge bg-light-success">Valid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No expiring medicine records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicines['total_buying_value'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicines['total_selling_value'], 2) }}</td>
                                    <td class="text-end">{{ $medicines['total_available_quantity'] }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($medicines['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            {{ $medicines['paginator']->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="expiryMedicinePrintSection">
    <div class="print-header">
        <h2>Expiry Medicine Report</h2>
        <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
    </div>
    
    <table class="print-table">
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Buying Price</th>
                <th>Selling Price</th>
                <th>Available</th>
                <th>Expiry Date</th>
                <th>Days Until Expiry</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicines['data'] as $medicine)
                <tr>
                    <td>
                        {{ $medicine['name'] }}
                        @if($medicine['salt_composition'])
                            <br><small>{{ $medicine['salt_composition'] }}</small>
                        @endif
                    </td>
                    <td>{{ $medicine['category']['name'] }}</td>
                    <td>{{ $medicine['brand']['name'] }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($medicine['buying_price'], 2) }}</td>
                    <td>{{ getCurrencySymbol() }} {{ number_format($medicine['selling_price'], 2) }}</td>
                    <td>{{ $medicine['available_quantity'] }}</td>
                    <td>{{ $medicine['expiry_date'] }}</td>
                    <td>
                        @if($medicine['days_until_expiry'] < 0)
                            Expired {{ abs($medicine['days_until_expiry']) }} days ago
                        @elseif($medicine['days_until_expiry'] == 0)
                            Expires today
                        @else
                            {{ $medicine['days_until_expiry'] }} days
                        @endif
                    </td>
                    <td>
                        @if($medicine['expiry_status'] === 'expired')
                            Expired
                        @elseif($medicine['expiry_status'] === 'expiring_soon')
                            Expiring Soon
                        @else
                            Valid
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No expiring medicine records found.</td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td colspan="3" class="text-end">Total:</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($medicines['total_buying_value'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($medicines['total_selling_value'], 2) }}</td>
                <td>{{ $medicines['total_available_quantity'] }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
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