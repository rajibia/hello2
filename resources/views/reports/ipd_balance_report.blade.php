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
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="mb-0">IPD Balance Report</h1>
        <div class="d-flex align-items-center gap-2">
            <!-- Export Buttons -->
            <div class="btn-group me-2" role="group">
                <button id="exportPdf" class="btn btn-danger btn-sm">
                    PDF
                </button>
                <button id="exportExcel" class="btn btn-success btn-sm">
                    Excel
                </button>
                <button id="exportCsv" class="btn btn-info btn-sm">
                    CSV
                </button>
            </div>

            <button id="printReport" class="btn btn-primary">
                Print
            </button>

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                Back
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
<!-- Libraries -->
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
let ipdTable = null;
let initAttempts = 0;

function initDataTable() {
    const tableEl = document.querySelector('#ipdBalanceWrapper table.table') ||
                    document.querySelector('#ipdBalanceWrapper .table-responsive table') ||
                    document.querySelector('#ipdBalanceWrapper table');

    if (!tableEl) {
        if (initAttempts++ < 20) setTimeout(initDataTable, 400);
        return;
    }

    // Wait for real data
    const rows = tableEl.querySelectorAll('tbody tr');
    if (rows.length === 0 || rows[0].children.length < 4) {
        if (initAttempts++ < 20) setTimeout(initDataTable, 400);
        return;
    }

    // Destroy previous instance
    if ($.fn.DataTable.isDataTable(tableEl)) {
        $(tableEl).DataTable().destroy();
    }

    ipdTable = $(tableEl).DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'IPD Balance Report',
                className: 'btn btn-success btn-sm',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'IPD Balance Report',
                className: 'btn btn-info btn-sm',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'IPD Balance Report',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':not(:last-child)' },
                customize: function (doc) {
                    doc.pageMargins = [20, 20, 20, 20];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.title = { fontSize: 16, bold: true, alignment: 'center' };
                }
            }
        ],
        pageLength: 25,
        order: [[0, 'desc']],
        columnDefs: [
            { targets: '_all', defaultContent: '' },
            { targets: -1, orderable: false, searchable: false, className: 'actions-column' }
        ],
        searching: true,
        paging: true,
        info: true,
        responsive: true,
        destroy: true
    });

    // Connect visible buttons
    $('#exportExcel').off('click').on('click', () => ipdTable.button('.buttons-excel').trigger());
    $('#exportCsv').off('click').on('click', () => ipdTable.button('.buttons-csv').trigger());
    $('#exportPdf').off('click').on('click', () => ipdTable.button('.buttons-pdf').trigger());

    // Live Search
    $('#liveSearch').off('input').on('input', function () {
        ipdTable.search(this.value).draw();
    });

    console.log('IPD Balance DataTable initialized successfully');
}

// Initialize on load & Livewire updates
document.addEventListener('DOMContentLoaded', initDataTable);
document.addEventListener('livewire:update', () => {
    initAttempts = 0;
    if (ipdTable) {
        ipdTable.destroy();
        ipdTable = null;
    }
    setTimeout(initDataTable, 600);
});

// Print Report - Beautiful & Clean
$('#printReport').on('click', function () {
    const dateRange = $('.date-range-display').text().trim() || 'All Time';
    const tableEl = document.querySelector('#ipdBalanceWrapper table') ||
                    document.querySelector('#ipdBalanceWrapper .table-responsive table');

    if (!tableEl) {
        alert('No data to print');
        return;
    }

    const tempTable = tableEl.cloneNode(true);

    // Remove Actions column (last column)
    tempTable.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());

    // Clean icons, buttons, images
    tempTable.querySelectorAll('i, svg, img, button, .btn, .avatar, .badge, .action-btn').forEach(el => el.remove());

    // Convert links to text
    tempTable.querySelectorAll('a').forEach(a => {
        const text = document.createTextNode(a.textContent.trim());
        a.parentNode.replaceChild(text, a);
    });

    const printWin = window.open('', '_blank');
    printWin.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>IPD Balance Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; margin: 0; }
                .header { text-align: center; margin-bottom: 30px; }
                h1 { font-size: 26px; margin: 0; color: #333; }
                h2 { font-size: 20px; margin: 10px 0; color: #555; }
                .info { font-size: 14px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                th, td { border: 1px solid #333; padding: 10px; text-align: left; }
                th { background: #f0f0f0; font-weight: bold; }
                tr:nth-child(even) { background: #f9f9f9; }
                .footer { text-align: center; margin-top: 50px; color: #777; font-size: 12px; }
                @page { margin: 1cm; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>IPD Balance Report</h2>
                <p class="info"><strong>Period:</strong> ${dateRange}</p>
                <p class="info"><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
            </div>

            ${tempTable.outerHTML}

            <div class="footer">
                <p>© {{ date('Y') }} Hospital Management System. All Rights Reserved.</p>
            </div>
        </body>
        </html>
    `);

    printWin.document.close();
    printWin.focus();
    setTimeout(() => printWin.print(), 1000);
});
</script>
@endsection