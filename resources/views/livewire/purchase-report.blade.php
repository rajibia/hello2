<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }

        /* print-specific styles moved here for Livewire component */
        @media print {
            @page { margin: 10px; }
            body { padding: 10px !important; font-size: 12px !important; }
            .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .print-table th, .print-table td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
            .print-table th { background-color: #f2f2f2; text-align: left; }
            .print-header { text-align: center; margin-bottom: 20px; }
            .print-header h2 { margin-bottom: 5px; }
            .print-header h4 { margin-top: 0; color: #6c757d; }
            .no-print { display: none !important; }
            a { text-decoration: none !important; color: inherit !important; }
            .badge { border: none !important; background-color: transparent !important; color: #000 !important; }
        }
    </style>

    <div class="mb-5 mb-xl-10">
        <div class="pt-3">
            <div class="row mb-5">
                <div class="col-lg-4 col-md-12">
                    <div class="d-flex flex-wrap mb-5">
                        <div class="btn-group me-5 mb-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'today' ? 'active' : '' }}"
                                wire:click="changeDateFilter('today')">
                                <span class="fw-bold">Today</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'yesterday' ? 'active' : '' }}"
                                wire:click="changeDateFilter('yesterday')">
                                <span class="fw-bold">Yesterday</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'this_week' ? 'active' : '' }}"
                                wire:click="changeDateFilter('this_week')">
                                <span class="fw-bold">This Week</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter === 'this_month' ? 'active' : '' }}"
                                wire:click="changeDateFilter('this_month')">
                                <span class="fw-bold">This Month</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-user-tie text-white"></i>
                            </span>
                            <select class="form-select" wire:model="supplierFilter">
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="d-flex align-items-center">
                        <div class="position-relative w-100">
                            <div class="input-group date-range-picker">
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </span>
                                <input type="date" class="form-control" placeholder="Start Date" id="startDate"
                                    wire:model="startDate" max="{{ date('Y-m-d') }}">
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                <input type="date" class="form-control" placeholder="End Date" id="endDate"
                                    wire:model="endDate" max="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-light-secondary" wire:click="clearFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="d-flex">
                        <div class="input-group me-2 mb-3" style="max-width: 250px;">
                            <span class="input-group-text bg-primary">
                                <i class="fas fa-money-check-alt text-white"></i>
                            </span>
                            <select class="form-select" wire:model="paymentStatusFilter">
                                @foreach($paymentStatuses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="reportDateFilter" class="text-muted fw-normal">
                <i class="fas fa-calendar-alt me-1"></i> {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </div>

            <div class="mb-5">
                <div class="input-group w-100">
                    <input type="text" class="form-control" placeholder="Search by purchase number, supplier name, or notes"
                           wire:model.debounce.500ms="searchQuery">
                </div>
            </div>

            <div class="d-flex justify-content-end mb-3 gap-2 no-print">
                <button id="exportExcel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </button>

                <button id="exportCsv" class="btn btn-info">
                    <i class="fas fa-file-csv"></i> CSV
                </button>

                <button id="exportPdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>

            <!-- Purchases Table in Card -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div id="purchasePrintSection">
                        <div class="table-responsive">
                            <table id="purchasesTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-100px">{{ __('Date') }}</th>
                                        <th class="min-w-100px">{{ __('Purchase No') }}</th>
                                        <th class="min-w-150px">{{ __('Supplier') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Items') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Total') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Discount') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Tax') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Net Amount') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Paid') }}</th>
                                        <th class="min-w-100px text-end">{{ __('Balance') }}</th>
                                        <th class="min-w-100px">{{ __('Payment') }}</th>
                                        <th class="min-w-100px">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases['data'] as $purchase)
                                        <tr>
                                            <td>{{ $purchase['date'] ?? '-' }}</td>
                                            <td>{{ $purchase['purchase_no'] ?? '-' }}</td>
                                            <td>{{ $purchase['supplier'] ?? '-' }}</td>
                                            <td class="text-end">{{ $purchase['items_count'] ?? 0 }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['total'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['discount'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['tax'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['net_amount'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['paid_amount'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchase['balance'] ?? 0, 2) }}</td>
                                            <td>{{ $purchase['payment_type'] ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $badgeColor = ($purchase['payment_status'] ?? 0) == 1 ? 'success' : 'danger';
                                                @endphp
                                                <span class="badge bg-light-{{ $badgeColor }}">{{ $purchase['payment_status_label'] ?? '-' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">{{ __('No purchase records found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="7" class="text-end">{{ __('Totals:') }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchases['total_amount'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchases['total_paid'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ getCurrencySymbol() }} {{ number_format($purchases['total_balance'] ?? 0, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(!empty($purchases['paginator']) && $purchases['total'] > 0)
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div>
                                    {{ $purchases['paginator']->onEachSide(1)->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden print wrapper (renamed to avoid duplicate ID issues) -->
    <div class="d-none" id="purchasePrintSectionHidden">
        <!-- will be populated by JS when printing -->
    </div>

    <!-- Livewire client-side helpers -->
    <script>
        // Ensure date inputs keep consistent ranges and propagate to Livewire
        document.addEventListener('livewire:load', function () {
            const startDateInput = document.querySelector('input[wire\\:model="startDate"]');
            const endDateInput = document.querySelector('input[wire\\:model="endDate"]');

            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (endDateInput.value && this.value > endDateInput.value) {
                        endDateInput.value = this.value;
                        @this.set('endDate', this.value);
                    }
                });

                endDateInput.addEventListener('change', function() {
                    if (startDateInput.value && this.value < startDateInput.value) {
                        startDateInput.value = this.value;
                        @this.set('startDate', this.value);
                    }
                });
            }
        });
    </script>

    {{-- All DataTables CSS/JS + initialization placed in page_scripts section so they load once --}}
    @section('page_scripts')
        <!-- DataTables CSS/JS (only included here) -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">

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
            // Keep a reference to DataTable instance
            let purchasesDt = null;

            // Initialize or re-initialize the DataTable safely
            function initPurchasesDataTable() {
                const table = document.querySelector('#purchasesTable');
                if (!table) return;

                // If DataTable already exists, destroy it first
                try {
                    if ($.fn.DataTable.isDataTable(table)) {
                        $(table).DataTable().clear().destroy();
                    }
                } catch (err) {
                    // ignore destroy errors
                    console.warn('DataTable destroy error (ignored):', err);
                }

                // Create fresh DataTable
                purchasesDt = $(table).DataTable({
                    dom: 'Bfrtip',
                    paging: false,
                    searching: false,
                    ordering: true,
                    info: false,
                    responsive: true,
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Purchase Report',
                            className: 'd-none',
                            exportOptions: { columns: ':visible' }
                        },
                        {
                            extend: 'csvHtml5',
                            title: 'Purchase Report',
                            className: 'd-none',
                            exportOptions: { columns: ':visible' }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Purchase Report',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            className: 'd-none',
                            exportOptions: { columns: ':visible' }
                        }
                    ]
                });

                // Wire up visible export buttons to DataTables' hidden buttons
                $('#exportExcel').off('click').on('click', () => purchasesDt.buttons(0).trigger());
                $('#exportCsv').off('click').on('click', () => purchasesDt.buttons(1).trigger());
                $('#exportPdf').off('click').on('click', () => purchasesDt.buttons(2).trigger());
            }

            // Initialize on page load if Livewire has already rendered table
            document.addEventListener('DOMContentLoaded', function () {
                // Attempt a first init (may fail if Livewire not rendered yet)
                setTimeout(initPurchasesDataTable, 300);
            });

            // Use Livewire hooks to re-initialize after Livewire updates the DOM
            if (window.Livewire) {
                // when livewire finishes processing a message, reinit
                Livewire.hook('message.processed', (message, component) => {
                    // small delay to ensure DOM is updated
                    setTimeout(initPurchasesDataTable, 100);
                });
            } else {
                // Fallback: listen for livewire:update
                document.addEventListener('livewire:update', () => setTimeout(initPurchasesDataTable, 150));
            }

            // PRINT function: build a printable HTML (cleaned)
            document.addEventListener('click', function (e) {
                if (e.target && (e.target.id === 'printReport' || e.target.closest('#printReport'))) {
                    e.preventDefault();

                    const visibleTable = document.querySelector('#purchasesTable');
                    if (!visibleTable) {
                        alert('No table to print');
                        return;
                    }

                    // Clone and simplify table for print
                    const temp = visibleTable.cloneNode(true);

                    // Remove icons, action buttons etc.
                    temp.querySelectorAll('i, svg, img, .action-btn, .btn').forEach(el => el.remove());

                    // Remove last column if it's actions (detect number of headers vs tds)
                    try {
                        const thCount = temp.querySelectorAll('thead tr th').length;
                        const firstTds = temp.querySelectorAll('tbody tr:first-child td').length;
                        // if first row has fewer tds than headers, do nothing; otherwise if tds > ths remove extras
                        if (firstTds > thCount) {
                            // trim each row to thCount cells
                            temp.querySelectorAll('tbody tr').forEach(row => {
                                while (row.children.length > thCount) row.removeChild(row.lastElementChild);
                            });
                        }
                    } catch (err) {
                        // ignore
                    }

                    const dateRange = document.querySelector('#reportDateFilter')?.textContent.trim() ?? 'All Time';

                    const win = window.open('', '_blank');
                    win.document.write(`
                        <!doctype html>
                        <html>
                        <head>
                            <meta charset="utf-8">
                            <title>Purchase Report</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; color: #222; }
                                .header { text-align: center; margin-bottom: 20px; }
                                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background: #f8f9fa; }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <h2>${document.title || 'Purchase Report'}</h2>
                                <p><strong>Period:</strong> ${dateRange}</p>
                                <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                            </div>
                            ${temp.outerHTML}
                            <div style="text-align:center; margin-top:20px; color:#666;">
                                <small>© ${new Date().getFullYear()} ${document.location.hostname}</small>
                            </div>
                        </body>
                        </html>
                    `);
                    win.document.close();
                    win.focus();
                    setTimeout(() => win.print(), 500);
                }
            });
        </script>
    @endsection
</div>
