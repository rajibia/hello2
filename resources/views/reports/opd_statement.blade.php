@extends('layouts.app')
@section('title')
    {{ __('OPD Statement Report') }}
@endsection
@section('page_css')
    <!-- No additional CSS needed -->
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('OPD Statement Report') }}</h1>
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
                        @livewire('opd-statement-report')
                    </div>
                </div>
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
                    // Get the table content directly from the DOM
                    console.log('Looking for table in OPD Statement Report');
                    
                    // Get the table HTML
                    let tableHTML = '';
                    let tableFound = false;
                    
                    // Method 1: Try getting the table from the visible content
                    const visibleTable = document.querySelector('table.table-row-dashed');
                    if (visibleTable) {
                        tableHTML = visibleTable.outerHTML;
                        tableFound = true;
                        console.log('Table found in visible content');
                    }
                    
                    // Method 2: Try using jQuery to find the table
                    if (!tableFound) {
                        const jqTable = $('table.table-row-dashed');
                        if (jqTable.length > 0) {
                            tableHTML = jqTable[0].outerHTML;
                            tableFound = true;
                            console.log('Table found using jQuery');
                        }
                    }
                    
                    // Method 3: Try getting the print section
                    if (!tableFound) {
                        const printSection = document.getElementById('opdStatementPrintSection');
                        if (printSection) {
                            tableHTML = printSection.innerHTML;
                            tableFound = true;
                            console.log('Print section found');
                        }
                    }
                    
                    console.log('Table found:', tableFound);
                    
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
                        
                        // Get the simplified table HTML
                        tableHTML = tempDiv.innerHTML;
                    }
                    
                    // Create the print content
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>OPD Statement Report</title>
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
                                    margin-bottom: 10px; 
                                }
                                .print-header p { 
                                    font-size: 14px; 
                                    color: #555; 
                                    margin: 5px 0; 
                                }
                                /* Remove link styling */
                                a {
                                    text-decoration: none !important;
                                    color: inherit !important;
                                }
                                /* Print buttons */
                                .no-print {
                                    text-align: center !important;
                                    margin-top: 30px !important;
                                    margin-bottom: 20px !important;
                                    display: flex !important;
                                    justify-content: center !important;
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
                                    background-color: #f8f9fa;
                                    font-weight: bold;
                                }
                                tr:nth-child(even) {
                                    background-color: #f2f2f2;
                                }
                                .badge {
                                    padding: 5px 10px;
                                    border-radius: 4px;
                                    font-size: 12px;
                                    font-weight: bold;
                                }
                                /* Reset all badge colors to default text color */
                                .bg-light-success, .bg-light-danger, .bg-light-primary, .bg-light-warning,
                                .bg-success, .bg-danger, .bg-primary, .bg-warning,
                                .text-success, .text-danger, .text-primary, .text-warning {
                                    color: inherit !important;
                                    background-color: transparent !important;
                                    border: 1px solid #ddd !important;
                                }
                                .print-footer { 
                                    text-align: center; 
                                    margin-top: 30px; 
                                    font-size: 12px; 
                                    color: #777; 
                                    padding-bottom: 20px;
                                }
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
                                <h2>OPD Statement Report</h2>
                                <p>Period: ${dateRange}</p>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                            
                            <div id="print-content">
                                ${tableFound ? tableHTML : `<p>No OPD statement records found</p>`}
                            </div>
                            
                            <div class="print-footer">
                                <p>Copyright ${new Date().getFullYear()} Hospital Management System</p>
                            </div>
                            
                            <div class="text-center mt-4 no-print" style="display: flex; justify-content: center;">
                                <button type="button" class="btn btn-primary btn-print me-2" onclick="window.print();" style="display: inline-block !important;">
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
                    }, 1000);
                    
                } catch (e) {
                    console.error('Error printing report:', e);
                    alert('Error printing report: ' + e.message);
                    if (printWindow) printWindow.close();
                }
            });
        });
    </script>
@endsection
