$(document).ready(function () {

    function getIpdBalanceTableData() {
        const dateRange = $('.date-range-display').text().trim() || 'All Dates';
        const table = document.querySelector('.table-responsive table');

        if (!table) {
            alert('No IPD balance records found.');
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
        const data = getIpdBalanceTableData();
        if (!data) return;

        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csv += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `IPD_Balance_Report_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    });

    $('#exportExcel').click(function () {
        const data = getIpdBalanceTableData();
        if (!data) return;

        let table = `<table border="1">
            <tr><th colspan="${data.headers.length}" style="font-size:16px">${APP_NAME} - IPD Balance Report</th></tr>
            <tr><th colspan="${data.headers.length}">Period: ${data.dateRange}</th></tr>
            <tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
        data.rows.forEach(row => {
            table += `<tr>${row.map(c => `<td>${c}</td>`).join('')}</tr>`;
        });
        table += `</table>`;

        const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `IPD_Balance_Report_${new Date().toISOString().split('T')[0]}.xls`;
        link.click();
    });

    $('#exportPdf').click(function () {
        const data = getIpdBalanceTableData();
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
        doc.text('IPD Balance Report', pageWidth / 2, 65, { align: 'center' });

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

        doc.save(`IPD_Balance_Report_${new Date().toISOString().split('T')[0]}.pdf`);
    });

});
