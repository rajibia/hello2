@extends('layouts.app')

@section('title', __('Patient Statement Report'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            .no-print, .btn-group, #liveSearch { display: none !important; }
            .actions-column { display: none !important; }
        }
        /* Hide Actions column in exports too */
        .dt-button { background: transparent !important; }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('Patient Statement Report') }}</h1>
        <div class="d-flex align-items-center gap-2">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>

            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="mb-5">
        <input type="text" id="liveSearch" class="form-control form-control-lg" 
               placeholder="Search services, dates, amounts..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="patientStatementWrapper">
                @livewire('patient-statement-report')
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
    const tableEl = document.querySelector('#patientStatementWrapper .table-responsive table') ||
                    document.querySelector('#patientStatementWrapper table');
    if (!tableEl) return;

    if (table) { table.destroy(); table = null; }

    setTimeout(() => {
        try {
            table = $(tableEl).DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'buttons-excel',
                        exportOptions: { columns: ':not(.actions-column)' } // EXCLUDE ACTIONS
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        className: 'buttons-csv',
                        exportOptions: { columns: ':not(.actions-column)' }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'buttons-pdf',
                        orientation: 'landscape',
                        exportOptions: { columns: ':not(.actions-column)' },
                        customize: function(doc) {
                            // Remove actions column header & data in PDF
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            let colCount = doc.content[1].table.body[0].length;
                            for (let i = 0; i < doc.content[1].table.body.length; i++) {
                                doc.content[1].table.body[i].splice(-1, 1); // Remove last column
                            }
                        }
                    }
                ],
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: '_all', defaultContent: '' },
                    { targets: 'actions-column', className: 'actions-column', visible: true } // Visible on screen only
                ],
                searching: true,
                destroy: true
            });

            // Connect top buttons
            $('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());
            $('#exportCsv').off('click').on('click', () => table.buttons('.buttons-csv').trigger());
            $('#exportPdf').off('click').on('click', () => table.buttons('.buttons-pdf').trigger());

            // Live Search
            $('#liveSearch').off('input').on('input', function() {
                table.search(this.value).draw();
            });

        } catch (e) {
            console.warn('DataTable not ready:', e);
        }
    }, 500);
}

// Initialize
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// === PRINT: Actions column removed ===
$('#printReport').click(function() {
    const printWin = window.open('', '_blank');
    const dateRange = $('.date-range-display').text().trim().replace(/\s+/g, ' ') || 'All Time';

    let patientInfo = '';
    const nameEl = document.querySelector('.patient-info h3');
    const details = document.querySelectorAll('.patient-info p');
    if (nameEl) {
        patientInfo += `<h3>${nameEl.textContent}</h3>`;
        details.forEach(p => patientInfo += `<p>${p.textContent}</p>`);
    }

    let tableHTML = '';
    const tableEl = document.querySelector('#patientStatementWrapper .table-responsive table') ||
                    document.querySelector('#patientStatementWrapper table');
    if (tableEl) {
        const temp = tableEl.cloneNode(true);
        // Remove Actions column (last column)
        temp.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
        temp.querySelectorAll('i, svg, img, button, .btn').forEach(el => el.remove());
        tableHTML = temp.outerHTML;
    }

    printWin.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Patient Statement Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; max-width: 1000px; margin: 0 auto; }
                .header { text-align: center; margin-bottom: 30px; }
                h1 { font-size: 24px; margin: 5px 0; }
                .patient-info { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 10px; font-size: 12px; text-align: left; }
                th { background: #f2f2f2; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>Patient Statement Report</h2>
                <p><strong>Period:</strong> ${dateRange}</p>
                <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
            </div>
            <div class="patient-info">${patientInfo || '<p>No patient info</p>'}</div>
            ${tableHTML || '<p>No records found</p>'}
            <div style="text-align:center; margin-top:50px; color:#777; font-size:12px;">
                <p>&copy; ${new Date().getFullYear()} Hospital Management System</p>
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