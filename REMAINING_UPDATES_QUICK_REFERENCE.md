# Quick Reference: Remaining Report Updates

## How to Update Remaining Reports

Each remaining report needs the `@section('page_scripts')` block replaced with the unified export utility pattern.

### Template for Each Update

```blade
@section('page_scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="{{ asset('assets/js/reports/export-utility.js') }}"></script>

<script>
let table = null;

function initializeDataTable() {
    const tableEl = document.querySelector('#WRAPPER_ID_HERE table');
    if (!tableEl) return;

    if (table) { table.destroy(); table = null; }

    setTimeout(() => {
        try {
            table = ReportExporter.initializeExports($(tableEl), {
                excludeColumns: [':last-child'],
                reportTitle: 'REPORT_TITLE_HERE',
                fileName: 'FILE_NAME_HERE'
            });

            ReportExporter.initializeLiveSearch(table);
        } catch (e) {
            console.warn('DataTable init error:', e);
        }
    }, 100);
}

// Initialize on all events
document.addEventListener('DOMContentLoaded', initializeDataTable);
document.addEventListener('livewire:load', initializeDataTable);
document.addEventListener('livewire:update', initializeDataTable);

// Fallback retries (for slower Livewire loads)
let attempts = 0;
const interval = setInterval(() => {
    if (attempts++ > 15 || table) clearInterval(interval);
    else initializeDataTable();
}, 600);

// Print functionality
ReportExporter.initializePrint('printReport', '#WRAPPER_ID_HERE table', 'REPORT_TITLE_HERE');
</script>
@endsection
```

## Reports to Update (With Specific Values)

### 1. IPD Balance Report
- **File**: `resources/views/reports/ipd_balance_report.blade.php`
- **WRAPPER_ID_HERE**: `ipdBalanceWrapper`
- **REPORT_TITLE_HERE**: `IPD Balance Report`
- **FILE_NAME_HERE**: `ipd_balance_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.button(0).trigger());`

### 2. Medicine Transfer Report
- **File**: `resources/views/reports/medicine_transfer.blade.php`
- **WRAPPER_ID_HERE**: `transferReportWrapper`
- **REPORT_TITLE_HERE**: `Medicine Transfer Report`
- **FILE_NAME_HERE**: `medicine_transfer_report`
- **Current Line**: Search for `$('#exportPdf').off('click').on('click', () => table.buttons('.buttons-pdf').trigger());`

### 3. Patient Statement Report
- **File**: `resources/views/reports/patient_statement.blade.php`
- **WRAPPER_ID_HERE**: `patientStatementWrapper`
- **REPORT_TITLE_HERE**: `Patient Statement Report`
- **FILE_NAME_HERE**: `patient_statement_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());`
- **⚠️ NOTE**: File has duplicate `@section('page_scripts')` blocks - delete one before updating

### 4. Purchase Report
- **File**: `resources/views/reports/purchase_report.blade.php`
- **WRAPPER_ID_HERE**: `purchaseReportWrapper`
- **REPORT_TITLE_HERE**: `Purchase Report`
- **FILE_NAME_HERE**: `purchase_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());`

### 5. Stock Report
- **File**: `resources/views/reports/stock_report.blade.php`
- **WRAPPER_ID_HERE**: `stockReportWrapper`
- **REPORT_TITLE_HERE**: `Stock Report`
- **FILE_NAME_HERE**: `stock_report`
- **⚠️ SPECIAL**: No @section('page_scripts') currently exists. Need to check if exports are in Livewire component.

### 6. Pharmacy Bill Report
- **File**: `resources/views/reports/pharmacy_bill_report.blade.php`
- **WRAPPER_ID_HERE**: `pharmacyBillWrapper`
- **REPORT_TITLE_HERE**: `Pharmacy Bill Report`
- **FILE_NAME_HERE**: `pharmacy_bill_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.buttons(0).trigger());`

### 7. Transaction Report
- **File**: `resources/views/reports/transaction_report.blade.php`
- **WRAPPER_ID_HERE**: `transactionReportWrapper`
- **REPORT_TITLE_HERE**: `Transaction Report`
- **FILE_NAME_HERE**: `transaction_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', function () {`

### 8. Medicine Adjustment Report
- **File**: `resources/views/reports/medicine_adjustment.blade.php`
- **WRAPPER_ID_HERE**: `medicineAdjustmentReportTable`
- **REPORT_TITLE_HERE**: `Medicine Adjustment Report`
- **FILE_NAME_HERE**: `medicine_adjustment_report`
- **Print Button**: `#printMedicineAdjustmentReport` (non-standard name)
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());`

### 9. Expiry Medicine Report
- **File**: `resources/views/reports/expiry_medicine.blade.php`
- **WRAPPER_ID_HERE**: `expiryReportWrapper`
- **REPORT_TITLE_HERE**: `Expiry Medicine Report`
- **FILE_NAME_HERE**: `expiry_medicine_report`
- **Current Line**: Search for `$('#exportExcel').off('click').on('click', () => table.buttons('.buttons-excel').trigger());`

## Already Updated Reports ✅

These reports have already been updated and are working:
1. ✅ OPD Statement Report
2. ✅ Discharge Report
3. ✅ Medicine Report
4. ✅ Expenses Report
5. ✅ Daily OPD & IPD Count Report
6. ✅ OPD Balance Report
7. ✅ Monthly Outpatient Morbidity Report

## Testing After Update

For each updated report:
1. Load the report page
2. Click Export PDF - should download without page refresh ✓
3. Click Export CSV - should download without page refresh ✓
4. Click Export Excel - should download without page refresh ✓
5. Try live search - should filter table instantly ✓
6. Click Print - should open print preview ✓
7. Change date range or filters - exports should still work ✓

---
**Status**: 7/15 reports updated, 8/15 ready for update, 1/15 needs special handling
