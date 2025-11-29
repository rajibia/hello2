@extends('layouts.app')

@section('title', __('messages.medicine.expiry_medicine_report'))

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
            th { background: #f8f9fa; font-weight: bold; }
            tr:nth-child(even) { background: #f9f9f9; }
            .badge {
                background: transparent !important;
                color: #d63031 !important;
                border: 1px solid #ddd;
                padding: 4px 8px;
                font-weight: bold;
            }
            i, svg, img, button, .btn, .avatar, .action-btn, .icon { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <h1 class="mb-0">{{ __('messages.medicine.expiry_medicine_report') }}</h1>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> {{ __('messages.common.print') }}
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
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>

    <!-- Live Search -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search by medicine name, batch, expiry date..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="expiryReportWrapper">
                @livewire('expiry-medicine-report')
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

<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>

<script>
const initExpiryMedicineExports = function () {
    ReportExporter.initializeOnWrapper('#expiryReportWrapper', {
        excludeColumns: [':last-child'],
        reportTitle: 'Expiry Medicine Report',
        fileName: 'expiry_medicine_report'
    });

    ReportExporter.initializePrint('printReport', '#expiryReportWrapper table', 'Expiry Medicine Report');
};

// Try immediately
initExpiryMedicineExports();

// Also on DOMContentLoaded
document.addEventListener('DOMContentLoaded', initExpiryMedicineExports);

// Re-init on Livewire events
window.addEventListener('livewire:load', initExpiryMedicineExports);
window.addEventListener('livewire:update', initExpiryMedicineExports);
window.addEventListener('livewire:updated', initExpiryMedicineExports);
</script>
@endsection