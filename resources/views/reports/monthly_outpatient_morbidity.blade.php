@extends('layouts.app')
@section('title')
    {{ __('Monthly Outpatient Morbidity Returns') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">{{ __('Monthly Outpatient Morbidity Returns') }}</h1>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary me-2" id="printReport">
                    <i class="fas fa-print me-1"></i> {{ __('Print Report') }}
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Reports') }}
                </a>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0">
                <div class="row">
                    <div class="col-12">
                    @livewire('monthly-outpatient-morbidity-report')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('print-morbidity-report', function() {
                printMorbidityReport();
            });
            
            // Connect the print button to the Livewire event
            document.getElementById('printReport').addEventListener('click', function() {
                window.livewire.emit('printReport');
            });
            
            // Category filter code removed as requested
            
            function printMorbidityReport() {
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                
                // Get the date range from the report
                let dateRange = $('.date-range-display').text().trim();
                dateRange = dateRange.replace(/\s+/g, ' ').trim(); // Clean up whitespace
                
                try {
                    // Get the table content
                    let tableHTML = '';
                    let tableFound = false;
                    
                    // Try different methods to find the table
                    const printSection = document.getElementById('monthlyMorbidityPrintSection');
                    if (printSection) {
                        tableHTML = printSection.innerHTML;
                        tableFound = true;
                    } else {
                        const visibleTable = document.querySelector('table.table-row-dashed');
                        if (visibleTable) {
                            tableHTML = visibleTable.outerHTML;
                            tableFound = true;
                        } else {
                            const jqTable = $('table.table-row-dashed');
                            if (jqTable.length > 0) {
                                tableHTML = jqTable[0].outerHTML;
                                tableFound = true;
                            }
                        }
                    }
                    
                    // Process the table HTML to remove icons and simplify it
                    if (tableFound) {
                        // Create a temporary div to manipulate the HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = tableHTML;
                        
                        // Remove all icons, images, and unnecessary elements
                        tempDiv.querySelectorAll('.fas, .far, .fa, .svg-icon').forEach(el => el.remove());
                        tempDiv.querySelectorAll('img').forEach(el => el.remove());
                        tempDiv.querySelectorAll('button').forEach(el => el.remove());
                        
                        // Get the simplified table HTML
                        tableHTML = tempDiv.innerHTML;
                    }
                    
                    // Create the print content with hospital system standard styling
                    printWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Monthly Outpatient Morbidity Returns</title>
                            <style>
                                body { 
                                    font-family: Arial, sans-serif; 
                                    padding: 20px; 
                                    max-width: 1000px; 
                                    margin: 0 auto; 
                                }
                                    /* Print buttons */
                                .btn-container {
                                    text-align: center;
                                    margin-top: 30px;
                                    margin-bottom: 20px;
                                }
                                /* Reset button styles for print buttons */
                                .btn-container .btn {
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
                                .btn-container .btn-primary {
                                    color: #fff !important;
                                    background-color: #3699FF !important;
                                    border: 1px solid #3699FF !important;
                                }
                                .btn-container .btn-secondary {
                                    color: #3F4254 !important;
                                    background-color: #E4E6EF !important;
                                    border: 1px solid #E4E6EF !important;
                                }
                                .print-header { 
                                    text-align: center; 
                                    margin-bottom: 20px; 
                                    border-bottom: 1px solid #ddd;
                                    padding-bottom: 10px;
                                }
                                .print-header h1 { 
                                    font-size: 22px; 
                                    margin-bottom: 8px; 
                                }
                                .print-header p { 
                                    font-size: 14px; 
                                    margin: 4px 0; 
                                    color: #555;
                                }
                                table { 
                                    width: 100%; 
                                    border-collapse: collapse; 
                                    margin-bottom: 20px; 
                                }
                                th, td { 
                                    border: 1px solid #ddd; 
                                    padding: 8px; 
                                    text-align: left; 
                                }
                                th { 
                                    background-color: #f5f5f5; 
                                    font-weight: bold; 
                                }
                                tr:nth-child(even) { 
                                    background-color: #f9f9f9; 
                                }
                                .badge { 
                                    padding: 4px 8px; 
                                    border-radius: 4px; 
                                    font-size: 12px; 
                                    font-weight: bold; 
                                }
                                a {
                                    text-decoration: none !important;
                                    color: inherit !important;
                                }
                                .print-footer { 
                                    text-align: center; 
                                    margin-top: 20px; 
                                    font-size: 12px; 
                                    color: #777; 
                                    border-top: 1px solid #ddd;
                                    padding-top: 10px;
                                }
                                @media print {
                                    .btn-container {
                                        display: none !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="print-header">
                                <h1>{{env('APP_NAME')}}</h1>
                                <h2>Monthly Outpatient Morbidity Returns</h2>
                                <p>Period: ${dateRange}</p>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                            
                            <div id="print-content">
                                ${tableFound ? tableHTML : `<p>No morbidity records found</p>`}
                            </div>
                            
                            <div class="print-footer">
                                <p>  ${new Date().getFullYear()} Hospital Management System</p>
                            </div>
                            
                            <div class="btn-container">
                                <button type="button" class="btn btn-primary" onclick="window.print();">
                                    Print Now
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="window.close();">
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

                } catch (e) {
                    console.error('Error printing report:', e);
                    alert('Error printing report: ' + e.message);
                    if (printWindow) printWindow.close();
                }
            }
        });
    </script>
@endsection
