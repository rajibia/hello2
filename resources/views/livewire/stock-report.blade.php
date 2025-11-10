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
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-layer-group text-white"></i>
                            </span>
                            <select class="form-select" wire:model="categoryFilter">
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-copyright text-white"></i>
                            </span>
                            <select class="form-select" wire:model="brandFilter">
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-box text-white"></i>
                            </span>
                            <select class="form-select" wire:model="stockStatusFilter">
                                @foreach($stockStatuses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-calendar-times text-white"></i>
                            </span>
                            <select class="form-select" wire:model="expiryFilter">
                                @foreach($expiryStatuses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <div class="input-group w-100">
                    <span class="input-group-text bg-primary">
                        <i class="fas fa-search text-white"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search by name, category, brand, or composition"
                           wire:model.debounce.500ms="searchQuery">
                    <button type="button" class="btn btn-light-secondary" wire:click="clearFilters">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>

            <!-- Stock Summary Cards -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-primary">
                                        <i class="fas fa-boxes text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_items'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Total Items</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-success">
                                        <i class="fas fa-pills text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_available_quantity'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Available Units</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-info">
                                        <i class="fas fa-warehouse text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_store_quantity'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Store Units</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-warning">
                                        <i class="fas fa-money-bill-alt text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ getCurrencySymbol() }} {{ number_format($stockItems['total_value'], 2) }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Total Stock Value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Table in Card -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div id="stockPrintSection">
                        <div class="table-responsive">
                            <table id="stockTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">{{ __('Medicine Name') }}</th>
                                        <th class="min-w-100px">{{ __('Category') }}</th>
                                        <th class="min-w-100px">{{ __('Brand') }}</th>
                                        <th class="min-w-100px">{{ __('Composition') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Buying Price') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Selling Price') }}</th>
                                        <th class="min-w-80px text-center">{{ __('Dispensary') }}</th>
                                        <th class="min-w-80px text-center">{{ __('Store') }}</th>
                                        <th class="min-w-80px text-center">{{ __('Available') }}</th>
                                        <th class="min-w-100px">{{ __('Expiry Date') }}</th>
                                        <th class="min-w-100px">{{ __('Status') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockItems['data'] as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['category'] }}</td>
                                            <td>{{ $item['brand'] }}</td>
                                            <td>{{ $item['salt_composition'] }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['buying_price'], 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['selling_price'], 2) }}</td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-center">{{ $item['store_quantity'] }}</td>
                                            <td class="text-center">{{ $item['available_quantity'] }}</td>
                                            <td>
                                                {{ $item['expiry_date'] }}
                                                @if($item['expiry_date'] !== 'N/A')
                                                    @if($item['expiry_status'] === 'Expired')
                                                        <span class="badge bg-light-danger">Expired</span>
                                                    @elseif($item['expiry_status'] === 'Expiring Soon')
                                                        <span class="badge bg-light-warning">{{ $item['days_until_expiry'] }} days left</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    // Determine badge color based on stock status
                                                    $badgeColor = 'danger';
                                                    if ($item['stock_status'] === 'In Stock') {
                                                        $badgeColor = 'success';
                                                    } elseif ($item['stock_status'] === 'Low Stock') {
                                                        $badgeColor = 'warning';
                                                    }
                                                @endphp
                                                <span class="badge bg-light-{{ $badgeColor }}">{{ $item['stock_status'] }}</span>
                                            </td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['total_value'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">{{ __('No stock items found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="6" class="text-end">{{ __('Totals:') }}</td>
                                        <td class="text-center">{{ $stockItems['total_quantity'] }}</td>
                                        <td class="text-center">{{ $stockItems['total_store_quantity'] }}</td>
                                        <td class="text-center">{{ $stockItems['total_available_quantity'] }}</td>
                                        <td colspan="2"></td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($stockItems['total_value'], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($stockItems['total'] > 0)
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div>
                                    {{ $stockItems['paginator']->onEachSide(1)->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Section (Hidden) -->
<div class="d-none" id="stockPrintSection">
    <!-- This section will be populated dynamically by JavaScript -->
</div>

<script>
    document.addEventListener('livewire:load', function () {
        // Any initialization code if needed
    });
</script>

@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printReport').click(function() {
                console.log('Print button clicked');
                
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                
                try {
                    // Get the table HTML
                    let tableHTML = '';
                    let tableFound = false;
                    
                    // Try to get the table content
                    const visibleTable = document.querySelector('#stockTable');
                    if (visibleTable) {
                        tableHTML = visibleTable.outerHTML;
                        tableFound = true;
                        console.log('Table found in visible section');
                    }
                    
                    if (!tableFound) {
                        console.error('Table not found');
                        alert('Error: Could not find report table to print.');
                        printWindow.close();
                        return;
                    }
                    
                    // Process the table HTML to remove icons and simplify it
                    if (tableFound) {
                        // Create a temporary div to manipulate the HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = tableHTML;
                        
                        // Remove all icons, images, and unnecessary elements
                        const icons = tempDiv.querySelectorAll('i, svg, img, .avatar-circle, .avatar, .icon');
                        icons.forEach(icon => icon.remove());
                        
                        // Remove any action buttons or links that shouldn't be printed
                        const actionButtons = tempDiv.querySelectorAll('.action-btn, .btn, button');
                        actionButtons.forEach(btn => btn.remove());
                        
                        // Get the simplified table HTML
                        tableHTML = tempDiv.innerHTML;
                    }
                    
                    // Create the print content
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Stock Report</title>
                            <style>
                                body { 
                                    font-family: Arial, sans-serif; 
                                    padding: 30px; 
                                    max-width: 1000px; 
                                    margin: 0 auto;
                                }
                                .print-header { 
                                    text-align: center; 
                                    margin-bottom: 30px; 
                                }
                                .print-header h1 { 
                                    font-size: 24px; 
                                    font-weight: bold; 
                                    margin-bottom: 5px; 
                                }
                                .print-header p { 
                                    font-size: 14px; 
                                    color: #555; 
                                    margin-bottom: 5px; 
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 20px;
                                    margin-bottom: 30px;
                                }
                                th, td {
                                    border: 1px solid #ddd;
                                    padding: 10px;
                                    text-align: left;
                                    font-size: 12px;
                                }
                                th {
                                    background-color: #f2f2f2;
                                    font-weight: bold;
                                }
                                /* Convert badges to simple text */
                                .badge {
                                    display: inline;
                                    padding: 0;
                                    font-size: inherit;
                                    font-weight: normal;
                                    line-height: inherit;
                                    text-align: inherit;
                                    white-space: inherit;
                                    vertical-align: inherit;
                                    border-radius: 0;
                                    background-color: transparent !important;
                                }
                                /* Reset all badge colors to default text color */
                                .bg-light-success, .bg-light-danger, .bg-light-primary, .bg-light-warning,
                                .bg-success, .bg-danger, .bg-primary, .bg-warning,
                                .text-success, .text-danger, .text-primary, .text-warning {
                                    color: inherit !important;
                                    background-color: transparent !important;
                                }
                                /* Hide unnecessary elements */
                                .avatar-circle, .avatar, .icon, svg, i, img, .action-btn {
                                    display: none !important;
                                }
                                /* Remove link styling */
                                a {
                                    text-decoration: none;
                                    color: inherit;
                                }
                                /* Print buttons */
                                .no-print {
                                    text-align: center;
                                    margin-top: 30px;
                                    margin-bottom: 20px;
                                }
                                /* Reset button styles for print buttons */
                                .no-print .btn {
                                    display: inline-block !important;
                                    font-weight: 500 !important;
                                    text-align: center !important;
                                    vertical-align: middle !important;
                                    user-select: none !important;
                                    padding: 0.65rem 1rem !important;
                                    font-size: 1rem !important;
                                    line-height: 1.5 !important;
                                    border-radius: 0.42rem !important;
                                    cursor: pointer !important;
                                    margin: 0 5px !important;
                                }
                                .no-print .btn-primary {
                                    color: #fff !important;
                                    background-color: #3699FF !important;
                                    border: 1px solid #3699FF !important;
                                }
                                .no-print .btn-secondary {
                                    color: #3F4254 !important;
                                    background-color: #E4E6EF !important;
                                    border: 1px solid #E4E6EF !important;
                                }
                                .print-footer { 
                                    text-align: center; 
                                    margin-top: 30px; 
                                    font-size: 12px; 
                                    color: #777; 
                                    padding-bottom: 20px;
                                }
                                @media print {
                                    body { 
                                        padding: 15px; 
                                        margin: 0 auto;
                                    }
                                    .no-print {
                                        display: none !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="print-header">
                                <h1>{{env('APP_NAME')}}</h1>
                                <h2>Inventory Stock Report</h2>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                            
                            <div id="print-content">
                                ${tableFound ? tableHTML : `<p>No stock items found</p>`}
                            </div>
                            
                            <div class="print-footer">
                                <p>© ${new Date().getFullYear()} Hospital Management System</p>
                            </div>
                            
                            <div class="text-center mt-4 no-print">
                                <button type="button" class="btn btn-primary btn-print" onclick="window.print();" style="display: inline-block !important;">
                                    Print Now
                                </button>
                                <button type="button" class="btn btn-secondary btn-close" onclick="window.close();" style="display: inline-block !important;">
                                    Close
                                </button>
                            </div>
                        </body>
                        </html>
                    `);
                    
                    // Finish and print
                    printWindow.document.close();
                    printWindow.focus();
                    
                     // Add a small delay before printing to ensure content is fully loaded
                     setTimeout(function() {
                        printWindow.print();
                    }, 500);
                    
                } catch (error) {
                    console.error('Error preparing print content:', error);
                    printWindow.close();
                }
            });
        });
    </script>
@endsection
