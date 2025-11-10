@extends('layouts.app')
@section('title')
    {{ __('Transaction Report') }}
@endsection
@section('page_css')
    <!-- No additional CSS needed -->
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Transaction Report') }}</h1>
            <div>
                <button id="printReport" class="btn btn-primary me-2">
                    <i class="fas fa-print"></i> {{ __('Print Report') }}
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                </a>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0">
                <div class="row">
                    <div class="col-12">
                        @livewire('transaction-report')
                    </div>
                </div>
            </div>
        </div>

    <!-- Print Section (Hidden) -->
    <div class="print-only" id="transactionReportPrintSection" style="display: none;">
        <div class="print-header">
            <h1>{{env('APP_NAME')}}</h1>
            <h2>Transaction Report</h2>
            <p class="date-range-print">Period: </p>
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        </div>
        
        <div class="print-content">
            <table class="table table-bordered">
                <thead style="background-color: #f5f8fa;">
                    <tr>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Patient/User') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Payment Method') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody id="printTableBody">
                    <!-- This will be populated by JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">{{ __('Total Amount') }}:</th>
                        <th id="printTotalAmount"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="print-footer" style="text-align: center;">
            <p>© {{ date('Y') }} Hospital Management System</p>
        </div>
        
        <div class="no-print" style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 20px;">
            <button class="btn btn-primary me-2" onclick="window.print();" style="display: inline-block !important;">
                Print Now
            </button>
            <button class="btn btn-secondary" onclick="window.close();" style="display: inline-block !important;">
                Close
            </button>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printReport').click(function() {
                console.log('Print button clicked');
                
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                
                // Get the date range from the report
                let dateRange = $('.date-range-display').text().trim();
                dateRange = dateRange.replace(/\s+/g, ' ').trim(); // Clean up whitespace
                console.log('Date range:', dateRange);
                
                try {
                    // Get the print section content
                    const printSection = document.getElementById('transactionReportPrintSection');
                    
                    if (!printSection) {
                        console.error('Print section not found');
                        return;
                    }
                    
                    // Update the date range in the print section
                    const dateRangePrint = printSection.querySelector('.date-range-print');
                    if (dateRangePrint) {
                        dateRangePrint.textContent = 'Period: ' + dateRange;
                    }
                    
                    // Get the transaction data from the table
                    const tableRows = document.querySelectorAll('.table-row-dashed tbody tr');
                    const printTableBody = printSection.querySelector('#printTableBody');
                    const printTotalAmount = printSection.querySelector('#printTotalAmount');
                    
                    // Clear existing content
                    if (printTableBody) {
                        printTableBody.innerHTML = '';
                    }
                    
                    // Populate the print table with data from the visible table
                    tableRows.forEach(row => {
                        const newRow = document.createElement('tr');
                        
                        // Get all cells except the last one (which contains actions)
                        const cells = row.querySelectorAll('td');
                        
                        for (let i = 0; i < cells.length; i++) {
                            const newCell = document.createElement('td');
                            // Clean the content (remove buttons, icons, etc.)
                            const cellContent = cells[i].cloneNode(true);
                            
                            // Remove icons and buttons
                            const icons = cellContent.querySelectorAll('i, svg, img, .avatar-circle, .avatar, .icon');
                            icons.forEach(icon => icon.remove());
                            
                            const buttons = cellContent.querySelectorAll('.btn, button, .action-btn');
                            buttons.forEach(btn => btn.remove());
                            
                            newCell.innerHTML = cellContent.innerHTML;
                            newRow.appendChild(newCell);
                        }
                        
                        printTableBody.appendChild(newRow);
                    });
                    
                    // Update the total amount
                    if (printTotalAmount) {
                        const totalAmountText = document.querySelector('.card-header .badge-success')?.textContent || '';
                        printTotalAmount.textContent = totalAmountText;
                    }
                    
                    // Get the print section content and clone it
                    const printContent = printSection.cloneNode(true);
                    
                    // Process the content to remove any unnecessary elements
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = printContent.innerHTML;
                    
                    // Create the print content with proper styling
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Transaction Report</title>
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
                                    margin-bottom: 5px;
                                }
                                .print-header p {
                                    margin: 5px 0;
                                    color: #555;
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
                                }
                                th {
                                    background-color: #f2f2f2;
                                    font-weight: bold;
                                }
                                /* Reset all badge colors to default text color */
                                .bg-light-success, .bg-light-danger, .bg-light-primary, .bg-light-warning,
                                .bg-success, .bg-danger, .bg-primary, .bg-warning,
                                .text-success, .text-danger, .text-primary, .text-warning {
                                    color: inherit !important;
                                    background-color: transparent !important;
                                }
                                /* Remove link styling */
                                a {
                                    text-decoration: none !important;
                                    color: inherit !important;
                                }
                                .no-print {
                                    display: flex;
                                    justify-content: center;
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
                            </style>
                        </head>
                        <body>
                            ${printContent.innerHTML}
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
                    
                } catch (e) {
                    console.error('Error printing report:', e);
                    alert('Error printing report: ' + e.message);
                    if (printWindow) printWindow.close();
                }
            });
            
            // Listen for Livewire print event
            window.addEventListener('print-transaction-report', event => {
                $('#printReport').click();
            });
        });
    </script>
@endsection
