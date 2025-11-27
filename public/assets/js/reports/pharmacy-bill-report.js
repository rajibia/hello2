$(document).ready(function() {
    function getPharmacyBillTableData() {
        const dateRange = $('.date-range-display').text().trim() || 'All Dates';
        const table = document.querySelector('.table-responsive table');

        if (!table) {
            alert('No Pharmacy Bill records found.');
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
        const data = getPharmacyBillTableData();
        if (!data) return;

        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csv += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Pharmacy_Bill_Report_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    });

    $('#exportExcel').click(function () {
        const data = getPharmacyBillTableData();
        if (!data) return;

        let table = `
            <table border="1" style="border-collapse:collapse;">
                <tr><th colspan="${data.headers.length}" style="font-size:16px">${APP_NAME} - Pharmacy Bill Report</th></tr>
                <tr><th colspan="${data.headers.length}">Period: ${data.dateRange}</th></tr>
                <tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr>
        `;
        data.rows.forEach(row => {
            table += `<tr>${row.map(c => `<td>${c}</td>`).join('')}</tr>`;
        });
        table += `</table>`;

        const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Pharmacy_Bill_Report_${new Date().toISOString().split('T')[0]}.xls`;
        link.click();
    });

    $('#exportPdf').click(function () {
        const data = getPharmacyBillTableData();
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
        doc.text('Pharmacy Bill Report', pageWidth / 2, 65, { align: 'center' });

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

        doc.save(`Pharmacy_Bill_Report_${new Date().toISOString().split('T')[0]}.pdf`);
    });

    $('#printReport').click(function() {
        let printWindow = window.open('', '_blank');
        const data = getPharmacyBillTableData();
        if (!data) return;

        let tableHTML = '<table border="1" style="border-collapse: collapse; width: 100%;">';
        tableHTML += `<thead><tr>${data.headers.map(h => `<th style="padding:8px; background:#f2f2f2;">${h}</th>`).join('')}</tr></thead>`;
        tableHTML += '<tbody>';
        data.rows.forEach(row => {
            tableHTML += `<tr>${row.map(c => `<td style="padding:8px;">${c}</td>`).join('')}</tr>`;
        });
        tableHTML += '</tbody></table>';

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Pharmacy Bill Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 30px; max-width: 1000px; margin: 0 auto; }
                    h1, h2 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    .print-footer { text-align:center; margin-top:30px; font-size:12px; color:#777; }
                    .no-print { text-align: center; margin-top: 30px; }
                    .no-print button { margin: 0 5px; padding: 8px 16px; }
                    @media print { .no-print { display: none; } }
                </style>
            </head>
            <body>
                <h1>${APP_NAME}</h1>
                <h2>Pharmacy Bill Report</h2>
                <p style="text-align:center;">Period: ${data.dateRange}</p>
                ${tableHTML}
                <div class="print-footer">
                    <p>© ${new Date().getFullYear()} Hospital Management System</p>
                </div>
                <div class="no-print">
                    <button onclick="window.print();">Print Now</button>
                    <button onclick="window.close();">Close</button>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    });
});
