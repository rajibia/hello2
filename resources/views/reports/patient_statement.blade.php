@extends('layouts.app')
@section('title')
    {{ __('Patient Statement Report') }}
@endsection
@section('content')
@include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Patient Statement Report') }}</h1>
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
                        @livewire('patient-statement-report')
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
                    // Get the table HTML
                    let tableHTML = '';
                    let tableFound = false;
                    
                    // Try to get the table content
                    const visibleTable = document.querySelector('.table-responsive table');
                    if (visibleTable) {
                        tableHTML = visibleTable.outerHTML;
                        tableFound = true;
                        console.log('Table found in visible section');
                    }
                    
                    // Get patient information if available
                    let patientInfo = '';
                    const patientName = document.querySelector('.patient-info h3');
                    const patientDetails = document.querySelectorAll('.patient-info p');
                    
                    if (patientName) {
                        patientInfo += `<h3>${patientName.textContent}</h3>`;
                        patientDetails.forEach(detail => {
                            patientInfo += `<p>${detail.textContent}</p>`;
                        });
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
                            <title>Patient Statement Report</title>
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
                                .patient-info {
                                    margin-bottom: 20px;
                                }
                                .patient-info h3 {
                                    margin-bottom: 5px;
                                }
                                .patient-info p {
                                    margin: 2px 0;
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
                                <h2>Patient Statement Report</h2>
                                <p>Period: ${dateRange}</p>
                                <p>Generated on: ${new Date().toLocaleString()}</p>
                            </div>
                            
                            <div class="patient-info">
                                ${patientInfo}
                            </div>
                            
                            <div id="print-content">
                                ${tableFound ? tableHTML : `<p>No patient statement records found</p>`}
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
                    
                } catch (e) {
                    console.error('Error printing report:', e);
                    alert('Error printing report: ' + e.message);
                    if (printWindow) printWindow.close();
                }
            });
        });
    </script>
@endsection
