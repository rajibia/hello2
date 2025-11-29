{{--  resources/views/partials/_report-tools.blade.php  --}}
{{--  ONE-FILE SOLUTION: Live Search + Export Buttons + Full JS  --}}

{{-- 1. CSS (only loads once) --}}
@section('page_css')
    @once
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">
    @endonce
@endsection

{{-- 2. HTML: Search + Export Buttons --}}
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="flex-fill" style="max-width: 400px;">
        <label for="reportLiveSearch" class="form-label fw-bold mb-1">Live Search</label>
        <input type="text" id="reportLiveSearch" class="form-control form-control-lg" 
               placeholder="Type to filter instantly..." autofocus>
    </div>

    <div class="btn-group" role="group">
        <button type="button" id="reportExportPdf" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> PDF
        </button>
        <button type="button" id="reportExportExcel" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Excel
        </button>
        <button type="button" id="reportExportCsv" class="btn btn-info btn-sm">
            <i class="fas fa-file-csv"></i> CSV
        </button>
    </div>
</div>

{{-- 3. JavaScript: Initialization handled by shared scripts partial --}}