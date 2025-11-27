@extends('layouts.app')

@section('title', __('Monthly Outpatient Morbidity Returns'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            .no-print, .btn, .btn-group, .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }
            body { padding: 20px; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; font-size: 11px; }
            th { background: #f5f5f5; font-weight: bold; }
            .badge, .fas, .far, .fa, img, button, .svg-icon { display: none !important; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1 class="mb-0">{{ __('Monthly Outpatient Morbidity Returns') }}</h1>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary me-2" id="printReport">
                Print Report
            </button>

<!-- EXPORT BUTTONS -->
<button id="exportExcel" class="btn btn-success me-2">
    Export Excel
</button>

<button id="exportCsv" class="btn btn-info me-2">
    Export CSV
</button>

<button id="exportPdf" class="btn btn-danger me-2">
    Export PDF
</button>

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                Back
            </a>
        </div>
    </div>

    <div class="mb-5">
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search diseases, cases, age groups instantly..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="morbidityReportWrapper">
                @livewire('monthly-outpatient-morbidity-report')
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<!-- Core Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>

<script>
let morbidityTable = null;
let initAttempts = 0;
const maxAttempts = 20;

function safelyInitializeDataTable() {
    // Find the table
    const tableEl = document.querySelector('#morbidityReportWrapper table.table-row-dashed') ||
                    document.querySelector('#morbidityReportWrapper table');

    if (!tableEl) {
        if (initAttempts++ < maxAttempts) {
            setTimeout(safelyInitializeDataTable, 400);
        }
        return;
    }

    // Critical: Wait for tbody to have rows AND match thead columns
    const theadColumns = tableEl.querySelectorAll('thead th').length;
    const tbodyRows = tableEl.querySelectorAll('tbody tr');

    if (tbodyRows.length === 0 || tbodyRows[0].children.length < theadColumns - 2) {
        if (initAttempts++ < maxAttempts) {
            setTimeout(safelyInitializeDataTable, 400);
            return;
        }
    }

    // Destroy if already initialized
    if ($.fn.DataTable.isDataTable(tableEl)) {
        $(tableEl).DataTable().destroy();
    }

    // Safe initialization
    morbidityTable = $(tableEl).DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                className: 'btn btn-info btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Monthly Outpatient Morbidity Returns'
            }
        ],
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100, 'All'],
        order: [[0, 'asc']],
        searching: true,
        paging: true,
        info: true,
        autoWidth: false,
        responsive: true,
        // This is the KEY fix — prevents "unknown parameter" error
        columnDefs: [
            { targets: '_all', defaultContent: '' }
        ],
        // Extra safety
        deferRender: true,
        destroy: true
    });

    // Connect export buttons
    $('#exportExcel').off('click').on('click', () => morbidityTable.button('.buttons-excel').trigger());
    $('#exportCsv').off('click').on('click', () => morbidityTable.button('.buttons-csv').trigger());
    $('#exportPdf').off('click').on('click', () => morbidityTable.button('.buttons-pdf').trigger());

    // Live search
    $('#liveSearch').off('input').on('input', function() {
        morbidityTable.search(this.value).draw();
    });

    console.log('DataTable initialized successfully');
}

// Re-init on every Livewire update
document.addEventListener('DOMContentLoaded', safelyInitializeDataTable);
document.addEventListener('livewire:update', () => {
    initAttempts = 0;
    if (morbidityTable) {
        morbidityTable.destroy();
        morbidityTable = null;
    }
    setTimeout(safelyInitializeDataTable, 300);
});

// Print Report (preserved from your working version)
document.getElementById('printReport')?.addEventListener('click', () => {
    window.livewire.emit('printReport');
});

document.addEventListener('livewire:load', function () {
    window.livewire.on('print-morbidity-report', () => {
        const printSection = document.getElementById('monthlyMorbidityPrintSection');
        const dateRange = $('.date-range-display').text().trim() || 'Current Month';
        const win = window.open('', '_blank');

        win.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Monthly Outpatient Morbidity Returns</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 30px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    h1 { font-size: 24px; margin: 0; }
                    table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                    th { background: #f0f0f0; }
                    @page { margin: 1cm; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>{{ env('APP_NAME') }}</h1>
                    <h2>Monthly Outpatient Morbidity Returns</h2>
                    <p><strong>Period:</strong> ${dateRange}</p>
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                </div>
                ${printSection ? printSection.innerHTML : '<p>No data available</p>'}
            </body>
            </html>
        `);
        win.document.close();
        setTimeout(() => win.print(), 800);
    });
});
</script>
@endsection