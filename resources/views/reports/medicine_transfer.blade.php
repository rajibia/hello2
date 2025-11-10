@extends('layouts.app')
@section('title')
    {{ __('messages.medicine.medicine_transfer_report') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">
    <style>
        @media print {
            @page {
                margin: 10px;
            }
            body {
                padding: 10px !important;
                font-size: 12px !important;
            }
            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .print-table th, .print-table td {
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 12px;
            }
            .print-table th {
                background-color: #f2f2f2;
                text-align: left;
            }
            .print-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header h2 {
                margin-bottom: 5px;
            }
            .print-header h4 {
                margin-top: 0;
                color: #6c757d;
            }
            .no-print {
                display: none !important;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
            .badge {
                border: none !important;
                background-color: transparent !important;
                color: #000 !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1>{{ __('messages.medicine.medicine_transfer_report') }}</h1>
                <div class="d-flex align-items-center">
                    <button id="printReport" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> {{ __('messages.common.print') }}
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                    </a>
                </div>
            </div>
            
            @livewire('medicine-transfer-report')
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
            let dateRange = '';
            try {
                dateRange = $('.date-range-display').text().trim();
                dateRange = dateRange.replace(/\s+/g, ' ').trim(); // Clean up whitespace
                console.log('Date range:', dateRange);
            } catch (e) {
                console.warn('Could not get date range:', e);
            }
            
            try {
                // Get the table HTML
                let tableHTML = '';
                let tableFound = false;
                
                // Try to get the table content
                const visibleTable = document.querySelector('#medicineTransferReportTable .table-responsive table');
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
                    
                    // Convert badges to plain text
                    const badges = tempDiv.querySelectorAll('.badge');
                    badges.forEach(badge => {
                        badge.classList.remove('badge');
                        badge.classList.remove('bg-light-success', 'bg-light-danger', 'bg-light-warning', 'bg-light-primary');
                        badge.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-primary');
                    });
                    
                    // Get the simplified table HTML
                    tableHTML = tempDiv.innerHTML;
                }
                
                // Get the app name
                let appName = 'Hospital Management System';
                try {
                    // Try to get the app name from the page if available
                    const appNameElement = document.querySelector('meta[name="app-name"]');
                    if (appNameElement && appNameElement.getAttribute('content')) {
                        appName = appNameElement.getAttribute('content');
                    }
                } catch (e) {
                    console.warn('Could not get app name:', e);
                }
                
                // Create the print content
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Medicine Transfer Report</title>
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
                            <h2>Medicine Transfer Report</h2>
                            <p>${dateRange ? 'Period: ' + dateRange : ''}</p>
                            <p>Generated on: ${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div id="print-content">
                            ${tableFound ? tableHTML : `<p>No transfer records found</p>`}
                        </div>
                        
                        <div class="print-footer">
                            <p>&copy; {{ date('Y') }} All Rights Reserved</p>
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
                console.error('Error generating print view:', error);
                alert('Error generating print view. Please try again.');
                printWindow.close();
            }
        });
    });
</script>
@endsection
