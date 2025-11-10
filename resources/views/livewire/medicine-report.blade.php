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
                                <i class="fas fa-pills text-white"></i>
                            </span>
                            <select class="form-select" wire:model="stockStatusFilter">
                                <option value="all">All Stock Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="expired">Expired</option>
                                <option value="expiring_soon">Expiring Soon</option>
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
                        <input type="text" class="form-control" placeholder="Search Medicine Name, Salt Composition, Category or Brand"
                               wire:model.debounce.500ms="searchQuery">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medicine Report Table in Card -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Medicine Report</span>
                </h3>
                <!-- Print button moved to main report page -->
            </div>
            <div class="card-body pb-3 pt-0">
                <div id="medicineReportTable">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Medicine</th>
                                    <th class="min-w-100px">Category</th>
                                    <th class="min-w-100px">Brand</th>
                                    <th class="min-w-100px text-end">Buying Price</th>
                                    <th class="min-w-100px text-end">Selling Price</th>
                                    <th class="min-w-70px text-end">Qty</th>
                                    <th class="min-w-70px text-end">Available</th>
                                    <th class="min-w-100px">Expiry Date</th>
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
                                        <td class="text-end">{{ $medicine['quantity'] }}</td>
                                        <td class="text-end">{{ $medicine['available_quantity'] }}</td>
                                        <td>{{ $medicine['expiry_date'] }}</td>
                                        <td class="text-center">
                                            @if($medicine['stock_status'] === 'out_of_stock')
                                                <span class="badge bg-light-danger">Out of Stock</span>
                                            @elseif($medicine['stock_status'] === 'low_stock')
                                                <span class="badge bg-light-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-light-success">In Stock</span>
                                            @endif
                                            
                                            @if($medicine['expiry_status'] === 'expired')
                                                <span class="badge bg-light-danger">Expired</span>
                                            @elseif($medicine['expiry_status'] === 'expiring_soon')
                                                <span class="badge bg-light-warning">Expiring Soon</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No medicine records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicines['total_buying_value'], 2) }}</td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($medicines['total_selling_value'], 2) }}</td>
                                    <td class="text-end">{{ $medicines['total_quantity'] }}</td>
                                    <td class="text-end">{{ $medicines['total_available_quantity'] }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($medicines['total'] > 0)
                        <!-- <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5">
                            <div class="d-flex flex-wrap py-2 mb-3 mb-md-0">
                                <div class="d-flex align-items-center py-1">
                                    <span class="text-muted">Showing {{ ($medicines['paginator']->currentPage() - 1) * $medicines['paginator']->perPage() + 1 }} to {{ min($medicines['paginator']->currentPage() * $medicines['paginator']->perPage(), $medicines['paginator']->total()) }} of {{ $medicines['paginator']->total() }} entries</span>
                                </div>
                            </div>
                        </div> -->
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
<div class="d-none" id="medicinePrintSection">
    <div class="print-header">
        <h1>{{env('APP_NAME')}}</h1>
        <h2>Medicine Report</h2>
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
                <th>Qty</th>
                <th>Available</th>
                <th>Expiry Date</th>
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
                    <td>{{ $medicine['quantity'] }}</td>
                    <td>{{ $medicine['available_quantity'] }}</td>
                    <td>{{ $medicine['expiry_date'] }}</td>
                    <td>
                        @if($medicine['stock_status'] === 'out_of_stock')
                            Out of Stock
                        @elseif($medicine['stock_status'] === 'low_stock')
                            Low Stock
                        @else
                            In Stock
                        @endif
                        
                        @if($medicine['expiry_status'] === 'expired')
                            <br>Expired
                        @elseif($medicine['expiry_status'] === 'expiring_soon')
                            <br>Expiring Soon
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No medicine records found.</td>
                </tr>
            @endforelse
            <tr class="fw-bold">
                <td colspan="3" class="text-end">Total:</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($medicines['total_buying_value'], 2) }}</td>
                <td>{{ getCurrencySymbol() }} {{ number_format($medicines['total_selling_value'], 2) }}</td>
                <td>{{ $medicines['total_quantity'] }}</td>
                <td>{{ $medicines['total_available_quantity'] }}</td>
                <td colspan="2"></td>
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
        
        // Print functionality
        $('#printReportComponent').click(function() {
            // Create new window
            let printWindow = window.open('', '_blank');
            
            // Get the print section content
            let printContent = document.getElementById('medicinePrintSection').innerHTML;
            
            // Add CSS for print styling
            let printStyles = `
                <style>
                    @media print {
                        body { font-family: Arial, sans-serif; }
                        .no-print { display: none !important; }
                        .print-header { text-align: center; margin-bottom: 20px; }
                        .print-header h2 { margin-bottom: 5px; }
                        .print-header h4 { margin-top: 0; color: #555; }
                        .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        .print-table th, .print-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        .print-table th { background-color: #f2f2f2; }
                        .print-table tr:nth-child(even) { background-color: #f9f9f9; }
                        .print-table tr.fw-bold { font-weight: bold; }
                    }
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .no-print { display: flex; justify-content: center; margin-top: 30px; }
                    .print-header { text-align: center; margin-bottom: 20px; }
                    .print-header h2 { margin-bottom: 5px; }
                    .print-header h4 { margin-top: 0; color: #555; }
                    .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    .print-table th, .print-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .print-table th { background-color: #f2f2f2; }
                    .print-table tr:nth-child(even) { background-color: #f9f9f9; }
                    .print-table tr.fw-bold { font-weight: bold; }
                    .btn { display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; 
                           line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; 
                           cursor: pointer; border: 1px solid transparent; border-radius: 4px; }
                    .btn-primary { color: #fff; background-color: #337ab7; border-color: #2e6da4; }
                    .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; }
                    .me-2 { margin-right: 0.5rem; }
                </style>
            `;
            
            // Write to the new window
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Medicine Report</title>
                    ${printStyles}
                </head>
                <body>
                    ${printContent}
                </body>
                </html>
            `);
            
            // Necessary for Firefox
            printWindow.document.close();
        });
    });
</script>
