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
                            <select class="form-select" wire:model="expenseHeadFilter">
                                @foreach($expenseHeads as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
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
            
            <div id="reportDateFilter" class="text-muted fw-normal">
                <i class="fas fa-calendar-alt me-1"></i> {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </div>
            
            <div class="mb-5">
                <div class="input-group w-100">
                    <input type="text" class="form-control" placeholder="Search by name or invoice number"
                           wire:model.debounce.500ms="searchQuery">
                </div>
            </div>

            <!-- Expenses Table in Card -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div id="expensesPrintSection">
                        <div class="table-responsive">
                            <table id="expensesTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-100px">{{ __('Date') }}</th>
                                        <th class="min-w-100px">{{ __('Invoice Number') }}</th>
                                        <th class="min-w-150px">{{ __('Expense Head') }}</th>
                                        <th class="min-w-150px">{{ __('Name') }}</th>
                                        <th class="min-w-150px">{{ __('Description') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses['data'] as $expense)
                                        <tr>
                                            <td>{{ $expense['date'] }}</td>
                                            <td>{{ $expense['invoice_number'] ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    // Determine badge color based on expense head ID
                                                    $badgeColors = [
                                                        '1' => 'success',  // Equipment
                                                        '2' => 'primary', // Building
                                                        '3' => 'info',    // Utilities
                                                        '4' => 'warning', // Material
                                                        '5' => 'danger',  // Staff
                                                        '6' => 'dark',    // Laboratory
                                                        '7' => 'secondary', // Kitchen
                                                        '8' => 'primary', // Repair
                                                        '9' => 'warning', // Transport
                                                        '10' => 'info',   // Cleaning
                                                    ];
                                                    $badgeColor = $badgeColors[$expense['expense_head_id']] ?? 'primary';
                                                @endphp
                                                <span class="badge bg-light-{{ $badgeColor }}">{{ $expense['expense_head'] }}</span>
                                            </td>
                                            <td>{{ $expense['name'] }}</td>
                                            <td>{{ $expense['description'] ?? 'N/A' }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($expense['amount'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('No expense records found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="5" class="text-end">{{ __('Total:') }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($expenses['total_amount'], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                    </div>

                    <!-- Pagination and Print Button -->
                    @if($expenses['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div>
                                {{ $expenses['paginator']->onEachSide(1)->links() }}
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
<div class="d-none" id="expensesPrintSection">
    <!-- This section will be populated dynamically by JavaScript -->
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

@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printReport').click(function() {
                console.log('Print button clicked');
                
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                
                // Get the date range from the report
                let dateRange = $('#reportDateFilter').text().trim();
                dateRange = dateRange.replace(/\s+/g, ' ').trim(); // Clean up whitespace
                console.log('Date range:', dateRange);
                
                try {
                    // Get the table HTML
                    let tableHTML = '';
                    let tableFound = false;
                    
                    // Try to get the table content
                    const visibleTable = document.querySelector('#expensesTable');
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
                            <title>Expenses Report</title>
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
                                <h2>Expenses Report</h2>
                                <p>Period: ${dateRange}</p>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                            
                            <div id="print-content">
                                ${tableFound ? tableHTML : `<p>No expense records found</p>`}
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
