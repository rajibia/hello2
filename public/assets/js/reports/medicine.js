// assets/js/reports/medicine.js

document.addEventListener('DOMContentLoaded', function () {
    // Helper function to get table data
    function getMedicineTableData() {
        const table = document.querySelector('#medicineReportTable .table-responsive table');
        if (!table) {
            alert('No medicine records found.');
            return null;
        }

        // Date range display (if exists)
        const dateRangeElem = document.querySelector('.date-range-display');
        const dateRange = dateRangeElem ? dateRangeElem.innerText.trim() : 'All Dates';

        // Get table headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());

        // Get table rows
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => {
            return Array.from(tr.querySelectorAll('td')).map(td => {
                // Remove icons/buttons/links
                td.querySelectorAll('i, svg, img, button, a').forEach(el => el.remove());
                return td.innerText.trim();
            });
        });

        return { headers, rows, dateRange };
    }

    // Export CSV
    const exportCsvBtn = document.getElementById('exportCsv');
    if (exportCsvBtn) {
        exportCsvBtn.addEventListener('click', function () {
            const data = getMedicineTableData();
            if (!data) return;

            let csv = data.headers.join(',') + '\n';
            data.rows.forEach(row => {
                csv += row.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `Medicine_Report_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        });
    }

    // Export Excel
    const exportExcelBtn = document.getElementById('exportExcel');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function () {
            const data = getMedicineTableData();
            if (!data) return;

            let table = `<table border="1">
                <tr><th colspan="${data.headers.length}" style="text-align:center;font-size:16px">Medicine Report</th></tr>
                <tr><th colspan="${data.headers.length}" style="text-align:center;">Period: ${data.dateRange}</th></tr>
                <tr>${data.headers.map(h => `<th>${h}</th>`).join('')}</tr>`;

            data.rows.forEach(row => {
                table += `<tr>${row.map(c => `<td>${c}</td>`).join('')}</tr>`;
            });
            table += `</table>`;

            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `Medicine_Report_${new Date().toISOString().split('T')[0]}.xls`;
            link.click();
        });
    }

    // Export PDF
    const exportPdfBtn = document.getElementById('exportPdf');
    if (exportPdfBtn) {
        exportPdfBtn.addEventListener('click', function () {
            const data = getMedicineTableData();
            if (!data) return;

            if (!window.jspdf || !window.jspdf.jsPDF) {
                alert('jsPDF not loaded properly.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'pt', format: 'a4' });
            const pageWidth = doc.internal.pageSize.getWidth();

            // Header
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            doc.text('Medicine Report', pageWidth / 2, 40, { align: 'center' });

            doc.setFontSize(12);
            doc.text(`Period: ${data.dateRange}`, pageWidth / 2, 60, { align: 'center' });
            doc.text(`Generated on: ${new Date().toLocaleString()}`, pageWidth / 2, 75, { align: 'center' });

            if (!doc.autoTable) {
                alert('jsPDF AutoTable plugin not loaded.');
                return;
            }

            // Table
            doc.autoTable({
                startY: 100,
                head: [data.headers],
                body: data.rows,
                theme: 'grid',
                styles: { fontSize: 8, cellPadding: 4 },
                headStyles: { fillColor: [41, 128, 185], textColor: 255, halign: 'center' },
                alternateRowStyles: { fillColor: [245, 245, 245] }
            });

            // Footer
            const finalY = doc.lastAutoTable.finalY + 20;
            doc.setFontSize(9);
            doc.setTextColor(100);
            doc.text(`© ${new Date().getFullYear()} Hospital Management System`, pageWidth / 2, finalY, { align: 'center' });

            doc.save(`Medicine_Report_${new Date().toISOString().split('T')[0]}.pdf`);
        });
    }
});
