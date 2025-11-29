@extends('layouts.app')

@section('title', __('Discharge Report'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            @page { margin: 10px; }
            body { padding: 15px !important; font-size: 12px !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 10px; }
            th { background: #f8f9fa; font-weight: bold; }
            .no-print, .btn, .btn-group, .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }
            .badge { background: transparent !important; color: #000 !important; border: 1px solid #ddd; }
            i, svg, img, .avatar, .icon, button { display: none !important; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('Discharge Report') }}</h1>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Report
            </button>

            <!-- Export Buttons (will trigger DataTables export) -->
            <div class="btn-group" role="group">
                <button id="exportPdf" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button id="exportExcel" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="exportCsv" class="btn btn-info btn-sm">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
            </div>

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Optional Live Search -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search patients, doctors, dates instantly..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="dischargeReportWrapper">
                @livewire('discharge-report')
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>

<script>
let table = null;

function initializeDataTable() {
    const tableEl = document.querySelector('#dischargeReportWrapper table.table, #dischargeReportWrapper table');
    if (!tableEl) return;

    if (table) { table.destroy(); table = null; }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'Discharge Report',
                fileName: 'discharge_report'
            });

            ReportExporter.initializeLiveSearch(table);
        } catch (e) {
            console.warn('DataTable not ready yet:', e);
        }
    }, 400);
}

// Try immediately
initializeDataTable();

// Initialize on page load and Livewire updates
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Fallback retry mechanism
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 15 || table !== null) clearInterval(interval);
    initializeDataTable();
}, 600);

// Print functionality
ReportExporter.initializePrint('printReport', '#dischargeReportWrapper table', 'Discharge Report');
</script>
@endsection