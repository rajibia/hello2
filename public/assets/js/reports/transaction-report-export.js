$(document).ready(function () {

    function getTransactionTableData() {
        const dateRange = $('.date-range-display').text().trim() || 'All Dates';
        const table = document.querySelector('.table-row-dashed');
        if (!table) {
            alert('No transaction records found.');
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

        const totalAmountText = document.querySelector('.card-header .badge-success')?.textContent || '';

        return { headers, rows, dateRange, totalAmountText };
    }

    $('#exportCsv').click(function () {
        const data = getTransactionTableData();
        if (!data) return;
        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csv += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Transaction_Report_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    });

    $('#exportExcel').click(function () {
        const data = getTransactionTableData();
        if (!data) return;
        let tableHTML = `
            <table border="1">
                <tr><th colspan="${data.headers.length}" style="font-size:16px">${APP_NAME} - Transaction Report</th></tr>
                <tr><th colspan="${data.headers.length}">Period: ${data.dateRange}</th></tr>
                <tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr>
        `;
        data.rows.forEach(row => {
            tableHTML += `<tr>${row.map(c => `<td>${c}</td>`).join('')}</tr>`;
        });
        tableHTML += `<tr><td colspan="${data.headers.length-1}" style="text-align:right">Total Amount:</td><td>${data.totalAmountText}</td></tr>`;
        tableHTML += `</table>`;
        const blob = new Blob([tableHTML], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Transaction_Report_${new Date().toISOString().split('T')[0]}.xls`;
        link.click();
    });

    $('#exportPdf').click(function () {
        const data = getTransactionTableData();
        if (!data) return;
        if (!window.jspdf || !window.jspdf.jsPDF) { alert('jsPDF not loaded properly.'); return; }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'pt', format: 'a4' });
        const pageWidth = doc.internal.pageSize.getWidth();
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(16);
        doc.text(APP_NAME, pageWidth / 2, 40, { align: 'center' });
        doc.setFontSize(14);
        doc.text('Transaction Report', pageWidth / 2, 60, { align: 'center' });
        doc.setFontSize(10);
        doc.text(`Period: ${data.dateRange}`, pageWidth / 2, 75, { align: 'center' });
        doc.text(`Generated on: ${new Date().toLocaleString()}`, pageWidth / 2, 90, { align: 'center' });
        if (!doc.autoTable) { alert('jsPDF AutoTable plugin not loaded.'); return; }
        doc.autoTable({
            startY: 110,
            head: [data.headers],
            body: data.rows,
            theme: 'grid',
            styles: { fontSize: 8, cellPadding: 4 },
            headStyles: { fillColor: [41, 128, 185], textColor: 255, halign: 'center' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });
        const finalY = doc.lastAutoTable.finalY + 20;
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text(`Total Amount: ${data.totalAmountText}`, pageWidth / 2, finalY, { align: 'center' });
        doc.text(`© ${new Date().getFullYear()} ${APP_NAME} | Hospital Management System`, pageWidth / 2, finalY + 15, { align: 'center' });
        doc.save(`Transaction_Report_${new Date().toISOString().split('T')[0]}.pdf`);
    });

    $('#printReport').click(function () {
        const data = getTransactionTableData();
        if (!data) return;
        const printWindow = window.open('', '_blank');
        let tableHTML = '<table border="1" cellpadding="5" cellspacing="0" width="100%"><thead><tr>';
        data.headers.forEach(h => { tableHTML += `<th>${h}</th>`; });
        tableHTML += '</tr></thead><tbody>';
        data.rows.forEach(row => {
            tableHTML += '<tr>' + row.map(c => `<td>${c}</td>`).join('') + '</tr>';
        });
        tableHTML += `<tr><td colspan="${data.headers.length-1}" style="text-align:right">Total Amount:</td><td>${data.totalAmountText}</td></tr>`;
        tableHTML += '</tbody></table>';
        printWindow.document.write(`
            <html>
            <head>
                <title>Transaction Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    h2, h3 { text-align: center; margin: 0; padding: 0; }
                    p { text-align: center; color: #555; }
                </style>
            </head>
            <body>
                <h2>${APP_NAME}</h2>
                <h3>Transaction Report</h3>
                <p>Period: ${data.dateRange}</p>
                ${tableHTML}
                <p style="margin-top:20px;">© ${new Date().getFullYear()} ${APP_NAME} | Hospital Management System</p>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

});
