@extends('layouts.app')
@section('title')
    {{ __('Inventory Stock Report') }}
@endsection

@include('reports.partials._report-scripts')
@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">
    
    <style>
        @media print {
            @page {
                margin: 10px;
            }
            body {
                padding: 10px !important;
                font-size: 12px !important;
            }
            .no-print, .btn, .btn-group, #exportPdf, #exportExcel, #exportCsv, #printReport,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { 
                display: none !important; 
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table th, table td {
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 12px;
            }
            table th {
                background-color: #f2f2f2;
                text-align: left;
            }
            .badge {
                border: none !important;
                background-color: transparent !important;
                color: #000 !important;
            }
            i, svg, img, .avatar {
                display: none !important;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1>{{ __('Inventory Stock Report') }}</h1>
                <div class="d-flex align-items-center">
                    <div class="btn-group me-2" role="group">
                        <button id="exportPdf" class="btn btn-danger btn-sm"> <i class="fas fa-file-pdf"></i> PDF</button>
                        <button id="exportExcel" class="btn btn-success btn-sm"> <i class="fas fa-file-excel"></i> Excel</button>
                        <button id="exportCsv" class="btn btn-info btn-sm"> <i class="fas fa-file-csv"></i> CSV</button>
                    </div>

                    <button id="printReport" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> {{ __('Print Report') }}
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                    </a>
                </div>
            </div>
            
            <div id="stockReportWrapper" class="card shadow-sm">
                <div class="card-body">
                    @livewire('stock-report')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>

<script>
const initStockReportExports = function () {
    ReportExporter.initializeOnWrapper('#stockReportWrapper', {
        excludeColumns: [':last-child'],
        reportTitle: 'Stock Report',
        fileName: 'stock_report'
    });

    ReportExporter.initializePrint('printReport', '#stockReportWrapper table', 'Stock Report');
};

// Try immediately
initStockReportExports();

// Also on DOMContentLoaded
document.addEventListener('DOMContentLoaded', initStockReportExports);

// Re-init on Livewire events
window.addEventListener('livewire:load', initStockReportExports);
window.addEventListener('livewire:update', initStockReportExports);
window.addEventListener('livewire:updated', initStockReportExports);
</script>
@endsection
