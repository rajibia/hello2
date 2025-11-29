@extends('layouts.app')
@section('title')
    {{ __('messages.medicine.medicine_adjustment_report') }}
@endsection

@include('reports.partials._report-scripts')

@section('page_css')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            a {
                text-decoration: none !important;
                color: inherit !important;
            }
            .badge {
                border: none !important;
                background-color: transparent !important;
                color: #000 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1>{{ __('messages.medicine.medicine_adjustment_report') }}</h1>
                <div class="d-flex align-items-center">
                    <div class="btn-group me-2" role="group">
                        <button id="exportPdf" class="btn btn-danger btn-sm"> <i class="fas fa-file-pdf"></i> PDF</button>
                        <button id="exportExcel" class="btn btn-success btn-sm"> <i class="fas fa-file-excel"></i> Excel</button>
                        <button id="exportCsv" class="btn btn-info btn-sm"> <i class="fas fa-file-csv"></i> CSV</button>
                    </div>

                    <button id="printMedicineAdjustmentReport" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> {{ __('messages.common.print') }}
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                    </a>
                </div>
            </div>
            
            @livewire('medicine-adjustment-report')
        </div>
    </div>
@endsection

@section('page_scripts')
<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function initExports() {
        const tableEl = document.querySelector('#medicineAdjustmentReportTable .table-responsive table') ||
                        document.querySelector('#medicineAdjustmentReportTable table');
        if (!tableEl) return;

        try {
            const table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [],
                reportTitle: 'Medicine Adjustment Report',
                fileName: 'medicine_adjustment_report'
            });
            ReportExporter.initializeLiveSearch(table);
        } catch (e) {
            console.warn('Error initializing exports for Medicine Adjustment:', e);
        }
    }

    // Try immediately
    initExports();

    document.addEventListener('livewire:load', initExports);
    document.addEventListener('livewire:update', initExports);
    document.addEventListener('livewire:updated', initExports);

    // Non-standard print button
    ReportExporter.initializePrint('printMedicineAdjustmentReport', '#medicineAdjustmentReportTable table', 'Medicine Adjustment Report');
});
</script>
@endsection