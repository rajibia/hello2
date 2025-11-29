/**
 * Unified Report Export Utility
 * Provides PDF, CSV, Excel export functionality for all reports
 * Works without page refresh using DataTables Buttons
 */

const ReportExporter = {
    /**
     * Initialize export buttons for a DataTable
     * @param {jQuery} tableElement - jQuery wrapped table element
     * @param {object} options - Configuration options
     */
    initializeExports: function(tableElement, options = {}) {
        const defaults = {
            excludeColumns: [':last-child'], // Exclude Actions column by default
            exportPdf: true,
            exportCsv: true,
            exportExcel: true,
            reportTitle: 'Report',
            fileName: 'report'
        };

        const config = Object.assign(defaults, options);

        // Destroy existing DataTable instance if present
        if ($.fn.DataTable.isDataTable(tableElement)) {
            tableElement.DataTable().destroy();
        }

        // Initialize DataTable with export buttons
        const dataTable = tableElement.DataTable({
            dom: 'Bfrtip',
            buttons: this._buildButtons(config),
            pageLength: 25,
            order: [[0, 'desc']],
            columnDefs: [
                { targets: '_all', defaultContent: '' }
            ],
            searching: true,
            destroy: true,
            responsive: true
        });

        // Connect external export buttons
        this._connectExportButtons(dataTable, config);

        return dataTable;
    },

    /**
     * Build DataTables button configuration
     * @private
     */
    _buildButtons: function(config) {
        const buttons = [];
        // Build exportOptions.columns in a robust way:
        // - If excludeColumns contains selector strings (e.g. ':last-child'), convert to a ':not(...)' selector
        // - If excludeColumns contains numeric indexes, pass array of indexes
        let exportOptions = {};
        try {
            if (Array.isArray(config.excludeColumns) && config.excludeColumns.length > 0) {
                const areAllNumbers = config.excludeColumns.every(c => Number.isInteger(c));
                if (areAllNumbers) {
                    // If array of indexes, include all columns except those indexes will be handled by caller
                    // Here we'll compute full column indexes is not trivial without table API; fallback to including all columns
                    exportOptions.columns = config.includeOnlyColumns || null;
                } else {
                    // Assume selectors; create a :not(...) selector that excludes listed selectors
                    const selector = config.excludeColumns.map(s => s.trim()).join('),:not(');
                    exportOptions.columns = `:not(${selector})`;
                }
            }
        } catch (err) {
            console.warn('Failed to compute exportOptions.columns, falling back to default', err);
            exportOptions = {};
        }

        if (config.exportExcel) {
            buttons.push({
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'buttons-excel d-none',
                exportOptions: exportOptions,
                filename: config.fileName + '_' + new Date().toISOString().split('T')[0]
            });
        }

        if (config.exportCsv) {
            buttons.push({
                extend: 'csvHtml5',
                text: 'CSV',
                className: 'buttons-csv d-none',
                exportOptions: exportOptions,
                filename: config.fileName + '_' + new Date().toISOString().split('T')[0]
            });
        }

        if (config.exportPdf) {
            buttons.push({
                extend: 'pdfHtml5',
                text: 'PDF',
            className: 'buttons-pdf d-none',
                orientation: 'landscape',
                exportOptions: exportOptions,
                filename: config.fileName + '_' + new Date().toISOString().split('T')[0],
                customize: function(doc) {
                    // Remove last column from PDF
                    if (doc.content[1] && doc.content[1].table) {
                        doc.content[1].table.body.forEach(row => {
                            if (row.length > 0) row.pop();
                        });
                    }
                }
            });
        }

        return buttons;
    },

    /**
     * Connect external export buttons to DataTable buttons
     * @private
     */
    _connectExportButtons: function(dataTable, config) {
        // Store reference for later use
        window._lastDataTable = dataTable;
        window._lastConfig = config;

        try {
            const excelBtn = document.getElementById('exportExcel');
            if (excelBtn && config.exportExcel) {
                if (excelBtn._exportClick) excelBtn.removeEventListener('click', excelBtn._exportClick);
                excelBtn._exportClick = () => {
                    try {
                        const btn = dataTable.button('.buttons-excel');
                        if (btn && btn.length) btn.trigger();
                        else console.warn('Excel button API not available');
                    } catch (e) { console.error('Error triggering excel export', e); }
                };
                excelBtn.addEventListener('click', excelBtn._exportClick);
            }
        } catch (e) { console.error('Error wiring excel export button', e); }

        // CSV export
        try {
            const csvBtn = document.getElementById('exportCsv');
            if (csvBtn && config.exportCsv) {
                if (csvBtn._exportClick) csvBtn.removeEventListener('click', csvBtn._exportClick);
                csvBtn._exportClick = () => {
                    try {
                        const btn = dataTable.button('.buttons-csv');
                        if (btn && btn.length) btn.trigger();
                        else console.warn('CSV button API not available');
                    } catch (e) { console.error('Error triggering csv export', e); }
                };
                csvBtn.addEventListener('click', csvBtn._exportClick);
            }
        } catch (e) { console.error('Error wiring csv export button', e); }

        // PDF export
        try {
            const pdfBtn = document.getElementById('exportPdf');
            if (pdfBtn && config.exportPdf) {
                if (pdfBtn._exportClick) pdfBtn.removeEventListener('click', pdfBtn._exportClick);
                pdfBtn._exportClick = () => {
                    try {
                        const btn = dataTable.button('.buttons-pdf');
                        if (btn && btn.length) btn.trigger();
                        else console.warn('PDF button API not available');
                    } catch (e) { console.error('Error triggering pdf export', e); }
                };
                pdfBtn.addEventListener('click', pdfBtn._exportClick);
            }
        } catch (e) { console.error('Error wiring pdf export button', e); }
    },

    /**
     * Initialize live search
     * @param {jQuery} dataTable - DataTable instance
     * @param {string} searchInputId - ID of search input element
     */
    initializeLiveSearch: function(dataTable, searchInputId = 'liveSearch') {
        const searchInput = document.getElementById(searchInputId);
        if (searchInput && dataTable) {
            searchInput.removeEventListener('input', searchInput._searchListener);
            searchInput._searchListener = function() {
                dataTable.search(this.value).draw();
            };
            searchInput.addEventListener('input', searchInput._searchListener);
        }
    },

    /**
     * Setup print functionality
     * @param {string} buttonId - ID of print button
     * @param {string} tableSelector - CSS selector for table
     * @param {string} reportTitle - Title for print
     */
    initializePrint: function(buttonId = 'printReport', tableSelector = 'table', reportTitle = 'Report') {
        const printBtn = document.getElementById(buttonId);
        if (!printBtn) return;

        printBtn.removeEventListener('click', printBtn._printClick);
        printBtn._printClick = function() {
            const tableEl = document.querySelector(tableSelector);
            if (!tableEl) {
                alert('No data to print');
                return;
            }

            // Clone table and remove action columns and icons
            const temp = tableEl.cloneNode(true);
            temp.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
            temp.querySelectorAll('i, svg, img, button, .btn, .avatar').forEach(el => el.remove());

            // Create print window
            const printWindow = window.open('', '_blank');
            const html = `
                <html>
                <head>
                    <title>${reportTitle}</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
                    <style>
                        @media print {
                            body { padding: 20px; font-size: 12px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #ddd; padding: 8px; }
                            th { background: #f5f5f5; font-weight: bold; }
                        }
                    </style>
                </head>
                <body>
                    <div class="text-center mb-4">
                        <h2>${reportTitle}</h2>
                        <p class="text-muted">${new Date().toLocaleString()}</p>
                    </div>
                    ${temp.outerHTML}
                    <div class="text-center mt-4 text-muted small">
                        <p>Generated on ${new Date().toLocaleString()}</p>
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.focus();
            
            setTimeout(() => {
                printWindow.print();
            }, 250);
        };

        printBtn.addEventListener('click', printBtn._printClick);
    }
    ,

    /**
     * Initialize exports when a table appears inside a wrapper.
     * Handles Livewire-rendered tables by using MutationObserver and Livewire events.
     * @param {string} wrapperSelector - CSS selector for the wrapper that will contain the table
     * @param {object} options - same options as initializeExports
     */
    initializeOnWrapper: function(wrapperSelector, options = {}) {
        const tryInit = (wrapper) => {
            if (!wrapper) return false;
            const tableEl = wrapper.querySelector('table');
            if (tableEl) {
                try {
                    const dt = this.initializeExports($(tableEl), options);
                    this.initializeLiveSearch(dt);
                    return true;
                } catch (e) {
                    console.warn('initializeOnWrapper: initializeExports failed', e);
                    return false;
                }
            }
            return false;
        };

        const wrapper = document.querySelector(wrapperSelector);
        if (wrapper) {
            // If table already present try initialize
            if (tryInit(wrapper)) return;

            // Observe wrapper for added tables
            const observer = new MutationObserver((mutations, obs) => {
                if (tryInit(wrapper)) {
                    obs.disconnect();
                }
            });
            observer.observe(wrapper, { childList: true, subtree: true });
        }

        // Also listen for Livewire events as a fallback
        const livewireHandler = () => {
            const w = document.querySelector(wrapperSelector);
            if (w && tryInit(w)) {
                window.removeEventListener('livewire:update', livewireHandler);
                window.removeEventListener('livewire:load', livewireHandler);
            }
        };

        window.addEventListener('livewire:load', livewireHandler);
        window.addEventListener('livewire:update', livewireHandler);

        // Final safety: interval fallback
        let attempts = 0;
        const interval = setInterval(() => {
            const w = document.querySelector(wrapperSelector);
            if (w && tryInit(w)) {
                clearInterval(interval);
            }
            if (attempts++ > 30) clearInterval(interval);
        }, 500);
    }
};

// Auto-wire export buttons whenever a DataTable is detected
(function() {
    function wireExportButtons() {
        if (!window._lastDataTable) return;
        
        const dt = window._lastDataTable;
        const cfg = window._lastConfig || {};
        
        try {
            const excelBtn = document.getElementById('exportExcel');
            if (excelBtn && !excelBtn.dataset.wired) {
                excelBtn.removeEventListener('click', excelBtn._autoClick);
                excelBtn._autoClick = () => {
                    const btn = dt.button ? dt.button('.buttons-excel') : null;
                    if (btn && btn.length) btn.trigger();
                };
                excelBtn.addEventListener('click', excelBtn._autoClick);
                excelBtn.dataset.wired = 'true';
            }
        } catch(e) {}

        try {
            const csvBtn = document.getElementById('exportCsv');
            if (csvBtn && !csvBtn.dataset.wired) {
                csvBtn.removeEventListener('click', csvBtn._autoClick);
                csvBtn._autoClick = () => {
                    const btn = dt.button ? dt.button('.buttons-csv') : null;
                    if (btn && btn.length) btn.trigger();
                };
                csvBtn.addEventListener('click', csvBtn._autoClick);
                csvBtn.dataset.wired = 'true';
            }
        } catch(e) {}

        try {
            const pdfBtn = document.getElementById('exportPdf');
            if (pdfBtn && !pdfBtn.dataset.wired) {
                pdfBtn.removeEventListener('click', pdfBtn._autoClick);
                pdfBtn._autoClick = () => {
                    const btn = dt.button ? dt.button('.buttons-pdf') : null;
                    if (btn && btn.length) btn.trigger();
                };
                pdfBtn.addEventListener('click', pdfBtn._autoClick);
                pdfBtn.dataset.wired = 'true';
            }
        } catch(e) {}
    }
    
    // Wire on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', wireExportButtons);
    } else {
        wireExportButtons();
    }
    
    // Re-wire whenever Livewire updates
    window.addEventListener('livewire:update', () => setTimeout(wireExportButtons, 100));
    window.addEventListener('livewire:updated', () => setTimeout(wireExportButtons, 100));
    window.addEventListener('livewire:load', () => setTimeout(wireExportButtons, 100));
    
    // Continuously try to wire (safety net)
    setInterval(wireExportButtons, 2000);
})();

