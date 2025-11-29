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
        (function () {
            function initReportDataTable() {
                const tableElement = document.querySelector('.card .table, #expensesPrintSection table, table');
                if (!tableElement) return;

                const $table = $(tableElement);

                if ($.fn.DataTable.isDataTable(tableElement)) {
                    try { $table.DataTable().destroy(); } catch (e) { /* ignore */ }
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

                // Connect export buttons (support several ID variants)
                const connectExport = (btnId, dtSelector) => {
                    const el = document.getElementById(btnId);
                    if (el) {
                        el.removeEventListener('click', el._repClick);
                        el._repClick = () => dataTable.button(dtSelector).trigger();
                        el.addEventListener('click', el._repClick);
                    }
                };

                ['reportExportExcel','exportExcel','reportExportCsv','exportCsv','reportExportPdf','exportPdf'].forEach(id => {
                    const sel = id.toLowerCase().includes('excel') ? '.buttons-excel' : id.toLowerCase().includes('csv') ? '.buttons-csv' : '.buttons-pdf';
                    connectExport(id, sel);
                });

                // Live Search hookup
                const searchInput = document.getElementById('reportLiveSearch') || document.getElementById('liveSearch');
                if (searchInput) {
                    searchInput.removeEventListener('input', searchInput._repInput);
                    searchInput._repInput = function () { dataTable.search(this.value).draw(); };
                    searchInput.addEventListener('input', searchInput._repInput);
                }
            }

            document.addEventListener('DOMContentLoaded', initReportDataTable);
            document.addEventListener('livewire:update', initReportDataTable);
        })();
        </script>
    @endonce
@endsection
