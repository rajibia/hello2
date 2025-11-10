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
                                <i class="fas fa-exchange-alt text-white"></i>
                            </span>
                            <select class="form-select" wire:model="transferDirectionFilter">
                                <option value="all">All Transfers</option>
                                <option value="dispensary_to_store">Dispensary to Store</option>
                                <option value="store_to_dispensary">Store to Dispensary</option>
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
        
        <!-- Medicine Transfer Report Table in Card -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Medicine Transfer Report</span>
                </h3>
                <!-- Print button moved to main report page -->
            </div>
            <div class="card-body pb-3 pt-0">
                <div id="medicineTransferReportTable">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-100px">Date & Time</th>
                                    <th class="min-w-150px">Medicine</th>
                                    <th class="min-w-100px">Category</th>
                                    <th class="min-w-100px">Brand</th>
                                    <th class="min-w-100px">From</th>
                                    <th class="min-w-100px">To</th>
                                    <th class="min-w-70px text-end">Quantity</th>
                                    <th class="min-w-100px text-end">Dispensary Balance</th>
                                    <th class="min-w-100px text-end">Store Balance</th>
                                    <th class="min-w-100px">Transferred By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers['data'] as $transfer)
                                    <tr>
                                        <td>{{ $transfer['date'] }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $transfer['medicine']['name'] }}</span>
                                                @if($transfer['medicine']['salt_composition'])
                                                    <span class="text-muted">{{ $transfer['medicine']['salt_composition'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $transfer['category']['name'] }}</td>
                                        <td>{{ $transfer['brand']['name'] }}</td>
                                        <td>{{ $transfer['transfer_from'] }}</td>
                                        <td>{{ $transfer['transfer_to'] }}</td>
                                        <td class="text-end">{{ $transfer['transfer_quantity'] }}</td>
                                        <td class="text-end">{{ $transfer['dispensary_balance'] }}</td>
                                        <td class="text-end">{{ $transfer['store_balance'] }}</td>
                                        <td>{{ $transfer['user']['name'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No transfer records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="6" class="text-end">Total:</td>
                                    <td class="text-end">{{ $transfers['total_transfer_quantity'] }}</td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end text-muted">Dispensary to Store:</td>
                                    <td class="text-end text-muted">{{ $transfers['total_dispensary_to_store'] }}</td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end text-muted">Store to Dispensary:</td>
                                    <td class="text-end text-muted">{{ $transfers['total_store_to_dispensary'] }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($transfers['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            {{ $transfers['paginator']->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="medicineTransferPrintSection">
    <div class="print-header">
        <h2>Medicine Transfer Report</h2>
        <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
    </div>
    
    <table class="print-table">
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Medicine</th>
                <th>Category</th>
                <th>Brand</th>
                <th>From</th>
                <th>To</th>
                <th>Quantity</th>
                <th>Dispensary Balance</th>
                <th>Store Balance</th>
                <th>Transferred By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transfers['data'] as $transfer)
                <tr>
                    <td>{{ $transfer['date'] }}</td>
                    <td>
                        {{ $transfer['medicine']['name'] }}
                        @if($transfer['medicine']['salt_composition'])
                            <br><small>{{ $transfer['medicine']['salt_composition'] }}</small>
                        @endif
                    </td>
                    <td>{{ $transfer['category']['name'] }}</td>
                    <td>{{ $transfer['brand']['name'] }}</td>
                    <td>{{ $transfer['transfer_from'] }}</td>
                    <td>{{ $transfer['transfer_to'] }}</td>
                    <td>{{ $transfer['transfer_quantity'] }}</td>
                    <td>{{ $transfer['dispensary_balance'] }}</td>
                    <td>{{ $transfer['store_balance'] }}</td>
                    <td>{{ $transfer['user']['name'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No transfer records found.</td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td colspan="6" class="text-end">Total:</td>
                <td>{{ $transfers['total_transfer_quantity'] }}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">Dispensary to Store:</td>
                <td>{{ $transfers['total_dispensary_to_store'] }}</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="6" class="text-end">Store to Dispensary:</td>
                <td>{{ $transfers['total_store_to_dispensary'] }}</td>
                <td colspan="3"></td>
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
