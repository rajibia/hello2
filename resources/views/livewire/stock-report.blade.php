<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }
    </style>

    <div class="mb-5 mb-xl-10">
        <div class="pt-3">
            <!-- Filters Row -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="input-group me-2 mb-3" style="max-width: 250px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-layer-group text-white"></i>
                        </span>
                        <select class="form-select" wire:model="categoryFilter">
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="input-group me-2 mb-3" style="max-width: 250px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-copyright text-white"></i>
                        </span>
                        <select class="form-select" wire:model="brandFilter">
                            @foreach($brands as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="input-group me-2 mb-3" style="max-width: 250px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-box text-white"></i>
                        </span>
                        <select class="form-select" wire:model="stockStatusFilter">
                            @foreach($stockStatuses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="input-group me-2 mb-3" style="max-width: 250px;">
                        <span class="input-group-text bg-primary">
                            <i class="fas fa-calendar-times text-white"></i>
                        </span>
                        <select class="form-select" wire:model="expiryFilter">
                            @foreach($expiryStatuses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <div class="input-group w-100">
                    <span class="input-group-text bg-primary">
                        <i class="fas fa-search text-white"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search by name, category, brand, or composition"
                           wire:model.debounce.500ms="searchQuery">
                    <button type="button" class="btn btn-light-secondary" wire:click="clearFilters">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="mb-5 text-end">
                <button id="exportCsv" class="btn btn-success me-2">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button id="exportExcel" class="btn btn-info me-2">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="exportPdf" class="btn btn-danger me-2">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>

            <!-- Stock Summary Cards -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-primary">
                                        <i class="fas fa-boxes text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_items'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Total Items</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-success">
                                        <i class="fas fa-pills text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_available_quantity'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Available Units</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-info">
                                        <i class="fas fa-warehouse text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ $stockItems['total_store_quantity'] }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Store Units</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-light-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-warning">
                                        <i class="fas fa-money-bill-alt text-white fs-2 p-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-4 text-dark fw-bold">{{ getCurrencySymbol() }} {{ number_format($stockItems['total_value'], 2) }}</div>
                                    <div class="fs-7 text-muted fw-semibold">Total Stock Value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div class="table-responsive">
                        <table id="stockTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">{{ __('Medicine Name') }}</th>
                                    <th class="min-w-100px">{{ __('Category') }}</th>
                                    <th class="min-w-100px">{{ __('Brand') }}</th>
                                    <th class="min-w-100px">{{ __('Composition') }}</th>
                                    <th class="min-w-100px text-end">{{ __('Buying Price') }}</th>
                                    <th class="min-w-100px text-end">{{ __('Selling Price') }}</th>
                                    <th class="min-w-80px text-center">{{ __('Dispensary') }}</th>
                                    <th class="min-w-80px text-center">{{ __('Store') }}</th>
                                    <th class="min-w-80px text-center">{{ __('Available') }}</th>
                                    <th class="min-w-100px">{{ __('Expiry Date') }}</th>
                                    <th class="min-w-100px">{{ __('Status') }}</th>
                                    <th class="min-w-100px text-end">{{ __('Value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockItems['data'] as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['category'] }}</td>
                                        <td>{{ $item['brand'] }}</td>
                                        <td>{{ $item['salt_composition'] }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['buying_price'], 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['selling_price'], 2) }}</td>
                                        <td class="text-center">{{ $item['quantity'] }}</td>
                                        <td class="text-center">{{ $item['store_quantity'] }}</td>
                                        <td class="text-center">{{ $item['available_quantity'] }}</td>
                                        <td>
                                            {{ $item['expiry_date'] }}
                                            @if($item['expiry_date'] !== 'N/A')
                                                @if($item['expiry_status'] === 'Expired')
                                                    <span class="badge bg-light-danger">Expired</span>
                                                @elseif($item['expiry_status'] === 'Expiring Soon')
                                                    <span class="badge bg-light-warning">{{ $item['days_until_expiry'] }} days left</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-{{ $item['stock_status'] === 'In Stock' ? 'success' : ($item['stock_status'] === 'Low Stock' ? 'warning' : 'danger') }}">
                                                {{ $item['stock_status'] }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($item['total_value'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">{{ __('No stock items found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="6" class="text-end">{{ __('Totals:') }}</td>
                                    <td class="text-center">{{ $stockItems['total_quantity'] }}</td>
                                    <td class="text-center">{{ $stockItems['total_store_quantity'] }}</td>
                                    <td class="text-center">{{ $stockItems['total_available_quantity'] }}</td>
                                    <td colspan="2"></td>
                                    <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($stockItems['total_value'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($stockItems['total'] > 0)
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div>
                                {{ $stockItems['paginator']->onEachSide(1)->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required Libraries (Add to your layout or here) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const { jsPDF } = window.jspdf;

    // Helper to get clean text from cell (remove badges, icons)
    function getCellText(cell) {
        const temp = document.createElement('div');
        temp.innerHTML = cell.innerHTML;
        temp.querySelectorAll('.badge, i, svg, img').forEach(el => el.remove());
        return temp.textContent || temp.innerText || '';
    }

    // Get table data
    function getTableData() {
        const table = document.getElementById('stockTable');
        const rows = table.querySelectorAll('tr');
        const data = [];

        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('th, td');
            if (cells.length === 0) return;

            const rowData = Array.from(cells).map(cell => getCellText(cell).trim());
            if (index === 0) {
                // Header
                data.push(rowData);
            } else if (row.closest('tfoot')) {
                // Skip tfoot or include only total row if needed
                if (row.querySelector('td') && row.querySelector('td').textContent.includes('Totals:')) {
                    data.push(rowData);
                }
            } else {
                data.push(rowData);
            }
        });

        return data;
    }

    // Export CSV
    document.getElementById('exportCsv').addEventListener('click', () => {
        const data = getTableData();
        let csv = data.map(row => row.join(',')).join('\n');
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Stock_Report_' + new Date().toISOString().slice(0,10) + '.csv';
        link.click();
    });

    // Export Excel
    document.getElementById('exportExcel').addEventListener('click', () => {
        const data = getTableData();
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Stock Report");
        XLSX.writeFile(wb, 'Stock_Report_' + new Date().toISOString().slice(0,10) + '.xlsx');
    });

    // Export PDF
    document.getElementById('exportPdf').addEventListener('click', () => {
        const doc = new jsPDF('l', 'mm', 'a4'); // landscape
        doc.setFontSize(16);
        doc.text("Inventory Stock Report", 14, 15);
        doc.setFontSize(10);
        doc.text("Generated on: " + new Date().toLocaleString(), 14, 22);

        const tableData = getTableData().map(row => row.map(cell => cell.replace(/,/g, ' ')));

        doc.autoTable({
            head: [tableData[0]],
            body: tableData.slice(1),
            startY: 30,
            theme: 'grid',
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [54, 96, 146] },
            columnStyles: {
                4: { halign: 'right' },  // Buying Price
                5: { halign: 'right' },  // Selling Price
                11: { halign: 'right' }  // Value
            }
        });

        doc.save('Stock_Report_' + new Date().toISOString().slice(0,10) + '.pdf');
    });

    // Print Functionality (already improved)
    document.getElementById('printReport').addEventListener('click', function () {
        let printWindow = window.open('', '_blank');
        const table = document.getElementById('stockTable').outerHTML;

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Stock Report - {{ env('APP_NAME') }}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f0f0f0; }
                    .badge { background: none !important; color: black !important; font-weight: normal; }
                    h1, h2 { text-align: center; }
                    @media print { body { padding: 10px; } }
                </style>
            </head>
            <body>
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>Inventory Stock Report</h2>
                <p style="text-align:center;">Generated on: ${new Date().toLocaleString()}</p>
                ${table}
                <script>window.print();<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    });
});
</script>
@endsection