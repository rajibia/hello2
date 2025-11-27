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

            <!-- EXPORT BUTTONS (Beautiful & Permanent) -->
            <div class="btn-group me-3">
                <button id="exportExcel" class="btn btn-success" title="Export to Excel">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="exportCsv" class="btn btn-info" title="Export to CSV">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button id="exportPdf" class="btn btn-danger" title="Export to PDF">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>

            <!-- PRINT -->
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- LIVE SEARCH -->
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

<script>
let table = null;

function initializeDataTable() {
    const tableEl = document.querySelector('#pharmacyBillWrapper .table-responsive table') ||
                    document.querySelector('#pharmacyBillWrapper table');

    if (!tableEl) return;

    // Prevent double initialization
    if ($.fn.DataTable.isDataTable(tableEl)) {
        table = $(tableEl).DataTable();
        return;
    }

    if (table) {
        table.destroy();
        table = null;
    }

    setTimeout(() => {
        table = $(tableEl).DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'd-none', // Hidden real button
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    className: 'd-none',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    className: 'd-none',
                    orientation: 'landscape',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function(doc) {
                        // Remove Actions column from PDF
                        doc.content[1].table.body.forEach(row => row.pop());
                    }
                }
            ],
            pageLength: 25,
            order: [[0, 'desc']],
            searching: true,
            destroy: true,
            columnDefs: [
                { targets: '_all', defaultContent: '' },
                { targets: -1, className: 'actions-column' } // Mark last column as actions
            ]
        });

        // Connect your beautiful custom buttons (they work FOREVER)
        $('#exportExcel').off('click').on('click', () => table.buttons(0).trigger());
        $('#exportCsv')  .off('click').on('click', () => table.buttons(1).trigger());
        $('#exportPdf')  .off('click').on('click', () => table.buttons(2).trigger());

        // Live Search
        $('#liveSearch').off('input').on('input', function() {
            table.search(this.value).draw();
        });

    }, 400);
}

// Initialize on page load & every Livewire update
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Fallback retry (just in case)
let initAttempts = 0;
const initInterval = setInterval(() => {
    if (initAttempts++ > 12 || table) clearInterval(initInterval);
    initializeDataTable();
}, 700);

// PRINT REPORT (Perfect & Clean)
$('#printReport').on('click', function() {
    const dateRange = $('.date-range-display')?.text().trim().replace(/\s+/g, ' ') || 'All Time';

    const tableEl = document.querySelector('#pharmacyBillWrapper .table-responsive table') ||
                    document.querySelector('#pharmacyBillWrapper table');
    if (!tableEl) {
        alert('No data available to print');
        return;
    }

    const temp = tableEl.cloneNode(true);

    // Remove Actions column
    temp.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
    temp.querySelectorAll('i, svg, img, button, .btn, .avatar, .action-btn').forEach(el => el.remove());

    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Pharmacy Bill Report</title>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; max-width: 1100px; margin: 0 auto; line-height: 1.5; }
                @page { margin: 15mm; }
                h1, h2 { text-align: center; margin: 10px 0; }
                .header p { text-align: center; margin: 8px 0; color: #555; }
                table { width: 100%; border-collapse: collapse; margin: 25px 0; font-size: 12px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background: #f8f9fa; font-weight: bold; }
                tr:nth-child(even) { background: #f9f9f9; }
                .no-print { text-align: center; margin: 40px 0; }
                .no-print button { padding: 12px 24px; margin: 0 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
                .btn-primary { background: #3699FF; color: white; }
                .btn-secondary { background: #E4E6EF; color: #3F4254; }
                @media print { .no-print { display: none; } }
            </style>
        </head>
        <body>
            <h1>{{ env('APP_NAME') }}</h1>
            <h2>Pharmacy Bill Report</h2>
            <div class="header">
                <p><strong>Period:</strong> ${dateRange}</p>
                <p><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
            </div>

            ${temp.outerHTML || '<p style="text-align:center;">No records found</p>'}

            <div style="text-align:center; color:#777; margin-top:50px; font-size:12px;">
                <p>&copy; {{ date('Y') }} Hospital Management System. All rights reserved.</p>
            </div>

            <div class="no-print">
                <button class="btn-primary" onclick="window.print()">Print Now</button>
                <button class="btn-secondary" onclick="window.close()">Close</button>
            </div>
        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    setTimeout(() => win.print(), 1000);
});
</script>
@endsection