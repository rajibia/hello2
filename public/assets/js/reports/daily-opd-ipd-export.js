$(document).ready(function() {
    function getReportData() {
        let startDate = $('.card-header .text-muted.mb-0').first().text().split(' - ')[0] || '';
        let endDate = $('.card-header .text-muted.mb-0').first().text().split(' - ')[1] || '';
        let dateRange = startDate && endDate ? `${startDate} - ${endDate}` : 'Custom Range';

        return {
            dateRange,
            opdTotal: $('.text-info.fw-bolder.fs-1').first().text() || '0',
            ipdTotal: $('.text-primary.fw-bolder.fs-1').first().text() || '0',
            opdNew: $('.text-success.fw-bolder.fs-2').first().text() || '0',
            opdOld: $('.text-info.fw-bolder.fs-2').first().text() || '0',
            opdMale: $('.text-warning.fw-bolder.fs-2').first().text() || '0',
            opdFemale: $('.text-danger.fw-bolder.fs-2').first().text() || '0',
            ipdNew: $('.text-success.fw-bolder.fs-2').last().text() || '0',
            ipdOld: $('.text-info.fw-bolder.fs-2').last().text() || '0',
            ipdMale: $('.text-warning.fw-bolder.fs-2').last().text() || '0',
            ipdFemale: $('.text-danger.fw-bolder.fs-2').last().text() || '0',
        };
    }

    // PRINT REPORT
    $('#printReport').click(function() {
        let d = getReportData();
        let printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html><head>
            <title>Daily OPD & IPD Count Report</title>
            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto; }
                .title { text-align: center; margin-bottom: 20px; }
                .stats-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .stats-table th, .stats-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                .stats-table th { background-color: #f8f9fa; font-weight: bold; }
                .no-print { text-align: center; margin-top: 20px; }
                @media print { .no-print { display: none !important; } }
            </style>
            </head>
            <body>
                <div class="title">
                    <h1>${APP_NAME}</h1>
                    <h2>Daily OPD & IPD Count Report</h2>
                    <p>Period: ${d.dateRange}</p>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                <table class="stats-table">
                    <tr><th>Category</th><th>New</th><th>Old</th><th>Male</th><th>Female</th><th>Total</th></tr>
                    <tr><td>OPD</td><td>${d.opdNew}</td><td>${d.opdOld}</td><td>${d.opdMale}</td><td>${d.opdFemale}</td><td>${d.opdTotal}</td></tr>
                    <tr><td>IPD</td><td>${d.ipdNew}</td><td>${d.ipdOld}</td><td>${d.ipdMale}</td><td>${d.ipdFemale}</td><td>${d.ipdTotal}</td></tr>
                </table>
                <div class="no-print">
                    <button class="btn btn-primary" onclick="window.print();">Print</button>
                    <button class="btn btn-secondary" onclick="window.close();">Close</button>
                </div>
            </body></html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    });

    // EXPORT CSV
    $('#exportCsv').click(function() {
        let d = getReportData();
        let csv = `Category,New,Old,Male,Female,Total\n` +
            `OPD,${d.opdNew},${d.opdOld},${d.opdMale},${d.opdFemale},${d.opdTotal}\n` +
            `IPD,${d.ipdNew},${d.ipdOld},${d.ipdMale},${d.ipdFemale},${d.ipdTotal}\n`;
        let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `daily_opd_ipd_report_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    });

    // EXPORT EXCEL
    $('#exportExcel').click(function() {
        let d = getReportData();
        let table = `
            <table border="1">
                <tr><th colspan="6">Daily OPD & IPD Count Report</th></tr>
                <tr><th colspan="6">Period: ${d.dateRange}</th></tr>
                <tr><th>Category</th><th>New</th><th>Old</th><th>Male</th><th>Female</th><th>Total</th></tr>
                <tr><td>OPD</td><td>${d.opdNew}</td><td>${d.opdOld}</td><td>${d.opdMale}</td><td>${d.opdFemale}</td><td>${d.opdTotal}</td></tr>
                <tr><td>IPD</td><td>${d.ipdNew}</td><td>${d.ipdOld}</td><td>${d.ipdMale}</td><td>${d.ipdFemale}</td><td>${d.ipdTotal}</td></tr>
            </table>`;
        let blob = new Blob([table], { type: 'application/vnd.ms-excel' });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `daily_opd_ipd_report_${new Date().toISOString().split('T')[0]}.xls`;
        link.click();
    });

    // EXPORT PDF
    $('#exportPdf').click(function() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
        let d = getReportData();

        pdf.setFontSize(18);
        pdf.text(APP_NAME, 105, 20, null, null, "center");
        pdf.setFontSize(14);
        pdf.text("Daily OPD & IPD Count Report", 105, 30, null, null, "center");
        pdf.setFontSize(11);
        pdf.text(`Period: ${d.dateRange}`, 20, 40);
        pdf.text(`Generated: ${new Date().toLocaleString()}`, 20, 46);

        let y = 60;
        pdf.text("Category", 20, y);
        pdf.text("New", 60, y);
        pdf.text("Old", 80, y);
        pdf.text("Male", 100, y);
        pdf.text("Female", 120, y);
        pdf.text("Total", 145, y);
        pdf.line(20, y + 2, 190, y + 2);

        y += 10;
        pdf.text("OPD", 20, y);
        pdf.text(d.opdNew, 60, y);
        pdf.text(d.opdOld, 80, y);
        pdf.text(d.opdMale, 100, y);
        pdf.text(d.opdFemale, 120, y);
        pdf.text(d.opdTotal, 145, y);

        y += 10;
        pdf.text("IPD", 20, y);
        pdf.text(d.ipdNew, 60, y);
        pdf.text(d.ipdOld, 80, y);
        pdf.text(d.ipdMale, 100, y);
        pdf.text(d.ipdFemale, 120, y);
        pdf.text(d.ipdTotal, 145, y);

        pdf.save(`daily_opd_ipd_report_${new Date().toISOString().split('T')[0]}.pdf`);
    });
});
