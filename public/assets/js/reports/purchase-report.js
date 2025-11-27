document.addEventListener('livewire:load', function () {

    const tableSelector = '#purchaseReportTable'; 
    const reportTitle = 'Purchase Report';

    
    function getTableData(selector) {
        const table = document.querySelector(`${selector} table`);
        if (!table) return null;

        // Headers excluding Actions
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map((th, index) => ({ text: th.innerText.trim(), index }))
            .filter(h => h.text.toLowerCase() !== 'actions');

        // Rows
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
            headers.map(h => {
                const td = tr.querySelectorAll('td')[h.index];
                if (!td) return '';
                td.querySelectorAll('i, svg, img, button, a').forEach(el => el.remove());
                return td.innerText.trim();
            })
        );

        return { headers: headers.map(h => h.text), rows };
    }

    // Print
    function printTable() {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found.');

        let tableHTML = `<table class="print-table">
            <thead><tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr></thead>
            <tbody>${data.rows.map(r => `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`).join('')}</tbody>
        </table>`;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>${reportTitle}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .print-header { text-align: center; margin-bottom: 20px; }
                    .print-header h2 { margin: 0; }
                    table.print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table.print-table th, table.print-table td { border: 1px solid #ddd; padding: 8px; font-size: 12px; text-align: left; }
                    table.print-table th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>${reportTitle}</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                ${tableHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    }

    // Export CSV
    function exportCSV() {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found.');

        let csv = data.headers.join(',') + '\n';
        data.rows.forEach(r => {
            csv += r.map(c => `"${c.replace(/"/g,'""')}"`).join(',') + '\n';
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = reportTitle.replace(/\s+/g,'_') + '.csv';
        link.click();
    }

    // Export Excel
    function exportExcel() {
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found.');

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
        link.download = reportTitle.replace(/\s+/g,'_') + '.xls';
        link.click();
    }

    // Export PDF
    function exportPDF() {
        if (!window.jspdf || !window.jspdf.jsPDF) return alert('jsPDF not loaded.');
        const data = getTableData(tableSelector);
        if (!data) return alert('No records found.');

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFontSize(14);
        doc.text(reportTitle, 15, 15);
        doc.setFontSize(10);
        doc.autoTable({
            head: [data.headers],
            body: data.rows,
            startY: 25,
            styles: { fontSize: 8 }
        });
        doc.save(reportTitle.replace(/\s+/g,'_') + '.pdf');
    }

    // Button bindings (make sure your buttons exist in the DOM)
    const printBtn = document.getElementById('printReport');
    if (printBtn) printBtn.addEventListener('click', printTable);

    const csvBtn = document.getElementById('exportCSV');
    if (csvBtn) csvBtn.addEventListener('click', exportCSV);

    const excelBtn = document.getElementById('exportExcel');
    if (excelBtn) excelBtn.addEventListener('click', exportExcel);

    const pdfBtn = document.getElementById('exportPDF');
    if (pdfBtn) pdfBtn.addEventListener('click', exportPDF);

});
