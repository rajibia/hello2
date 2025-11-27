$(document).ready(function () {

    

    function getOpdBalanceTableData() {
        const dateRange = $('.date-range-display').text().trim() || 'All Dates';
        const table = document.querySelector('.table-responsive table');
        if (!table) {
            alert('No OPD balance records found.');
            return null;
        }

        const headers = [];
        table.querySelectorAll('thead th').forEach(th => headers.push(th.innerText.trim()));

        const rows = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach(td => {
                td.querySelectorAll('i, svg, img, button, a').forEach(el => el.remove());
                row.push(td.innerText.trim());
            });
            rows.push(row);
        });

        return { headers, rows, dateRange };
    }

    $('#exportCsv').click(function () {
        const data = getOpdBalanceTableData();
        if (!data) return;

        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csv += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `OPD_Balance_Report_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    });

    $('#exportExcel').click(function () {
        const data = getOpdBalanceTableData();
        if (!data) return;

        let table = `<table border="1">
            <tr><th colspan="${data.headers.length}" style="font-size:16px">${APP_NAME} - OPD Balance Report</th></tr>
            <tr><th colspan="${data.headers.length}">Period: ${data.dateRange}</th></tr>
            <tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
        data.rows.forEach(row => {
            table += `<tr>${row.map(c => `<td>${c}</td>`).join('')}</tr>`;
        });
        table += `</table>`;

        const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `OPD_Balance_Report_${new Date().toISOString().split('T')[0]}.xls`;
        link.click();
    });

    $('#exportPdf').click(function () {
        const data = getOpdBalanceTableData();
        if (!data) return;

        if (!window.jspdf || !window.jspdf.jsPDF) {
            alert('jsPDF not loaded properly.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'pt', format: 'a4' });
        const pageWidth = doc.internal.pageSize.getWidth();

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(16);
        doc.text(APP_NAME, pageWidth / 2, 40, { align: 'center' });

        doc.setFontSize(14);
        doc.text('OPD Balance Report', pageWidth / 2, 65, { align: 'center' });

        doc.setFontSize(10);
        doc.text(`Period: ${data.dateRange}`, pageWidth / 2, 80, { align: 'center' });
        doc.text(`Generated on: ${new Date().toLocaleString()}`, pageWidth / 2, 95, { align: 'center' });

        if (!doc.autoTable) {
            alert('jsPDF AutoTable plugin not loaded.');
            return;
        }

        doc.autoTable({
            startY: 120,
            head: [data.headers],
            body: data.rows,
            theme: 'grid',
            styles: { fontSize: 8, cellPadding: 4 },
            headStyles: { fillColor: [41, 128, 185], textColor: 255, halign: 'center' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });

        const finalY = doc.lastAutoTable.finalY + 30;
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text(`© ${new Date().getFullYear()} ${APP_NAME} | Hospital Management System`, pageWidth / 2, finalY, { align: 'center' });

        doc.save(`OPD_Balance_Report_${new Date().toISOString().split('T')[0]}.pdf`);
    });

    $('#printReport').click(function () {
        const data = getOpdBalanceTableData();
        if (!data) return;

        const printWindow = window.open('', '_blank');

        let tableHTML = '<table border="1" cellpadding="5" cellspacing="0" width="100%"><thead><tr>';
        data.headers.forEach(h => { tableHTML += `<th>${h}</th>`; });
        tableHTML += '</tr></thead><tbody>';
        data.rows.forEach(row => {
            tableHTML += '<tr>' + row.map(c => `<td>${c}</td>`).join('') + '</tr>';
        });
        tableHTML += '</tbody></table>';

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>OPD Balance Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: 0 auto; }
                    .print-header { text-align: center; margin-bottom: 30px; }
                    .print-header h1, .print-header h2 { margin: 0; padding: 0; }
                    .print-header p { color: #555; margin: 5px 0; }
                    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    a { text-decoration: none; color: inherit; }
                    .print-footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
                    .no-print { text-align: center; margin-top: 20px; }
                    .no-print .btn { display: inline-block; font-weight: 500; padding: 0.65rem 1rem; font-size: 1rem; line-height: 1.5; border-radius: 0.42rem; cursor: pointer; margin: 0 5px; }
                    .no-print .btn-primary { color: #fff; background-color: #3699FF; border: 1px solid #3699FF; }
                    .no-print .btn-secondary { color: #3F4254; background-color: #E4E6EF; border: 1px solid #E4E6EF; }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>${APP_NAME}</h1>
                    <h2>OPD Balance Report</h2>
                    <p>Period: ${data.dateRange}</p>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                ${tableHTML}
                <div class="print-footer">
                    <p>© ${new Date().getFullYear()} ${APP_NAME} | Hospital Management System</p>
                </div>
                <div class="no-print">
                    <button class="btn btn-primary" onclick="window.print();">Print Now</button>
                    <button class="btn btn-secondary" onclick="window.close();">Close</button>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => { printWindow.print(); }, 500);
    });

});
