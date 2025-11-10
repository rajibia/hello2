@extends('layouts.app')
@section('title')
    {{ __('Expenses Report') }}
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
                <h1>{{ __('Expenses Report') }}</h1>
                <div class="d-flex align-items-center">
                    <button id="printReport" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> {{ __('Print Report') }}
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                    </a>
                </div>
            </div>
            
            @livewire('expenses-report')
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print functionality
        document.getElementById('printReport').addEventListener('click', function() {
            // Create a new window for printing
            let printWindow = window.open('', '_blank');
            
            // Get the print section content
            let printContent = document.getElementById('expensesPrintSection').innerHTML;
            
            // Add hospital name and address from the main layout if available
            let hospitalName = '{{ getSettingValue('hospital_name') }}';
            let hospitalAddress = '{{ getSettingValue('hospital_address') }}';
            
            // Create the print document
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Expenses Report</title>
                    <style>
                        @page {
                            margin: 10px;
                        }
                        body {
                            padding: 10px !important;
                            font-family: Arial, sans-serif;
                            font-size: 12px;
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
                        .hospital-info {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .hospital-info h2 {
                            margin-bottom: 5px;
                        }
                        .hospital-info p {
                            margin-top: 0;
                        }
                        .no-print {
                            display: none !important;
                        }
                        .text-end {
                            text-align: right;
                        }
                        .btn {
                            display: inline-block;
                            padding: 6px 12px;
                            margin-bottom: 0;
                            font-size: 14px;
                            font-weight: 400;
                            line-height: 1.42857143;
                            text-align: center;
                            white-space: nowrap;
                            vertical-align: middle;
                            cursor: pointer;
                            border: 1px solid transparent;
                            border-radius: 4px;
                            text-decoration: none;
                        }
                        .btn-primary {
                            color: #fff;
                            background-color: #337ab7;
                            border-color: #2e6da4;
                        }
                        .btn-secondary {
                            color: #fff;
                            background-color: #6c757d;
                            border-color: #6c757d;
                        }
                        .me-2 {
                            margin-right: 0.5rem;
                        }
                        @media print {
                            .no-print {
                                display: none !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="hospital-info">
                        <h2>${hospitalName}</h2>
                        <p>${hospitalAddress}</p>
                    </div>
                    ${printContent}
                    <div class="text-center mt-4">
                        <p>&copy; ${new Date().getFullYear()} ${hospitalName}. All rights reserved.</p>
                    </div>
                </body>
                </html>
            `);
            
            // Wait for content to load then print
            printWindow.document.close();
            printWindow.focus();
            
            // Add a slight delay to ensure content is fully loaded
            setTimeout(function() {
                printWindow.print();
            }, 500);
        });
    });
</script>
@endsection
