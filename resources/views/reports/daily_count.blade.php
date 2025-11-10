@extends('layouts.app')
@section('title')
    {{ __('Daily OPD & IPD Count') }}
@endsection
@section('page_css')
    <!-- No additional CSS needed -->
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Daily OPD & IPD Count') }}</h1>
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
                        <livewire:daily-count-report />
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
                
                // Get the date range and statistics data
                // Use a more specific selector to get only the date range text
                let startDate = $('.card-header .text-muted.mb-0').first().text().split(' - ')[0] || '';
                let endDate = $('.card-header .text-muted.mb-0').first().text().split(' - ')[1] || '';
                let dateRange = startDate && endDate ? `${startDate} - ${endDate}` : 'Custom Range';
                
                // Extract the statistics data
                let opdTotal = $('.text-info.fw-bolder.fs-1').first().text() || '0';
                let ipdTotal = $('.text-primary.fw-bolder.fs-1').first().text() || '0';
                
                let opdNew = $('.text-success.fw-bolder.fs-2').first().text() || '0';
                let opdOld = $('.text-info.fw-bolder.fs-2').first().text() || '0';
                let opdMale = $('.text-warning.fw-bolder.fs-2').first().text() || '0';
                let opdFemale = $('.text-danger.fw-bolder.fs-2').first().text() || '0';
                
                let ipdNew = $('.text-success.fw-bolder.fs-2').last().text() || '0';
                let ipdOld = $('.text-info.fw-bolder.fs-2').last().text() || '0';
                let ipdMale = $('.text-warning.fw-bolder.fs-2').last().text() || '0';
                let ipdFemale = $('.text-danger.fw-bolder.fs-2').last().text() || '0';
                
                // Create a clean print document without using the original HTML
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Daily OPD & IPD Count Report</title>
                        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
                        <style>
                            body { 
                                font-family: Arial, sans-serif; 
                                padding: 20px; 
                                max-width: 1000px; 
                                margin: 0 auto; /* Center the content */
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
                            .print-footer { 
                                text-align: center; 
                                margin-top: 30px; 
                                font-size: 12px; 
                                color: #777; 
                                padding-bottom: 20px;
                            }
                            
                            .report-container {
                                max-width: 900px;
                                margin: 0 auto;
                            }
                            
                            .stats-card { 
                                border: 1px solid #ddd; 
                                border-radius: 5px; 
                                margin-bottom: 20px; 
                                padding: 15px; 
                                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                            }
                            .stats-header { 
                                background-color: #f8f9fa; 
                                padding: 10px; 
                                margin: -15px -15px 15px; 
                                border-bottom: 1px solid #ddd; 
                                border-radius: 5px 5px 0 0;
                            }
                            .stats-header h3 { 
                                margin: 0; 
                                font-size: 18px; 
                                font-weight: bold; 
                                color: #333;
                            }
                            
                            .stat-row { 
                                display: flex; 
                                margin-bottom: 15px; 
                            }
                            .stat-item { 
                                flex: 1; 
                                padding: 10px; 
                                text-align: center;
                            }
                            .stat-item.total { 
                                background-color: #f8f9fa; 
                                border-radius: 5px; 
                                padding: 15px; 
                                margin-bottom: 15px; 
                                text-align: center;
                            }
                            .stat-label { 
                                font-size: 14px; 
                                color: #555; 
                                margin-bottom: 5px; 
                                font-weight: 500;
                            }
                            .stat-value { 
                                font-size: 24px; 
                                font-weight: bold; 
                                color: #333; 
                            }
                            .stat-value.primary { color: #3699FF; }
                            .stat-value.success { color: #1BC5BD; }
                            .stat-value.info { color: #8950FC; }
                            .stat-value.warning { color: #FFA800; }
                            .stat-value.danger { color: #F64E60; }
                            
                            .row { 
                                display: flex; 
                                flex-wrap: wrap; 
                                margin: 0 -15px; 
                                justify-content: center; /* Center the row content */
                            }
                            .col-6 { 
                                width: 50%; 
                                padding: 0 15px; 
                            }
                            
                            @media print {
                                .no-print { display: none !important; }
                                body { 
                                    padding: 0; 
                                    margin: 0 auto;
                                    width: 100%;
                                }
                                .stats-card { 
                                    break-inside: avoid; 
                                    box-shadow: none;
                                    border: 1px solid #ddd;
                                }
                                .row {
                                    display: flex;
                                    justify-content: center;
                                }
                            }
                            
                            @media (max-width: 768px) {
                                .col-6 {
                                    width: 100%;
                                }
                                .row {
                                    flex-direction: column;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-header">
                            <h1>{{env('APP_NAME')}}</h1>
                            <h2>Daily OPD & IPD Count Report</h2>
                            <p>Period: ${dateRange}</p>
                            <p>Generated on: ${new Date().toLocaleString()}</p>
                        </div>
                        
                        <div class="report-container">
                            <div class="row">
                            <!-- OPD Statistics (Left Column) -->
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-header">
                                        <h3>OPD Statistics</h3>
                                    </div>
                                    
                                    <div class="stat-item total">
                                        <div class="stat-label">Total Patients</div>
                                        <div class="stat-value primary">${opdTotal}</div>
                                    </div>
                                    
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <div class="stat-label">New</div>
                                            <div class="stat-value success">${opdNew}</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Old</div>
                                            <div class="stat-value info">${opdOld}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <div class="stat-label">Male</div>
                                            <div class="stat-value warning">${opdMale}</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Female</div>
                                            <div class="stat-value danger">${opdFemale}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- IPD Statistics (Right Column) -->
                            <div class="col-6">
                                <div class="stats-card">
                                    <div class="stats-header">
                                        <h3>IPD Statistics</h3>
                                    </div>
                                    
                                    <div class="stat-item total">
                                        <div class="stat-label">Total Patients</div>
                                        <div class="stat-value primary">${ipdTotal}</div>
                                    </div>
                                    
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <div class="stat-label">New</div>
                                            <div class="stat-value success">${ipdNew}</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Old</div>
                                            <div class="stat-value info">${ipdOld}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <div class="stat-label">Male</div>
                                            <div class="stat-value warning">${ipdMale}</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Female</div>
                                            <div class="stat-value danger">${ipdFemale}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                
                printWindow.document.close();
                printWindow.focus();
                
                // Add a slight delay to allow styles to load
                setTimeout(function() {
                    printWindow.print();
                }, 500);
            });
        });
    </script>
@endsection
