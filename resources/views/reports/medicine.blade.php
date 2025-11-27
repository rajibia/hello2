@extends('layouts.app')

@section('title')
    {{ __('Medicine Report') }}
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            @page { margin: 10px; }
            body { padding: 20px !important; font-size: 12px !important; }
            .no-print, .btn-group, #liveSearch,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }

            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 10px; font-size: 12px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            .badge { background: transparent !important; color: #000 !important; border: none !important; }
            i, svg, img, button, .btn, .avatar, .action-btn { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1>{{ __('Medicine Report') }}</h1>
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
                    Print Report
                </button>

                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Live Search -->
        <div class="mb-5">
            <label for="liveSearch" class="form-label fw-bold">Live Search</label>
            <input type="text" id="liveSearch" class="form-control form-control-lg"
                   placeholder="Search medicine name, category, stock, expiry..." autofocus>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @livewire('medicine-report')
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<!-- DataTables + Buttons -->
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
let medicineTable = null;
let initAttempts = 0;

function initDataTable() {
    const tableEl = document.querySelector('#medicineReportTable table') ||
                    document.querySelector('.table-responsive table') ||
                    document.querySelector('table.table');

    if (!tableEl) {
        if (initAttempts++ < 25) setTimeout(initDataTable, 400);
        return;
    }

    // Wait for real rows
    const rows = tableEl.querySelectorAll('tbody tr');
    if (rows.length === 0 || rows[0].children.length < 3) {
        if (initAttempts++ < 25) setTimeout(initDataTable, 400);
        return;
    }

    if ($.fn.DataTable.isDataTable(tableEl)) {
        $(tableEl).DataTable().destroy();
    }

    medicineTable = $(tableEl).DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Medicine Report',
                className: 'btn btn-success btn-sm',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'Medicine Report',
                className: 'btn btn-info btn-sm',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'Medicine Report',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':visible' },
                customize: function(doc) {
                    doc.pageMargins = [20, 20, 20, 20];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.title = { fontSize: 16, bold: true, alignment: 'center', margin: [0, 0, 0, 12] };
                }
            }
        ],
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { targets: '_all', defaultContent: '' }
        ],
        searching: true,
        paging: true,
        info: true,
        responsive: true,
        destroy: true
    });

    // Connect visible buttons
    $('#exportExcel').off('click').on('click', () => medicineTable.button('.buttons-excel').trigger());
    $('#exportCsv').off('click').on('click', () => medicineTable.button('.buttons-csv').trigger());
    $('#exportPdf').off('click').on('click', () => medicineTable.button('.buttons-pdf').trigger());

    // Live Search
    $('#liveSearch').off('input').on('input', function () {
        medicineTable.search(this.value).draw();
    });

    console.log('Medicine Report DataTable + Export buttons initialized');
}

// Initialize on load & Livewire updates
document.addEventListener('DOMContentLoaded', initDataTable);
document.addEventListener('livewire:update', () => {
    initAttempts = 0;
    if (medicineTable) {
        medicineTable.destroy();
        medicineTable = null;
    }
    setTimeout(initDataTable, 700);
});

// Professional Print Report (Same as your original style)
$('#printReport').on('click', function () {
    const dateRange = $('.date-range-display').text().trim().replace(/\s+/g, ' ') || 'All Time';

    const tableEl = document.querySelector('#medicineReportTable table') ||
                    document.querySelector('.table-responsive table') ||
                    document.querySelector('table.table');

    if (!tableEl) {
        alert('No data to print');
        return;
    }

    const tempTable = tableEl.cloneNode(true);

    // Clean up for print
    tempTable.querySelectorAll('i, svg, img, button, .btn, .avatar, .action-btn, .badge').forEach(el => {
        if (el.classList.contains('badge')) {
            el.style.background = 'transparent';
            el.style.color = '#000';
            el.style.border = 'none';
        } else {
            el.remove();
        }
    });

    tempTable.querySelectorAll('a').forEach(a => {
        const text = document.createTextNode(a.textContent);
        a.parentNode.replaceChild(text, a);
    });

    const printWin = window.open('', '_blank');
    printWin.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Medicine Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; margin: 0; background: white; }
                .header { text-align: center; margin-bottom: 30px; }
                h1 { font-size: 26px; margin: 0; color: #333; }
                h2 { font-size: 20px; margin: 10px 0; color: #555; }
                .info { font-size: 14px; color: #666; margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background: #f2f2f2; font-weight: bold; }
                tr:nth-child(even) { background: #f9f9f9; }
                .footer { text-align: center; margin-top: 50px; color: #777; font-size: 12px; }
                .no-print { text-align: center; margin: 30px 0; }
                .btn { padding: 10px 20px; margin: 0 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
                .btn-primary { background: #3699FF; color: white; }
                .btn-secondary { background: #E4E6EF; color: #333; }
                @page { margin: 1cm; }
                @media print { .no-print { display: none; } }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>Medicine Report</h2>
                <p class="info"><strong>Period:</strong> ${dateRange}</p>
                <p class="info"><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
            </div>

            ${tempTable.outerHTML}

            <div class="footer">
                <p>&copy; {{ date('Y') }} Hospital Management System. All Rights Reserved.</p>
            </div>

            <div class="no-print">
                <button class="btn btn-primary" onclick="window.print()">Print Now</button>
                <button class="btn btn-secondary" onclick="window.close()">Close</button>
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