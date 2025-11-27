@extends('layouts.app')

@section('title', __('Transaction Report'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        /* Optional: hide internal DataTables buttons but keep them active */
        .dt-buttons {
            position: absolute;
            left: -9999px;
        }

        @media print {
            .no-print, .btn-group, #liveSearch, .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }
            body { padding: 20px; font-size: 12px; }
            .actions-column { display: none !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; }
            th { background: #f5f8fa; font-weight: bold; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('Transaction Report') }}</h1>
        <div class="d-flex align-items-center gap-2 no-print">
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
    <div class="mb-5 no-print">
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search transactions, patients, amounts..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="transactionReportWrapper">
                @livewire('transaction-report')
            </div>
        </div>
    </div>

    <!-- Hidden Print Section -->
    <div id="transactionReportPrintSection" style="display: none;">
        <div class="text-center mb-4">
            <h1>{{ env('APP_NAME') }}</h1>
            <h2>Transaction Report</h2>
            <p class="fw-bold date-range-print">Period: All Time</p>
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        </div>
        <table class="table table-bordered" style="font-size: 12px; width: 100%;">
            <thead style="background: #f5f8fa;">
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Patient/User</th>
                    <th>Type</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="printTableBody"></tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-end">Total Amount:</th>
                    <th id="printTotalAmount" class="fw-bold">0.00</th>
                </tr>
            </tfoot>
        </table>
        <div class="text-center text-muted small mt-5">
            <p>© {{ date('Y') }} Hospital Management System</p>
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
let transactionTable = null;

function initDataTable() {
    const tableEl =
        document.querySelector('#transactionReportWrapper table.table-row-dashed') ||
        document.querySelector('#transactionReportWrapper table');

    if (!tableEl) {
        // Livewire might not have rendered yet
        setTimeout(initDataTable, 300);
        return;
    }

    // Wait until table has body rows
    if (!tableEl.querySelector('tbody tr')) {
        setTimeout(initDataTable, 300);
        return;
    }

    // Destroy old instance if any
    if ($.fn.DataTable.isDataTable(tableEl)) {
        $(tableEl).DataTable().destroy();
    }

    transactionTable = $(tableEl).DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Transaction Report',
                className: 'btn btn-success btn-sm buttons-excel',
                exportOptions: { columns: [0,1,2,3,4,5,6] }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'Transaction Report',
                className: 'btn btn-info btn-sm buttons-csv',
                exportOptions: { columns: [0,1,2,3,4,5,6] }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'Transaction Report',
                className: 'btn btn-danger btn-sm buttons-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0,1,2,3,4,5,6] },
                customize: function (doc) {
                    doc.pageMargins = [20, 20, 20, 20];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc.styles.title = { fontSize: 16, bold: true, alignment: 'center' };
                }
            }
        ],
        pageLength: 25,
        order: [[1, 'desc']],
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

    // External buttons -> DataTables buttons (use API, no wrapper issues)
    $('#exportExcel').off('click').on('click', function () {
        if (transactionTable) {
            transactionTable.button('.buttons-excel').trigger();
        }
    });

    $('#exportCsv').off('click').on('click', function () {
        if (transactionTable) {
            transactionTable.button('.buttons-csv').trigger();
        }
    });

    $('#exportPdf').off('click').on('click', function () {
        if (transactionTable) {
            transactionTable.button('.buttons-pdf').trigger();
        }
    });

    // Live Search
    $('#liveSearch').off('input').on('input', function () {
        if (transactionTable) {
            transactionTable.search(this.value).draw();
        }
    });

    console.log('DataTable + Export buttons initialized successfully');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initDataTable();
});

// Re-init after every Livewire DOM update
document.addEventListener('livewire:update', () => {
    initDataTable();
});

// Print Report
$('#printReport').on('click', function () {
    const dateRange = $('.date-range-display').text().trim() || 'All Time';
    const totalAmount = $('.card-header .badge-success, .text-success.fw-bold').first().text().trim() || '0.00';

    const $print = $('#transactionReportPrintSection');
    $print.find('.date-range-print').text('Period: ' + dateRange);
    $print.find('#printTotalAmount').text(totalAmount);

    const $body = $print.find('#printTableBody').empty();
    $('#transactionReportWrapper table tbody tr').each(function () {
        const $cells = $(this).find('td').not(':last-child');
        const $row = $('<tr></tr>');
        $cells.each(function () {
            const $cell = $(this).clone();
            $cell.find('i, svg, img, button, .btn, .avatar, .badge').remove();
            $row.append('<td>' + $cell.text().trim() + '</td>');
        });
        $body.append($row);
    });

    window.print();
});

// Support Livewire emit
window.addEventListener('print-transaction-report', () => $('#printReport').click());
</script>
@endsection
