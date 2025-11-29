@extends('layouts.app')

@section('title', 'OPD Balance Report')

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            .no-print, .btn-group, #liveSearch { display: none !important; }
            .actions-column, .action-btn, i, svg, img, button { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1>OPD Balance Report</h1>
        <div class="d-flex align-items-center gap-2">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>

            <!-- Export Buttons -->
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

    <!-- Live Search Only -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search patients, balance, dates..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="opdBalanceWrapper">
                @livewire('opd-balance-report')
            </div>
        </div>
    </div>

    <!-- Hidden Print Section (Your Original Preserved) -->
    <div id="opdBalancePrintSection" class="d-none">
        <div class="text-center mb-4">
            <h3>{{ getAppName() }}</h3>
            <h4>OPD Balance Report</h4>
            <h5 id="printDateRange"></h5>
        </div>
        <div id="printContent"></div>
        <div class="text-center mt-4 text-muted small">
            <p>{{ getAppName() }} &copy; {{ date('Y') }} All Rights Reserved</p>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <button onclick="window.print();" class="btn btn-primary me-2">Print Now</button>
            <button onclick="window.close();" class="btn btn-secondary">Close</button>
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
    const tableEl = document.querySelector('#opdBalanceWrapper .table-responsive table') ||
                    document.querySelector('#opdBalanceWrapper table');
    if (!tableEl) return;

    if (table) { table.destroy(); table = null; }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'OPD Balance Report',
                fileName: 'opd_balance_report'
            });

            ReportExporter.initializeLiveSearch(table);
        } catch (e) {
            console.warn('DataTable not ready yet:', e);
        }
    }, 500);
}

// Initialize on load & Livewire updates
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Try immediately
initializeDataTable();

// Fallback
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 15 || table) clearInterval(interval);
    initializeDataTable();
}, 600);

// Print functionality
ReportExporter.initializePrint('printReport', '#opdBalanceWrapper table', 'OPD Balance Report');
</script>
@endsection