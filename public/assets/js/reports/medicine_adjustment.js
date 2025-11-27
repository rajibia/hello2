document.addEventListener('livewire:load', function () {

    const tableSelector = '#medicineAdjustmentReportTable';
    const reportTitle = 'Medicine Adjustment Report';

    function getTableData() {
        const table = document.querySelector(`${tableSelector} .table-responsive table`);
        if (!table) return null;

        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            Array.from(tr.querySelectorAll('td')).map(td => {
                td.querySelectorAll('i, svg, img, button, a').forEach(el => el.remove());
                return td.innerText.trim();
            })
        );

        const dateRange = document.querySelector('.date-range-display')?.innerText.trim() || '';
        return { headers, rows, dateRange };
    }

    function printTable() {
        const data = getTableData();
        if (!data) return alert('No records to print.');

        let tableHTML = '<table border="1" style="border-collapse:collapse;width:100%">';
        tableHTML += '<thead><tr>' + data.headers.map(h => `<th>${h}</th>`).join('') + '</tr></thead>';
        tableHTML += '<tbody>' + data.rows.map(r => `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`).join('') + '</tbody>';
        tableHTML += '</table>';

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html><head><title>${reportTitle}</title>
            <style>
                body{font-family:Arial,sans-serif;padding:30px;}
                th, td{border:1px solid #ddd;padding:8px;font-size:12px;text-align:left;}
                th{background:#f2f2f2;font-weight:bold;}
            </style>
            </head><body>
            <h2>${reportTitle}</h2>
            <p>${data.dateRange ? 'Period: ' + data.dateRange : ''}</p>
            ${tableHTML}
            <p>&copy; ${new Date().getFullYear()}</p>
            </body></html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    }

    function exportCSV() {
        const data = getTableData();
        if (!data) return alert('No records to export.');

        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csv += row.map(c => `"${c.replace(/"/g,'""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csv], {type:'text/csv'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${reportTitle.replace(/\s+/g,'_')}.csv`;
        link.click();
    }

    function exportExcel() {
        const data = getTableData();
        if (!data) return alert('No records to export.');

        let tableHTML = '<table><thead><tr>' + data.headers.map(h => `<th>${h}</th>`).join('') + '</tr></thead>';
        tableHTML += '<tbody>' + data.rows.map(r => `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`).join('') + '</tbody></table>';

        const blob = new Blob([tableHTML], {type:'application/vnd.ms-excel'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${reportTitle.replace(/\s+/g,'_')}.xls`;
        link.click();
    }

    function exportPDF() {
    const data = getTableData();
    if (!data) return alert('No records to export.');

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(14);
    doc.text(reportTitle, 15, 15);
    if(data.dateRange) doc.setFontSize(10).text(`Period: ${data.dateRange}`, 15, 22);

    doc.autoTable({
        head: [data.headers],
        body: data.rows,
        startY: 28,
        styles: { fontSize: 8 }
    });
    doc.save(`${reportTitle.replace(/\s+/g,'_')}.pdf`);
}


    // Bind buttons
    document.getElementById('printMedicineAdjustmentReport')?.addEventListener('click', printTable);
    document.getElementById('exportCSV')?.addEventListener('click', exportCSV);
    document.getElementById('exportExcel')?.addEventListener('click', exportExcel);
    document.getElementById('exportPDF')?.addEventListener('click', exportPDF);

});
