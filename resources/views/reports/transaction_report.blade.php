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

<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>
<script>
let transactionTable = null;
let initAttempts = 0;

function initDataTable() {
    const tableEl =
        document.querySelector('#transactionReportWrapper table.table-row-dashed') ||
        document.querySelector('#transactionReportWrapper table');

    if (!tableEl) {
        // Livewire might not have rendered yet
        if (initAttempts++ < 20) setTimeout(initDataTable, 300);
        return;
    }

    if (transactionTable) {
        try { transactionTable.destroy(); } catch (e) {}
        transactionTable = null;
    }

    setTimeout(() => {
        try {
            transactionTable = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'Transaction Report',
                fileName: 'transaction_report'
            });

            ReportExporter.initializeLiveSearch(transactionTable);
            console.log('ReportExporter initialized for Transaction Report');
        } catch (e) {
            console.warn('Failed to init ReportExporter for transactions:', e);
            if (initAttempts++ < 10) setTimeout(initDataTable, 500);
        }
    }, 200);
}

// Try immediately
initDataTable();

// Initialize on page load and Livewire updates
document.addEventListener('DOMContentLoaded', initDataTable);
document.addEventListener('livewire:load', initDataTable);
document.addEventListener('livewire:update', initDataTable);
document.addEventListener('livewire:updated', initDataTable);

// Print Report (keep existing specialized print behavior)
$('#printReport').off('click').on('click', function () {
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
