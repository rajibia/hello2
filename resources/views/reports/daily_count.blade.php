{{-- resources/views/reports/daily-count.blade.php --}}
@extends('layouts.app')

@section('title', __('Daily OPD & IPD Count'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

    <style>
        @media print {
            @page { margin: 15px; }
            body { padding: 30px !important; font-size: 13px !important; }
            .no-print, .btn, .btn-group, .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate { display: none !important; }
            .card { box-shadow: none; border: 1px solid #ddd; }
            .stats-card { background: #fff !important; }
            .total-count { background: #e3f2fd !important; color: #1976d2 !important; }
            h1, h2, h3 { color: #333 !important; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('Daily OPD & IPD Count') }}</h1>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Report
            </button>

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

    <div class="row">
        <div class="col-12">
            <livewire:daily-count-report />
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
    // Build hidden table for export/print consistency
    let tableHTML = `
        <table class="table table-bordered table-striped" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                    <th>New</th>
                    <th>Old</th>
                    <th>Male</th>
                    <th>Female</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>OPD Patients</strong></td>
                    <td>${$('.text-info.fw-bolder.fs-1').text().trim() || '0'}</td>
                    <td>${$('.text-success.fw-bolder.fs-2').first().text().trim() || '0'}</td>
                    <td>${$('.text-info.fw-bolder.fs-2').eq(1).text().trim() || '0'}</td>
                    <td>${$('.text-warning.fw-bolder.fs-2').first().text().trim() || '0'}</td>
                    <td>${$('.text-danger.fw-bolder.fs-2').first().text().trim() || '0'}</td>
                </tr>
                <tr>
                    <td><strong>IPD Patients</strong></td>
                    <td>${$('.text-primary.fw-bolder.fs-1').text().trim() || '0'}</td>
                    <td>${$('.text-success.fw-bolder.fs-2').eq(1).text().trim() || '0'}</td>
                    <td>${$('.text-info.fw-bolder.fs-2').eq(2).text().trim() || '0'}</td>
                    <td>${$('.text-warning.fw-bolder.fs-2').eq(1).text().trim() || '0'}</td>
                    <td>${$('.text-danger.fw-bolder.fs-2').eq(1).text().trim() || '0'}</td>
                </tr>
            </tbody>
        </table>`;

    // Inject hidden table if not exists
    if (!$('#exportTable').length) {
        $('body').append(`<div id="exportTable" style="display:none">${tableHTML}</div>`);
    } else {
        $('#exportTable').html(tableHTML);
    }

    const tableEl = $('#exportTable table')[0];
    if (!tableEl) return;

    if (table) { table.destroy(); table = null; }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [],
                reportTitle: 'Daily OPD & IPD Count Report',
                fileName: 'daily_opd_ipd_count'
            });
        } catch (e) {
            console.warn('DataTable init failed:', e);
        }
    }, 300);
}

// Try immediately
initializeDataTable();

// Re-init on Livewire update
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);

// Fallback retry
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 10 || table) clearInterval(interval);
    initializeDataTable();
}, 800);

// Print functionality
ReportExporter.initializePrint('printReport', '#exportTable table', 'Daily OPD & IPD Count Report');
</script>
@endsection