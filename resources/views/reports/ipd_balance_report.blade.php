{{-- resources/views/reports/ipd-balance.blade.php --}}
@extends('layouts.app')

@section('title', 'IPD Balance Report')

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            .no-print, .btn-group, #liveSearch,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }
            body { padding: 30px; font-size: 12px; }
            .actions-column, i, svg, img, button, .btn, .avatar, .badge { display: none !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #333; padding: 10px; text-align: left; }
            th { background: #f0f0f0; font-weight: bold; }
            tr:nth-child(even) { background: #f9f9f9; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <h1 class="mb-0">IPD Balance Report</h1>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>

            <!-- Your existing buttons — now fully functional -->
            <div class="btn-group me-2" role="group">
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

    <!-- Live Search -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search patients, IPD number, balance..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="ipdBalanceWrapper">
                @livewire('ipd-balance-report')
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
let initAttempts = 0;

function initializeDataTable() {
    const wrapper = document.getElementById('ipdBalanceWrapper');
    if (!wrapper) return;

    const tableEl = wrapper.querySelector('table');
    if (!tableEl) {
        if (initAttempts++ < 30) setTimeout(initializeDataTable, 250);
        return;
    }

    initAttempts = 0;

    if (table) {
        try { table.destroy(); } catch (e) {}
        table = null;
    }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'IPD Balance Report',
                fileName: 'ipd_balance_report'
            });
            ReportExporter.initializeLiveSearch(table);
        } catch (e) {
            console.warn('Error initializing exports:', e);
            if (initAttempts++ < 10) setTimeout(initializeDataTable, 500);
        }
    }, 150);
}

// Try immediately
initializeDataTable();

// Critical events — these make it work every time
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:update',   initializeDataTable);
document.addEventListener('livewire:updated',  initializeDataTable);

// Safety net
setInterval(() => {
    if (!table && document.querySelector('#ipdBalanceWrapper table')) {
        initializeDataTable();
    }
}, 1000);

ReportExporter.initializePrint('printReport', '#ipdBalanceWrapper table', 'IPD Balance Report');
</script>
@endsection