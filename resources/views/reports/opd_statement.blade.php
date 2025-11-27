@extends('layouts.app')

@section('title', __('OPD Statement Report'))

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
            .no-print, .btn, .btn-group { display: none !important; }
            .badge { background: transparent !important; color: #000 !important; }
            i, svg, img, .avatar, .icon { display: none !important; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('OPD Statement Report') }}</h1>
        <div class="d-flex align-items-center gap-2">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Report
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

    <!-- Live Search -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg" 
               placeholder="Search patients, doctors, dates instantly..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="opdReportWrapper">
                @livewire('opd-statement-report')
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
    const tableEl = document.querySelector('#opdReportWrapper table.table-row-dashed');
    if (!tableEl) return;

    // Destroy existing instance
    if (table) {
        table.destroy();
        table = null;
    }

    setTimeout(() => {
        try {
            table = $(tableEl).DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', text: 'Excel', className: 'buttons-excel' },
                    { extend: 'csvHtml5',   text: 'CSV',   className: 'buttons-csv' },
                    { extend: 'pdfHtml5',   text: 'PDF',   className: 'buttons-pdf', orientation: 'landscape' }
                ],
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: '_all', defaultContent: '' } // Prevents "unknown parameter" error
                ],
                searching: true,
                destroy: true
            });

            // Connect export buttons
            $('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());
            $('#exportCsv').off('click').on('click', () => table.buttons('.buttons-csv').trigger());
            $('#exportPdf').off('click').on('click', () => table.buttons('.buttons-pdf').trigger());

            // Live Search
            $('#liveSearch').off('input').on('input', function() {
                table.search(this.value).draw();
            });

        } catch (e) {
            console.warn('DataTable not ready yet:', e);
        }
    }, 400);
}

// Initialize on load + Livewire updates
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Fallback attempts
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 12 || table !== null) clearInterval(interval);
    initializeDataTable();
}, 500);

// Your ORIGINAL beautiful print function (kept 100% intact)
$('#printReport').click(function() {
    let printWindow = window.open('', '_blank');
    let dateRange = $('.date-range-display').text().trim().replace(/\s+/g, ' ') || 'All Time';

    try {
        let tableHTML = '';
        const visibleTable = document.querySelector('table.table-row-dashed');
        
        if (visibleTable) {
            const temp = visibleTable.cloneNode(true);
            temp.querySelectorAll('i, svg, img, .avatar, .icon, button, .btn').forEach(el => el.remove());
            temp.querySelectorAll('.badge').forEach(b => {
                b.style.background = 'none';
                b.style.color = 'inherit';
                b.style.border = '1px solid #ddd';
            });
            tableHTML = temp.outerHTML;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>OPD Statement Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 30px; max-width: 1000px; margin: 0 auto; }
                    .print-header { text-align: center; margin-bottom: 30px; }
                    h1 { font-size: 24px; margin-bottom: 10px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
                    th { background: #f8f9fa; font-weight: bold; }
                    tr:nth-child(even) { background: #f2f2f2; }
                    .badge { background: transparent !important; color: #000 !important; border: 1px solid #ddd; padding: 5px 10px; }
                    @media print { .no-print { display: none !important; } }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>{{ env('APP_NAME') }}</h1>
                    <h2>OPD Statement Report</h2>
                    <p><strong>Period:</strong> ${dateRange}</p>
                    <p><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
                </div>
                ${tableHTML || '<p>No records found</p>'}
                <div style="text-align:center; margin-top:50px; color:#777;">
                    <p>© ${new Date().getFullYear()} Hospital Management System</p>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 1000);

    } catch (e) {
        console.error('Print error:', e);
        alert('Error printing report');
        printWindow.close();
    }
});
</script>
@endsection