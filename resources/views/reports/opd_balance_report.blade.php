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

<script>
let table = null;

function initializeDataTable() {
    const tableEl = document.querySelector('#opdBalanceWrapper .table-responsive table') ||
                    document.querySelector('#opdBalanceWrapper table');
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
                        exportOptions: { columns: ':not(:last-child)' } // Exclude Actions
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        className: 'buttons-csv',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'buttons-pdf',
                        orientation: 'landscape',
                        exportOptions: { columns: ':not(:last-child)' },
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(row => row.pop()); // Remove last column
                        }
                    }
                ],
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: '_all', defaultContent: '' },
                    { targets: -1, className: 'actions-column' } // Mark Actions column
                ],
                searching: true,
                destroy: true
            });

            // Connect top export buttons
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
    }, 500);
}

// Initialize on load & Livewire updates
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Fallback
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 15 || table) clearInterval(interval);
    initializeDataTable();
}, 600);

// === YOUR ORIGINAL PRINT LOGIC (100% PRESERVED & WORKING) ===
$('#printReport').on('click', function() {
    const dateRange = $('.date-range-display').text().trim().replace(/\s+/g, ' ') || 'All Time';

    const tableEl = document.querySelector('#opdBalanceWrapper .table-responsive table') ||
                    document.querySelector('#opdBalanceWrapper table');
    if (!tableEl) {
        alert('No data to print');
        return;
    }

    const temp = tableEl.cloneNode(true);
    // Remove Actions column
    temp.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
    temp.querySelectorAll('i, svg, img, button, .btn, .avatar').forEach(el => el.remove());

    document.getElementById('printDateRange').textContent = dateRange;
    document.getElementById('printContent').innerHTML = temp.outerHTML;

    window.print();
});
</script>
@endsection