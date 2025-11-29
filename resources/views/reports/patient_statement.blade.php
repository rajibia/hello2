{{-- resources/views/reports/patient-statement.blade.php --}}
@extends('layouts.app')

@section('title', __('Patient Statement Report'))

@section('page_css')
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
            .actions-column { display: none !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            th { background: #f8f9fa; font-weight: bold; }
            tr:nth-child(even) { background: #f9f9f9; }
            .badge { background: transparent !important; color: #000 !important; border: 1px solid #ddd; padding: 4px 8px; }
            i, svg, img, button, .btn, .avatar, .icon { display: none !important; }
        }
    </style>
@endsection

@section('content')
@include('flash::message')

<div class="container-fluid">
    <div class="d-md-flex align-items-center justify-content-between mb-7">
        <h1 class="mb-0">{{ __('Patient Statement Report') }}</h1>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Report
            </button>

            <!-- Export Buttons (Live, no refresh) -->
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
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Live Search -->
    <div class="mb-5">
        <label for="liveSearch" class="form-label fw-bold">Live Search</label>
        <input type="text" id="liveSearch" class="form-control form-control-lg"
               placeholder="Search services, dates, amounts..." autofocus>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="patientStatementWrapper">
                @livewire('patient-statement-report')
            </div>
        </div>
    </div>
</div>
@endsection

document.addEventListener('DOMContentLoaded', initializeDataTable);
@section('page_scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Helper to locate Livewire component root inside wrapper
    function getLivewireRoot() {
        const wrapper = document.getElementById('patientStatementWrapper');
        if (!wrapper) return null;
        // Livewire root element will have an attribute like wire:id
        return wrapper.querySelector('[wire\:id]') || wrapper.querySelector('[wire:id]');
    }

    // Read public properties from the Livewire component
    async function readLivewireProps(propNames = []) {
        const root = getLivewireRoot();
        if (!root || !window.Livewire) return {};
        const id = root.getAttribute('wire:id') || root.getAttribute('wire\:id');
        if (!id) return {};
        try {
            const comp = window.Livewire.find(id);
            const result = {};
            propNames.forEach(p => result[p] = comp.get(p));
            return result;
        } catch (e) {
            return {};
        }
    }

    function buildQuery(params) {
        const qs = new URLSearchParams();
        Object.keys(params || {}).forEach(k => {
            if (params[k] !== undefined && params[k] !== null) qs.append(k, params[k]);
        });
        return qs.toString();
    }

    // Export button handlers
    document.getElementById('exportExcel').addEventListener('click', async function () {
        const s = await readLivewireProps(['startDate', 'endDate', 'patientId', 'searchTerm']);
        const q = buildQuery(s);
        window.location = '{{ route('reports.patient-statement.export-excel') }}' + (q ? '?' + q : '');
    });

    document.getElementById('exportCsv').addEventListener('click', async function () {
        const s = await readLivewireProps(['startDate', 'endDate', 'patientId', 'searchTerm']);
        const q = buildQuery(s);
        window.location = '{{ route('reports.patient-statement.export-csv') }}' + (q ? '?' + q : '');
    });

    document.getElementById('exportPdf').addEventListener('click', async function () {
        const s = await readLivewireProps(['startDate', 'endDate', 'patientId', 'searchTerm']);
        const q = buildQuery(s);
        window.location = '{{ route('reports.patient-statement.export-pdf') }}' + (q ? '?' + q : '');
    });

    // Keep print handler (uses jQuery)
    $('#printReport').off('click').on('click', function () {
        const printWindow = window.open('', '_blank');
        const dateRange = $('.date-range-display').text().trim().replace(/\s+/g, ' ') || 'All Time';

        let patientInfo = '';
        const nameEl = document.querySelector('.patient-info h3');
        const details = document.querySelectorAll('.patient-info p');
        if (nameEl) {
            patientInfo += `<h3 style="margin:0 0 10px 0; color:#333;">${nameEl.textContent}</h3>`;
            details.forEach(p => patientInfo += `<p style="margin:5px 0; color:#555;">${p.innerHTML}</p>`);
        }

        let tableHTML = '<p style="text-align:center;color:#999;font-size:16px;">No records found.</p>';
        const visibleTable = document.querySelector('#patientStatementWrapper table');
        if (visibleTable) {
            const temp = visibleTable.cloneNode(true);
            temp.querySelectorAll('th.actions-column, td.actions-column').forEach(el => el.remove());
            temp.querySelectorAll('i,svg,img,.avatar,.icon,button,.btn,.action-btn').forEach(el => el.remove());
            temp.querySelectorAll('.badge').forEach(b => {
                b.style.background = 'transparent';
                b.style.color = '#000';
                b.style.border = '1px solid #ddd';
                b.style.padding = '4px 8px';
            });
            tableHTML = temp.outerHTML;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Patient Statement Report</title>
                <meta charset="utf-8">
                <style>
                    body { font-family: Arial, sans-serif; padding: 40px; margin: 0; line-height: 1.6; }
                    .header { text-align: center; margin-bottom: 30px; }
                    h1 { font-size: 28px; margin: 0; color: #333; }
                    h2 { font-size: 22px; color: #0d6efd; margin: 10px 0; }
                    .info { color: #666; font-size: 14px; margin: 5px 0; }
                    .patient-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                    th { background: #f8f9fa; font-weight: bold; }
                    tr:nth-child(even) { background: #f9f9f9; }
                    .footer { margin-top: 60px; text-align: center; color: #888; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>{{ env('APP_NAME') }}</h1>
                    <h2>Patient Statement Report</h2>
                    <p class="info"><strong>Period:</strong> ${dateRange}</p>
                    <p class="info"><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
                </div>
                <div class="patient-info">${patientInfo || ''}</div>
                ${tableHTML}
                <div class="footer">
                    <p>© ${new Date().getFullYear()} Hospital Management System. All rights reserved.</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 1000);
    });
</script>
@endsection