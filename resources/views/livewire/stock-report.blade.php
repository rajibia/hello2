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

@section('page_scripts')
    <script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const dt = ReportExporter.initializeExports($('#stockTable'), {
                    excludeColumns: [],
                    reportTitle: 'Inventory Stock Report',
                    fileName: 'stock_report'
                });

                ReportExporter.initializePrint('printReport', '#stockTable', 'Inventory Stock Report');
            } catch (e) {
                console.error('ReportExporter init failed for stock report:', e);
            }
        });
    </script>
@endsection