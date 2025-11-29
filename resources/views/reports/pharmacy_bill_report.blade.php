@extends('layouts.app')

@section('title', 'Pharmacy Bill Report')

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            .no-print, .btn-group, #liveSearch { display: none !important; }
            .actions-column, i, svg, img, button, .btn, .avatar { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1>Pharmacy Bill Report</h1>
        <div class="d-flex align-items-center gap-2">

       

            <!-- PRINT -->
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>

            <!-- Export Buttons -->
            <div class="btn-group ms-2" role="group">
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

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary ms-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>


    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search bill number, patient, amount..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="pharmacyBillWrapper">
                @livewire('pharmacy-bill-report')
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
    const tableEl = document.querySelector('#pharmacyBillWrapper .table-responsive table') ||
                    document.querySelector('#pharmacyBillWrapper table');

    if (!tableEl) return;

    if ($.fn.DataTable.isDataTable(tableEl)) {
        table = $(tableEl).DataTable();
        return;
    }

    if (table) {
        try { table.destroy(); } catch (e) {}
        table = null;
    }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'Pharmacy Bill Report',
                fileName: 'pharmacy_bill_report'
            });
            ReportExporter.initializeLiveSearch(table);
            // debug
            try {
                console.info('Pharmacy Bill: ReportExporter initialized');
                const btns = { excel: !!table.button('.buttons-excel').length, csv: !!table.button('.buttons-csv').length, pdf: !!table.button('.buttons-pdf').length };
                console.info('Pharmacy Bill DT Buttons:', btns);
            } catch (err) { console.warn('Pharmacy Bill post-init check failed', err); }
        } catch (e) {
            console.warn('Error initializing exports for Pharmacy Bill:', e);
        }
    }, 300);
}

document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Try immediately
initializeDataTable();

let initAttempts = 0;
const initInterval = setInterval(() => {
    if (initAttempts++ > 12 || table) clearInterval(initInterval);
    initializeDataTable();
}, 700);

ReportExporter.initializePrint('printReport', '#pharmacyBillWrapper table', 'Pharmacy Bill Report');
</script>
@endsection