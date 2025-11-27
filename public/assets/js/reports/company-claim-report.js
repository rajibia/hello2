document.addEventListener('DOMContentLoaded', function () {

    // Helper: Get table data excluding Actions column
    function getTableData(tableSelector) {
        const table = document.querySelector(`${tableSelector} table`);
        if (!table) return null;

        // Map headers and exclude Actions
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map((th, index) => ({ text: th.innerText.trim(), index }))
            .filter(h => h.text.toLowerCase() !== 'actions');

        // Get rows based on filtered headers
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            headers.map(h => {
                const td = tr.querySelectorAll('td')[h.index];
                if (!td) return '';
                // Remove icons/buttons/images/links
                td.querySelectorAll('i, svg, img, button, a').forEach(el => el.remove());
                return td.innerText.trim();
            })
        );

        return { headers: headers.map(h => h.text), rows };
    }

    // Print table
    function printTable(tableSelector, reportTitle) {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found to print.');

        const tableHTML = data.rows.length
            ? `<table border="1" style="border-collapse:collapse;width:100%">
                    <thead><tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr></thead>
                    <tbody>${data.rows.map(r => `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`).join('')}</tbody>
               </table>`
            : '<p>No records found</p>';

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>${reportTitle}</title>
                <style>
                    body{font-family:Arial,sans-serif;padding:30px;max-width:1000px;margin:0 auto;}
                    table{width:100%;border-collapse:collapse;margin-top:20px;margin-bottom:30px;}
                    th,td{border:1px solid #ddd;padding:8px;font-size:12px;text-align:left;}
                    th{background-color:#f2f2f2;font-weight:bold;}
                    .print-header{text-align:center;margin-bottom:30px;}
                    .print-footer{text-align:center;margin-top:30px;font-size:12px;color:#777;}
                    .no-print{display:none !important;}
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>${document.querySelector('meta[name="app-name"]')?.content || 'Hospital Management System'}</h1>
                    <h2>${reportTitle}</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                ${tableHTML}
                <div class="print-footer">&copy; ${new Date().getFullYear()} Hospital Management System</div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    }

    // Export CSV
    function exportCSV(tableSelector, reportTitle) {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found to export.');

        let csvContent = data.headers.join(',') + '\n';
        data.rows.forEach(row => {
            csvContent += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${reportTitle.replace(/\s+/g, '_')}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Export Excel
    function exportExcel(tableSelector, reportTitle) {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found to export.');

        let tableHTML = '<table><thead><tr>';
        data.headers.forEach(h => tableHTML += `<th>${h}</th>`);
        tableHTML += '</tr></thead><tbody>';
        data.rows.forEach(r => {
            tableHTML += '<tr>';
            r.forEach(c => tableHTML += `<td>${c}</td>`);
            tableHTML += '</tr>';
        });
        tableHTML += '</tbody></table>';

        const blob = new Blob([tableHTML], { type: 'application/vnd.ms-excel' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${reportTitle.replace(/\s+/g, '_')}.xls`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

function exportPDF(tableSelector, reportTitle) {
    // Check if UMD jsPDF loaded
    if (!window.jspdf || !window.jspdf.jsPDF || !window.jspdf.AutoTable) {
        return alert('jsPDF or AutoTable not loaded.');
    }

    const data = getTableData(tableSelector);
    if (!data) return alert('No records found to export.');

    const { jsPDF } = window.jspdf; // UMD style
    const doc = new jsPDF();

    doc.setFontSize(14);
    doc.text(reportTitle, 15, 15);
    doc.setFontSize(10);

    // AutoTable
    doc.autoTable({
        head: [data.headers],
        body: data.rows,
        startY: 25,
        styles: { fontSize: 8 }
    });

    doc.save(`${reportTitle.replace(/\s+/g, '_')}.pdf`);
}

    // Bind buttons
    const tableSelector = '#companyClaimReportTable';
    const reportTitle = 'Company Claim Report';

    const printBtn = document.getElementById('printReport');
    if (printBtn) printBtn.addEventListener('click', () => printTable(tableSelector, reportTitle));

    const csvBtn = document.getElementById('exportCSV');
    if (csvBtn) csvBtn.addEventListener('click', () => exportCSV(tableSelector, reportTitle));

    const excelBtn = document.getElementById('exportExcel');
    if (excelBtn) excelBtn.addEventListener('click', () => exportExcel(tableSelector, reportTitle));

    const pdfBtn = document.getElementById('exportPDF');
    if (pdfBtn) pdfBtn.addEventListener('click', () => exportPDF(tableSelector, reportTitle));

});
