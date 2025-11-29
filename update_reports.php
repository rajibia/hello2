<?php
// Batch update reports to use unified export utility

$reports = [
    [
        'file' => 'ipd_balance_report.blade.php',
        'wrapper_id' => 'ipdBalanceWrapper',
        'table_selector' => 'table',
        'title' => 'IPD Balance Report',
        'filename' => 'ipd_balance_report'
    ],
    [
        'file' => 'monthly_outpatient_morbidity.blade.php',
        'wrapper_id' => 'morbidityReportWrapper',
        'table_selector' => 'table',
        'title' => 'Monthly Outpatient Morbidity Returns',
        'filename' => 'monthly_outpatient_morbidity'
    ],
    [
        'file' => 'medicine_transfer.blade.php',
        'wrapper_id' => 'medicineTransferWrapper',
        'table_selector' => 'table',
        'title' => 'Medicine Transfer Report',
        'filename' => 'medicine_transfer_report'
    ],
    [
        'file' => 'patient_statement.blade.php',
        'wrapper_id' => 'patientStatementWrapper',
        'table_selector' => 'table',
        'title' => 'Patient Statement Report',
        'filename' => 'patient_statement_report'
    ],
    [
        'file' => 'purchase_report.blade.php',
        'wrapper_id' => 'purchaseReportWrapper',
        'table_selector' => 'table',
        'title' => 'Purchase Report',
        'filename' => 'purchase_report'
    ],
    [
        'file' => 'stock_report.blade.php',
        'wrapper_id' => 'stockReportWrapper',
        'table_selector' => 'table',
        'title' => 'Stock Report',
        'filename' => 'stock_report'
    ],
    [
        'file' => 'pharmacy_bill_report.blade.php',
        'wrapper_id' => 'pharmacyBillWrapper',
        'table_selector' => 'table',
        'title' => 'Pharmacy Bill Report',
        'filename' => 'pharmacy_bill_report'
    ],
    [
        'file' => 'transaction_report.blade.php',
        'wrapper_id' => 'transactionReportWrapper',
        'table_selector' => 'table',
        'title' => 'Transaction Report',
        'filename' => 'transaction_report'
    ],
    [
        'file' => 'medicine_adjustment.blade.php',
        'wrapper_id' => 'medicineAdjustmentWrapper',
        'table_selector' => 'table',
        'title' => 'Medicine Adjustment Report',
        'filename' => 'medicine_adjustment_report'
    ],
    [
        'file' => 'expiry_medicine.blade.php',
        'wrapper_id' => 'expiryMedicineWrapper',
        'table_selector' => 'table',
        'title' => 'Expiry Medicine Report',
        'filename' => 'expiry_medicine_report'
    ]
];

foreach ($reports as $report) {
    echo "Updating: " . $report['file'] . "\n";
}

echo "\nAll reports listed. Ready for bulk updates.\n";
?>
