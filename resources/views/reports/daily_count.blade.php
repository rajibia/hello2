@extends('layouts.app')
@section('title')
    {{ __('Daily OPD & IPD Count') }}
@endsection

@section('page_css')
    <!-- SheetJS (for Excel & CSV) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- jsPDF + autoTable (for PDF) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
@endsection

@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Daily OPD & IPD Count') }}</h1>
            <div>
                <!-- Export Buttons -->
                <button id="exportPDF" class="btn btn-danger me-2">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button id="exportExcel" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button id="exportCSV" class="btn btn-info me-2">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button id="printReport" class="btn btn-primary me-2">
                    <i class="fas fa-print"></i> {{ __('Print Report') }}
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
                </a>
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0">
                <div class="row">
                    <div class="col-12">
                        <livewire:daily-count-report />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Helper: Get text safely
    function getText(selector, fallback = '0') {
        const el = document.querySelector(selector);
        return el ? el.textContent.trim() : fallback;
    }

    // Get report data
    function getReportData() {
        const dateRangeText = getText('.card-header .text-muted.mb-0', 'Custom Range');
        const [start = '', end = ''] = dateRangeText.split(' - ');
        const dateRange = start && end ? `${start.trim()} - ${end.trim()}` : dateRangeText;

        return {
            dateRange,
            generatedOn: new Date().toLocaleString(),
            opd: {
                total: getText('.text-info.fw-bolder.fs-1', '0'),
                new: getText('.text-success.fw-bolder.fs-2', '0'),
                old: getText('.text-info.fw-bolder.fs-2', '0'),
                male: getText('.text-warning.fw-bolder.fs-2', '0'),
                female: getText('.text-danger.fw-bolder.fs-2', '0')
            },
            ipd: {
                total: getText('.text-primary.fw-bolder.fs-1', '0'),
                new: getText('.text-success.fw-bolder.fs-2 ~ .text-success.fw-bolder.fs-2', '0'),
                old: getText('.text-info.fw-bolder.fs-2 ~ .text-info.fw-bolder.fs-2', '0'),
                male: getText('.text-warning.fw-bolder.fs-2 ~ .text-warning.fw-bolder.fs-2', '0'),
                female: getText('.text-danger.fw-bolder.fs-2 ~ .text-danger.fw-bolder.fs-2', '0')
            }
        };
    }

    // 1. PRINT REPORT
    $('#printReport').on('click', function () {
        const data = getReportData();
        const printWindow = window.open('', '_blank');

        printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Daily OPD & IPD Count Report</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { font-size: 28px; color: #333; }
                .header h2 { font-size: 22px; color: #555; }
                .stats-card { border: 1px solid #ddd; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .stats-header { background: #f8f9fa; padding: 12px; border-radius: 8px 8px 0 0; margin: -20px -20px 20px; text-align: center; }
                .stats-header h3 { margin: 0; font-size: 20px; color: #333; }
                .total { font-size: 36px; font-weight: bold; text-align: center; padding: 15px; background: #e3f2fd; border-radius: 8px; color: #1976d2; }
                .row-flex { display: flex; gap: 20px; flex-wrap: wrap; }
                .col { flex: 1; min-width: 200px; text-align: center; padding: 15px; background: #f9f9f9; border-radius: 8px; }
                .label { font-size: 14px; color: #666; }
                .value { font-size: 28px; font-weight: bold; margin-top: 5px; }
                .value.success { color: #1bc5bd; }
                .value.info { color: #8950fc; }
                .value.warning { color: #ffa800; }
                .value.danger { color: #f64e60; }
                @media print { body { padding: 10px; } .no-print { display: none; } }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>Daily OPD & IPD Count Report</h2>
                <p><strong>Period:</strong> ${data.dateRange}</p>
                <p><strong>Generated on:</strong> ${data.generatedOn}</p>
            </div>

            <div class="row-flex">
                <div class="col-6">
                    <div class="stats-card">
                        <div class="stats-header"><h3>OPD Statistics</h3></div>
                        <div class="total">${data.opd.total}</div>
                        <div class="row-flex">
                            <div class="col"><div class="label">New</div><div class="value success">${data.opd.new}</div></div>
                            <div class="col"><div class="label">Old</div><div class="value info">${data.opd.old}</div></div>
                        </div>
                        <div class="row-flex">
                            <div class="col"><div class="label">Male</div><div class="value warning">${data.opd.male}</div></div>
                            <div class="col"><div class="label">Female</div><div class="value danger">${data.opd.female}</div></div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stats-card">
                        <div class="stats-header"><h3>IPD Statistics</h3></div>
                        <div class="total">${data.ipd.total}</div>
                        <div class="row-flex">
                            <div class="col"><div class="label">New</div><div class="value success">${data.ipd.new}</div></div>
                            <div class="col"><div class="label">Old</div><div class="value info">${data.ipd.old}</div></div>
                        </div>
                        <div class="row-flex">
                            <div class="col"><div class="label">Male</div><div class="value warning">${data.ipd.male}</div></div>
                            <div class="col"><div class="label">Female</div><div class="value danger">${data.ipd.female}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:center; margin-top:40px; color:#777;">
                © ${new Date().getFullYear()} Hospital Management System
            </div>

            <div style="text-align:center; margin-top:30px;" class="no-print">
                <button onclick="window.print()" class="btn btn-primary">Print Now</button>
                <button onclick="window.close()" class="btn btn-secondary">Close</button>
            </div>
        </body>
        </html>
        `);

        printWindow.document.close();
        setTimeout(() => printWindow.print(), 500);
    });

    // 2. EXPORT TO PDF
    $('#exportPDF').on('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const data = getReportData();

        doc.setFontSize(18);
        doc.text('Daily OPD & IPD Count Report', 105, 20, { align: 'center' });
        doc.setFontSize(12);
        doc.text(`Period: ${data.dateRange}`, 105, 30, { align: 'center' });
        doc.text(`Generated: ${data.generatedOn}`, 105, 37, { align: 'center' });

        doc.autoTable({
            startY: 50,
            head: [['Category', 'Total', 'New', 'Old', 'Male', 'Female']],
            body: [
                ['OPD Patients', data.opd.total, data.opd.new, data.opd.old, data.opd.male, data.opd.female],
                ['IPD Patients', data.ipd.total, data.ipd.new, data.ipd.old, data.ipd.male, data.ipd.female],
            ],
            theme: 'grid',
            styles: { fontSize: 12, cellPadding: 6 },
            headStyles: { fillColor: [54, 153, 255] }
        });

        doc.save(`OPD_IPD_Report_${new Date().toISOString().slice(0,10)}.pdf`);
    });

    // 3. EXPORT TO EXCEL
    $('#exportExcel').on('click', function () {
        const data = getReportData();
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([
            ['Daily OPD & IPD Count Report'],
            [`Period: ${data.dateRange}`],
            [`Generated: ${data.generatedOn}`],
            [],
            ['Category', 'Total', 'New', 'Old', 'Male', 'Female'],
            ['OPD Patients', data.opd.total, data.opd.new, data.opd.old, data.opd.male, data.opd.female],
            ['IPD Patients', data.ipd.total, data.ipd.new, data.ipd.old, data.ipd.male, data.ipd.female]
        ]);

        XLSX.utils.book_append_sheet(wb, ws, 'Report');
        XLSX.writeFile(wb, `OPD_IPD_Report_${new Date().toISOString().slice(0,10)}.xlsx`);
    });

    // 4. EXPORT TO CSV
    $('#exportCSV').on('click', function () {
        const data = getReportData();
        const csv = [
            ['Daily OPD & IPD Count Report'],
            [`Period: ${data.dateRange}`],
            [`Generated: ${data.generatedOn}`],
            [],
            ['Category,Total,New,Old,Male,Female'],
            [`OPD Patients,${data.opd.total},${data.opd.new},${data.opd.old},${data.opd.male},${data.opd.female}`],
            [`IPD Patients,${data.ipd.total},${data.ipd.new},${data.ipd.old},${data.ipd.male},${data.ipd.female}`]
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `OPD_IPD_Report_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    });
});
</script>
@endsection