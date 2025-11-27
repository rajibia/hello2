@extends('layouts.app')

@section('title', __('Expenses Report'))

@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/sub-header.css') }}">

    <style>
        @media print {
            .no-print, .btn-group, #liveSearch { display: none !important; }
            .actions-column, i, svg, img, button, .btn, .avatar { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1>{{ __('Expenses Report') }}</h1>
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group me-3">
                <button id="exportExcel" class="btn btn-success" title="!Excel">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="exportCsv" class="btn btn-info" title="!CSV">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button id="exportPdf" class="btn btn-danger" title="!PDF">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search expense, category, amount..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="expensesWrapper">
                @livewire('expenses-report')
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

<script>
let table = null;
let initAttempts = 0;

function initDataTable() {
    const wrapper = document.getElementById('expensesWrapper');
    if (!wrapper) return false;

    const tableEl = wrapper.querySelector('.table-responsive table') || wrapper.querySelector('table');
    if (!tableEl || tableEl.rows.length === 0) return false;

    // Destroy if already initialized
    if ($.fn.DataTable.isDataTable(tableEl)) {
        table = $(tableEl).DataTable();
        return true;
    }

    if (table) table.destroy();

    table = $(tableEl).DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', className: 'd-none', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'csvHtml5',   className: 'd-none', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'pdfHtml5',   className: 'd-none', orientation: 'landscape',
              exportOptions: { columns: ':not(:last-child)' },
              customize: doc => doc.content[1].table.body.forEach(row => row.pop())
            }
        ],
        pageLength: 25,
        order: [[0, 'desc']],
        searching: true,
        destroy: true,
        columnDefs: [
            { targets: '_all', defaultContent: '' },
            { targets: -1, className: 'actions-column' }
        ]
    });

    // EXPORT BUTTONS — WORK WITHOUT PAGE REFRESH
    $('#exportExcel').off('click').on('click', function() {
        exportTableData('excel');
    });
    $('#exportCsv').off('click').on('click', function() {
        exportTableData('csv');
    });
    $('#exportPdf').off('click').on('click', function() {
        exportTableData('pdf');
    });

    $('#liveSearch').off('input').on('input', function() {
        table.search(this.value).draw();
    });

    console.log('DataTable + Export Buttons initialized successfully!');
    return true;
}

function exportTableData(format) {
    const tableEl = document.querySelector('#expensesWrapper table');
    if (!tableEl || tableEl.querySelector('tbody').children.length === 0) {
        alert('No data to export');
        return;
    }

    // Get table data excluding last column (actions)
    const rows = [];
    tableEl.querySelectorAll('thead th').forEach((th, index) => {
        if (index < tableEl.querySelectorAll('thead th').length - 1) {
            rows.push(th.textContent.trim());
        }
    });
    
    const headers = [rows];
    const data = [];
    
    tableEl.querySelectorAll('tbody tr').forEach(tr => {
        const rowData = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            // Skip last column (actions)
            if (index < tr.querySelectorAll('td').length - 1) {
                rowData.push(td.textContent.trim());
            }
        });
        if (rowData.length > 0) {
            data.push(rowData);
        }
    });

    const allData = [...headers, ...data];

    if (format === 'excel' || format === 'csv') {
        exportAsExcelOrCsv(allData, format);
    } else if (format === 'pdf') {
        exportAsPdf(tableEl);
    }
}

function exportAsExcelOrCsv(data, format) {
    let csv = data.map(row => 
        row.map(cell => {
            // Escape quotes and wrap in quotes if contains comma
            const escaped = String(cell).replace(/"/g, '""');
            return escaped.includes(',') ? `"${escaped}"` : escaped;
        }).join(',')
    ).join('\n');

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `expenses-${new Date().getTime()}.${format === 'excel' ? 'xlsx' : 'csv'}`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportAsPdf(tableEl) {
    // Use the table's DataTable button to trigger PDF export
    if (table) {
        table.buttons(2).trigger();
    }
}

// METHOD 1: Livewire hook (BEST)
document.addEventListener('livewire:update', () => {
    setTimeout(initDataTable, 300);
});

// METHOD 2: DOM + Livewire load
document.addEventListener('DOMContentLoaded', () => setTimeout(initDataTable, 500));
document.addEventListener('livewire:load', () => setTimeout(initDataTable, 500));

// METHOD 3: Aggressive fallback (NEVER FAILS)
const forceInit = setInterval(() => {
    if (initAttempts++ > 20 || initDataTable()) {
        clearInterval(forceInit);
    }
}, 600);

// PRINT — WORKS IMMEDIATELY
$(document).on('click', '#printReport', function() {
    const hospitalName = "{{ getSettingValue('hospital_name') ?? env('APP_NAME') }}";
    const hospitalAddress = "{{ getSettingValue('hospital_address') }}";
    const dateRange = $('.date-range-display')?.textContent?.trim().replace(/\s+/g, ' ') || 'All Time';

    const tableEl = document.querySelector('#expensesWrapper table') ||
                    document.querySelector('#expensesWrapper .table-responsive table');
    if (!tableEl || tableEl.querySelector('tbody').children.length === 0) {
        alert('No data to print');
        return;
    }

    const temp = tableEl.cloneNode(true);
    temp.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
    temp.querySelectorAll('i, svg, img, button, .btn, .avatar').forEach(el => el.remove());

    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html>
        <html><head><title>Expenses Report</title>
        <style>
            body { font-family: Arial; padding: 40px; max-width: 1100px; margin: 0 auto; }
            @page { margin: 15mm; }
            h1 { text-align: center; font-size: 26px; }
            h2 { text-align: center; color: #333; }
            .info { text-align: center; color: #666; margin: 8px 0; }
            table { width: 100%; border-collapse: collapse; margin: 30px 0; font-size: 12px; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            th { background: #f8f9fa; font-weight: bold; }
            tr:nth-child(even) { background: #f9f9f9; }
            .no-print button { padding: 12px 24px; margin: 10px; border: none; border-radius: 6px; cursor: pointer; }
            .btn-primary { background: #007bff; color: white; }
            .btn-secondary { background: #6c757d; color: white; }
            @media print { .no-print { display: none; } }
        </style>
        </head><body>
        <h1>${hospitalName}</h1>
        ${hospitalAddress ? `<p class="info">${hospitalAddress}</p>` : ''}
        <h2>Expenses Report</h2>
        <p class="info"><strong>Period:</strong> ${dateRange}</p>
        <p class="info"><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
        ${temp.outerHTML}
        <div style="text-align:center; color:#777; margin-top:50px; font-size:12px;">
            <p>&copy; {{ date('Y') }} ${hospitalName}. All rights reserved.</p>
        </div>
        <div class="no-print" style="text-align:center; margin-top:40px;">
            <button class="btn-primary" onclick="window.print()">Print Now</button>
            <button class="btn-secondary" onclick="window.close()">Close</button>
        </div>
        </body></html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => win.print(), 1000);
});
</script>
@endsection