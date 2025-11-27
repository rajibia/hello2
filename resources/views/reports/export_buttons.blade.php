{{-- 
    This partial is included with one required variable: 
    $reportRouteName - the base name for the export route (e.g., 'reports.attendance.export')
--}}
<div class="d-flex justify-content-end mb-4">
    <button type="button" class="btn btn-outline-danger me-2" 
        onclick="window.location='{{ route($reportRouteName, ['format' => 'pdf']) }}'">
        <i class="fas fa-file-pdf"></i> Export to PDF
    </button>
    <button type="button" class="btn btn-outline-success me-2" 
        onclick="window.location='{{ route($reportRouteName, ['format' => 'xlsx']) }}'">
        <i class="fas fa-file-excel"></i> Export to Excel
    </button>
    <button type="button" class="btn btn-outline-info" 
        onclick="window.location='{{ route($reportRouteName, ['format' => 'csv']) }}'">
        <i class="fas fa-file-csv"></i> Export to CSV
    </button>
</div>