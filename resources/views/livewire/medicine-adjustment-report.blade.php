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
            
                <div class="col-lg-8 col-md-12">
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
                        <input type="text" class="form-control" placeholder="Search Medicine Name, Category, Brand or User"
                               wire:model.debounce.500ms="searchQuery">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medicine Adjustment Report Table in Card -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Medicine Adjustment Report</span>
                </h3>
                <!-- Print button moved to main report page -->
            </div>
            <div class="card-body pb-3 pt-0">
                <div id="medicineAdjustmentReportTable">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-100px">Date & Time</th>
                                    <th class="min-w-150px">Medicine</th>
                                    <th class="min-w-100px">Category</th>
                                    <th class="min-w-100px">Brand</th>
                                    <th class="min-w-100px text-end">Initial Dispensary</th>
                                    <th class="min-w-100px text-end">Current Dispensary</th>
                                    <th class="min-w-70px text-end">Dispensary Change</th>
                                    <th class="min-w-100px text-end">Initial Store</th>
                                    <th class="min-w-100px text-end">Current Store</th>
                                    <th class="min-w-70px text-end">Store Change</th>
                                    <th class="min-w-100px">Adjusted By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adjustments['data'] as $adjustment)
                                    <tr>
                                        <td>{{ $adjustment['date'] }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $adjustment['medicine']['name'] }}</span>
                                                @if($adjustment['medicine']['salt_composition'])
                                                    <span class="text-muted">{{ $adjustment['medicine']['salt_composition'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $adjustment['category']['name'] }}</td>
                                        <td>{{ $adjustment['brand']['name'] }}</td>
                                        <td class="text-end">{{ $adjustment['initial_dispensary_quantity'] }}</td>
                                        <td class="text-end">{{ $adjustment['current_dispensary_quantity'] }}</td>
                                        <td class="text-end">
                                            @if($adjustment['dispensary_change'] > 0)
                                                <span class="badge badge-light-success">+{{ $adjustment['dispensary_change'] }}</span>
                                            @elseif($adjustment['dispensary_change'] < 0)
                                                <span class="badge badge-light-danger">{{ $adjustment['dispensary_change'] }}</span>
                                            @else
                                                <span class="badge badge-light-secondary">0</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $adjustment['initial_store_quantity'] }}</td>
                                        <td class="text-end">{{ $adjustment['current_store_quantity'] }}</td>
                                        <td class="text-end">
                                            @if($adjustment['store_change'] > 0)
                                                <span class="badge badge-light-success">+{{ $adjustment['store_change'] }}</span>
                                            @elseif($adjustment['store_change'] < 0)
                                                <span class="badge badge-light-danger">{{ $adjustment['store_change'] }}</span>
                                            @else
                                                <span class="badge badge-light-secondary">0</span>
                                            @endif
                                        </td>
                                        <td>{{ $adjustment['user']['name'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No adjustment records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="6" class="text-end">Total Dispensary Change:</td>
                                    <td class="text-end">
                                        @if($adjustments['total_dispensary_change'] > 0)
                                            <span class="badge badge-light-success">+{{ $adjustments['total_dispensary_change'] }}</span>
                                        @elseif($adjustments['total_dispensary_change'] < 0)
                                            <span class="badge badge-light-danger">{{ $adjustments['total_dispensary_change'] }}</span>
                                        @else
                                            <span class="badge badge-light-secondary">0</span>
                                        @endif
                                    </td>
                                    <td colspan="2" class="text-end">Total Store Change:</td>
                                    <td class="text-end">
                                        @if($adjustments['total_store_change'] > 0)
                                            <span class="badge badge-light-success">+{{ $adjustments['total_store_change'] }}</span>
                                        @elseif($adjustments['total_store_change'] < 0)
                                            <span class="badge badge-light-danger">{{ $adjustments['total_store_change'] }}</span>
                                        @else
                                            <span class="badge badge-light-secondary">0</span>
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($adjustments['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            {{ $adjustments['paginator']->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="medicineAdjustmentPrintSection">
    <div class="print-header">
        <h2>Medicine Adjustment Report</h2>
        <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
    </div>
    
    <table class="print-table">
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Medicine</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Initial Dispensary</th>
                <th>Current Dispensary</th>
                <th>Dispensary Change</th>
                <th>Initial Store</th>
                <th>Current Store</th>
                <th>Store Change</th>
                <th>Adjusted By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adjustments['data'] as $adjustment)
                <tr>
                    <td>{{ $adjustment['date'] }}</td>
                    <td>
                        <div>
                            <div>{{ $adjustment['medicine']['name'] }}</div>
                            @if($adjustment['medicine']['salt_composition'])
                                <div class="small">{{ $adjustment['medicine']['salt_composition'] }}</div>
                            @endif
                        </div>
                    </td>
                    <td>{{ $adjustment['category']['name'] }}</td>
                    <td>{{ $adjustment['brand']['name'] }}</td>
                    <td>{{ $adjustment['initial_dispensary_quantity'] }}</td>
                    <td>{{ $adjustment['current_dispensary_quantity'] }}</td>
                    <td>
                        @if($adjustment['dispensary_change'] > 0)
                            +{{ $adjustment['dispensary_change'] }}
                        @else
                            {{ $adjustment['dispensary_change'] }}
                        @endif
                    </td>
                    <td>{{ $adjustment['initial_store_quantity'] }}</td>
                    <td>{{ $adjustment['current_store_quantity'] }}</td>
                    <td>
                        @if($adjustment['store_change'] > 0)
                            +{{ $adjustment['store_change'] }}
                        @else
                            {{ $adjustment['store_change'] }}
                        @endif
                    </td>
                    <td>{{ $adjustment['user']['name'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No adjustment records found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end">Total Dispensary Change:</td>
                <td>
                    @if($adjustments['total_dispensary_change'] > 0)
                        +{{ $adjustments['total_dispensary_change'] }}
                    @else
                        {{ $adjustments['total_dispensary_change'] }}
                    @endif
                </td>
                <td colspan="2" class="text-end">Total Store Change:</td>
                <td>
                    @if($adjustments['total_store_change'] > 0)
                        +{{ $adjustments['total_store_change'] }}
                    @else
                        {{ $adjustments['total_store_change'] }}
                    @endif
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure start date is never after end date
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                if (endDateInput.value && this.value > endDateInput.value) {
                    endDateInput.value = this.value;
                    Livewire.emit('updatedEndDate');
                }
            });
            
            endDateInput.addEventListener('change', function() {
                if (startDateInput.value && this.value < startDateInput.value) {
                    startDateInput.value = this.value;
                    Livewire.emit('updatedStartDate');
                }
            });
        }
    });
</script>
