@extends('layouts.app')
@section('title')
    {{ __('Company Claim Report') }}
@endsection
@section('content')
@include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Company Claim Report') }}</h1>
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
                        <div class="card">
                            <div class="card-body pt-5 fs-6 py-8 px-8 px-lg-10 text-gray-700">
                                <div class="table-responsive" id="companyClaimReportTable">
                                    <table class="table table-striped border-bottom-2">
                                        <thead>
                                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                <th>{{ __('Company Name') }}</th>
                                                <th>{{ __('Code') }}</th>
                                                <th>{{ __('Patients Count') }}</th>
                                                <th class="text-end">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($companies as $company)
                                                <tr>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company->code }}</td>
                                                    <td>{{ $company->patients_count }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('reports.company-claim.detail', $company->id) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-file-invoice"></i> {{ __('Claim Report') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">{{ __('No companies found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end pt-5">
                                    {{ $companies->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printReport').click(function() {
                // Create a new window for printing
                let printWindow = window.open('', '_blank');
                
                // Get the table HTML
                let tableHTML = '';
                const visibleTable = document.querySelector('#companyClaimReportTable table');
                if (visibleTable) {
                    tableHTML = visibleTable.outerHTML;
                    
                    // Create a temporary div to manipulate the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = tableHTML;
                    
                    // Remove all icons, images, and unnecessary elements
                    const icons = tempDiv.querySelectorAll('i, svg, img, .avatar-circle, .avatar, .icon');
                    icons.forEach(icon => icon.remove());
                    
                    // Remove any action buttons or links that shouldn't be printed
                    const actionButtons = tempDiv.querySelectorAll('.btn');
                    actionButtons.forEach(btn => {
                        // Replace buttons with text that says "Claim Report"
                        if (btn.textContent.includes('Claim Report')) {
                            const td = btn.closest('td');
                            if (td) {
                                td.textContent = 'Claim Report';
                            }
                        } else {
                            btn.remove();
                        }
                    });
                    
                    // Get the simplified table HTML
                    tableHTML = tempDiv.innerHTML;
                }
                
                // Create the print content
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Company Claim Report</title>
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
                            <h2>Company Claim Report</h2>
                            <p>Generated on: ${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div id="print-content">
                            ${tableHTML || `<p>No company data available</p>`}
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
            });
        });
    </script>
@endsection
