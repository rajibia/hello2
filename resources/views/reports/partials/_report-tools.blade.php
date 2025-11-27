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

{{-- 3. JavaScript: Auto-initialize DataTable + Connect Everything --}}
@section('page_scripts')
    @once
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
        document.addEventListener('DOMContentLoaded', function () {
            // Find the first table inside the current parent card/section
            const tableElement = document.querySelector('.card .table, #expensesPrintSection table, table');
            if (!tableElement) return;

            const $table = $(tableElement);

            // Prevent double initialization
            if ($.fn.DataTable.isDataTable(tableElement)) {
                $table.DataTable().destroy();
            }

            const dataTable = $table.DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', text: 'Excel', className: 'buttons-excel' },
                    { extend: 'csvHtml5',   text: 'CSV',   className: 'buttons-csv' },
                    { extend: 'pdfHtml5',   text: 'PDF',   className: 'buttons-pdf', orientation: 'landscape' }
                ],
                pageLength: 25,
                order: [[0, 'desc']],
                language: { search: "Filter:" }
            });

            // Live Search
            const searchInput = document.getElementById('reportLiveSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function () {
                    dataTable.search(this.value).draw();
                });
            }

            // Connect Export Buttons
            document.getElementById('reportExportExcel')?.addEventListener('click', () => dataTable.button('.buttons-excel').trigger());
            document.getElementById('reportExportCsv')?.addEventListener('click',   () => dataTable.button('.buttons-csv').trigger());
            document.getElementById('reportExportPdf')?.addEventListener('click',   () => dataTable.button('.buttons-pdf').trigger());
        });
        </script>
    @endonce
@endsection