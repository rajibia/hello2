@extends('layouts.app')

@section('title')
    {{ __('Discharge Report') }}
@endsection

@section('page_css')
    <!-- Load required libraries for PDF & Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
@endsection

@section('content')
    @include('flash::message')

    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">{{ __('Discharge Report') }}</h1>
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
                        @livewire('discharge-report')
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
    function getText(selector, fallback = '') {
        const el = document.querySelector(selector);
        return el ? el.textContent.trim().replace(/\s+/g, ' ') : fallback;
    }

    // Get active tab and table data
    function getReportData() {
        const dateRange = getText('.date-range-display', 'All Time');
        const activeTab = document.querySelector('.nav-link.active');
        const isOpd = activeTab && (activeTab.textContent.includes('OPD') || activeTab.getAttribute('href') === '#opd-tab');
        const reportType = isOpd ? 'OPD' : 'IPD';
        const tabId = isOpd ? 'opd-tab' : 'ipd-tab';

        // Try multiple ways to get the table
        let table = document.querySelector(`#${tabId} table`) || 
                    document.querySelector('.tab-pane.active table') ||
                    document.querySelector('table');

        if (!table) return { dateRange, reportType, rows: [], headers: [], hasData: false };

        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => {
            return Array.from(tr.querySelectorAll('td')).map(td => {
                // Clean cell: remove icons, buttons, avatars
                const clone = td.cloneNode(true);
                clone.querySelectorAll('i, svg, img, button, .btn, .avatar, .action-btn').forEach(el => el.remove());
                return clone.textContent.trim().replace(/\s+/g, ' ');
            });
        });

        return { dateRange, reportType, headers, rows, hasData: rows.length > 0 };
    }

    // 1. PRINT REPORT
    $('#printReport').on('click', function () {
        const data = getReportData();
        const win = window.open('', '_blank');

        const tableRows = data.rows.map(row => 
            `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`
        ).join('');

        const tableHTML = data.hasData ? `
            <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px;">
                <thead style="background:#f8f9fa;">
                    <tr>${data.headers.map(h => `<th style="padding:12px; text-align:left;">${h}</th>`).join('')}</tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        ` : `<p style="text-align:center; color:#999; font-size:18px;">No ${data.reportType} discharge records found for the selected period.</p>`;

        win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${data.reportType} Discharge Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; margin: 0; }
                .header { text-align: center; margin-bottom: 30px; }
                h1 { font-size: 28px; color: #333; margin: 0; }
                h2 { font-size: 22px; color: #555; margin: 10px 0; }
                .info { color: #666; font-size: 14px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 13px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background: #f8f9fa; font-weight: bold; }
                tr:nth-child(even) { background: #f9f9f9; }
                .footer { text-align: center; margin-top: 50px; color: #777; font-size: 12px; }
                .no-print { text-align: center; margin: 30px 0; }
                .btn { padding: 10px 20px; margin: 0 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
                .btn-primary { background: #3699FF; color: white; }
                .btn-secondary { background: #E4E6EF; color: #3F4254; }
                @media print { .no-print { display: none; } body { padding: 10px; } }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ env('APP_NAME') }}</h1>
                <h2>${data.reportType} Discharge Report</h2>
                <p class="info"><strong>Period:</strong> ${data.dateRange}</p>
                <p class="info"><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
            </div>

            ${tableHTML}

            <div class="footer">
                <p>© ${new Date().getFullYear()} Hospital Management System. All rights reserved.</p>
            </div>

            <div class="no-print">
                <button class="btn btn-primary" onclick="window.print()">Print Now</button>
                <button class="btn btn-secondary" onclick="window.close()">Close</button>
            </div>
        </body>
        </html>
        `);

        win.document.close();
        setTimeout(() => win.print(), 500);
    });

    // 2. EXPORT TO PDF
    $('#exportPDF').on('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape
        const data = getReportData();

        doc.setFontSize(20);
        doc.text(`${data.reportType} Discharge Report`, 148, 20, { align: 'center' });
        doc.setFontSize(12);
        doc.text(`Period: ${data.dateRange}`, 148, 30, { align: 'center' });
        doc.text(`Generated: ${new Date().toLocaleString()}`, 148, 37, { align: 'center' });

        if (data.hasData) {
            doc.autoTable({
                head: [data.headers],
                body: data.rows,
                startY: 50,
                theme: 'grid',
                styles: { fontSize: 9, cellPadding: 4 },
                headStyles: { fillColor: [54, 153, 255], textColor: 255 },
                alternateRowStyles: { fillColor: [248, 249, 250] }
            });
        } else {
            doc.text('No records found', 148, 60, { align: 'center' });
        }

        doc.save(`${data.reportType}_Discharge_Report_${new Date().toISOString().slice(0,10)}.pdf`);
    });

    // 3. EXPORT TO EXCEL
    $('#exportExcel').on('click', function () {
        const data = getReportData();
        const wb = XLSX.utils.book_new();

        const wsData = [
            [`${data.reportType} Discharge Report`],
            [`Period: ${data.dateRange}`],
            [`Generated: ${new Date().toLocaleString()}`],
            [],
            data.headers
        ];

        if (data.hasData) {
            data.rows.forEach(row => wsData.push(row));
        } else {
            wsData.push(['No records found']);
        }

        const ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, 'Discharge Report');
        XLSX.writeFile(wb, `${data.reportType}_Discharge_Report_${new Date().toISOString().slice(0,10)}.xlsx`);
    });

    // 4. EXPORT TO CSV
    $('#exportCSV').on('click', function () {
        const data = getReportData();
        let csv = `data:text/csv;charset=utf-8,${data.reportType} Discharge Report\n`;
        csv += `Period: ${data.dateRange}\n`;
        csv += `Generated: ${new Date().toLocaleString()}\n\n`;
        csv += data.headers.join(',') + '\n';

        if (data.hasData) {
            data.rows.forEach(row => {
                csv += row.map(cell => `"${cell}"`).join(',') + '\n';
            });
        } else {
            csv += 'No records found\n';
        }

        const link = document.createElement('a');
        link.setAttribute('href', encodeURI(csv));
        link.setAttribute('download', `${data.reportType}_Discharge_Report_${new Date().toISOString().slice(0,10)}.csv`);
        link.click();
    });
});
</script>
@endsection