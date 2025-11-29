<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Company;
use App\Models\Patient;

class ReportsController extends AppBaseController
{
    /**
     * Display the reports dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Display the daily OPD & IPD count report.
     *
     * @return \Illuminate\View\View
     */
    public function dailyCount()
    {
        return view('reports.daily_count');
    }

    /**
     * Display the discharge report for OPD & IPD.
     *
     * @return \Illuminate\View\View
     */
    public function dischargeReport()
    {
        return view('reports.discharge');
    }
// In app/Http/Controllers/ReportsController.php (or similar)



    public function laboratoryAttendance()
    {
        // Logic to fetch and prepare attendance data (e.g., from MONTHLY REPORTS MSOFT.pdf [cite: 4, 5])
        // and return the view.
        return view('reports.laboratory_attendance');
    }

    /**
     * Display the Test Investigations Done Report page.
     */
    public function laboratoryInvestigation()
    {
        // Logic to fetch and prepare test investigation data (e.g., from MONTHLY REPORTS MSOFT.pdf [cite: 6, 7])
        // and return the view.
        return view('reports.laboratory_investigation');
    }

    /**
     * Display the OPD statement report.
     *
     * @return \Illuminate\View\View
     */
    public function opdStatementReport()
    {
        return view('reports.opd_statement');
    }
    
    /**
     * Display the Monthly Outpatient Morbidity Returns report.
     *
     * @return \Illuminate\View\View
     */
    public function monthlyOutpatientMorbidityReport()
    {
        return view('reports.monthly_outpatient_morbidity');
    }
    
    /**
     * Display the Patient Statement Report.
     *
     * @return \Illuminate\View\View
     */
    public function patientStatementReport()
    {
        return view('reports.patient_statement');
    }
    
    /**
     * Display the Transaction Report.
     *
     * @return \Illuminate\View\View
     */
    public function transactionReport()
    {
        return view('reports.transaction_report');
    }

    /**
     * Export transaction report as PDF
     */
    public function exportTransactionPDF(Request $request)
    {
        try {
            $startDate = $request->get('startDate', now()->startOfDay());
            $endDate = $request->get('endDate', now()->endOfDay());
            $transactionType = $request->get('transactionType', 'all');
            
            // Get transactions logic (simplified version)
            $transactions = [];
            $totalAmount = 0;
            
            $html = view('livewire.transaction-report-pdf', compact('transactions', 'startDate', 'endDate', 'totalAmount'))->render();
            $pdf = \PDF::loadHTML($html);
            
            return $pdf->download('transaction-report-' . now()->format('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            return back()->withError('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export Patient Statement as Excel/CSV/PDF
     */
    public function exportPatientStatementExcel(Request $request)
    {
        $params = $request->only(['startDate', 'endDate', 'patientId', 'searchTerm']);
        $rows = $this->fetchPatientStatementsForExport($params);

        if (empty($rows)) {
            return back()->withError('No records found for export');
        }

        $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $rows;
            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function collection()
            {
                return collect($this->rows);
            }

            public function headings(): array
            {
                return array_keys($this->rows[0] ?? []);
            }
        };

        return \Maatwebsite\Excel\Facades\Excel::download($export, 'patient-statement-' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportPatientStatementCsv(Request $request)
    {
        $params = $request->only(['startDate', 'endDate', 'patientId', 'searchTerm']);
        $rows = $this->fetchPatientStatementsForExport($params);

        if (empty($rows)) {
            return back()->withError('No records found for export');
        }

        $filename = 'patient-statement-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($rows) {
            $FH = fopen('php://output', 'w');
            // headers
            fputcsv($FH, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($FH, array_values($row));
            }
            fclose($FH);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPatientStatementPdf(Request $request)
    {
        $params = $request->only(['startDate', 'endDate', 'patientId', 'searchTerm']);
        $patientData = $this->fetchPatientStatementsForExport($params, $forPdf = true);

        $html = view('exports.patient_statement_pdf', [
            'patientData' => $patientData,
            'startDate' => $params['startDate'] ?? null,
            'endDate' => $params['endDate'] ?? null,
        ])->render();

        $pdf = \PDF::loadHTML($html);

        return $pdf->download('patient-statement-' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Helper: fetch patient statements for export
     */
    protected function fetchPatientStatementsForExport(array $params = [], $forPdf = false)
    {
        $startDate = $params['startDate'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $params['endDate'] ?? now()->endOfMonth()->format('Y-m-d');
        $patientId = $params['patientId'] ?? null;
        $searchTerm = $params['searchTerm'] ?? null;

        if (!$patientId) {
            $patients = \App\Models\Patient::with(['patientUser'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereHas('invoices', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('invoice_date', [$startDate, $endDate]);
                    })->orWhereHas('bills', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('bill_date', [$startDate, $endDate]);
                    });
                })
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->whereHas('patientUser', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->get();

            $rows = [];
            foreach ($patients as $p) {
                $rows[] = [
                    'Patient' => $p->patientUser->full_name ?? 'N/A',
                    'Patient ID' => $p->patient_unique_id ?? '',
                    'Email' => $p->patientUser->email ?? '',
                    'Phone' => $p->patientUser->phone ?? '',
                ];
            }

            return $rows;
        }

        // Specific patient detailed
        $patient = \App\Models\Patient::with([
            'patientUser',
            'invoices' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('invoice_date', [$startDate, $endDate])->with('invoiceItems.charge.chargeCategory');
            },
            'bills' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('bill_date', [$startDate, $endDate])->with('billItems', 'manualBillPayment');
            }
        ])->where('id', $patientId)->first();

        if (!$patient) return [];

        // Build rows for export: invoices then bills
        $rows = [];
        foreach ($patient->invoices as $invoice) {
            $rows[] = [
                'Type' => 'Invoice',
                'Reference' => $invoice->invoice_id,
                'Date' => optional($invoice->invoice_date)->format('Y-m-d') ?? $invoice->invoice_date,
                'Amount' => $invoice->amount,
                'Discount' => $invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0,
                'Total' => $invoice->amount - ($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0),
                'Status' => $invoice->status ? 'Paid' : 'Unpaid',
            ];
        }

        foreach ($patient->bills as $bill) {
            $rows[] = [
                'Type' => 'Bill',
                'Reference' => $bill->bill_id,
                'Date' => optional($bill->bill_date)->format('Y-m-d') ?? $bill->bill_date,
                'Amount' => $bill->amount,
                'Discount' => 0,
                'Total' => $bill->amount,
                'Status' => ($bill->manualBillPayment->sum('amount') ?? 0) >= $bill->amount ? 'Paid' : 'Unpaid',
            ];
        }

        return $rows;
    }
    
    /**
     * Display the OPD Balance Report.
     *
     * @return \Illuminate\View\View
     */
    public function opdBalanceReport()
    {
        return view('reports.opd_balance_report');
    }

    /**
     * Display the IPD Balance Report.
     *
     * @return \Illuminate\View\View
     */
    public function ipdBalanceReport()
    {
        return view('reports.ipd_balance_report');
    }
    
    /**
     * Display the Pharmacy Bill Report.
     *
     * @return \Illuminate\View\View
     */
    public function pharmacyBillReport()
    {
        return view('reports.pharmacy_bill_report');
    }
    
    /**
     * Display the Expenses Report.
     *
     * @return \Illuminate\View\View
     */
    public function expensesReport()
    {
        return view('reports.expenses_report');
    }
    
    /**
     * Display the Medicine Report.
     *
     * @return \Illuminate\View\View
     */
    public function medicineReport()
    {
        return view('reports.medicine');
    }
    
    /**
     * Display the Expiry Medicine Report.
     *
     * @return \Illuminate\View\View
     */
    public function expiryMedicineReport()
    {
        return view('reports.expiry_medicine');
    }
    
    /**
     * Display the Medicine Transfer Report.
     *
     * @return \Illuminate\View\View
     */
    public function medicineTransferReport()
    {
        return view('reports.medicine_transfer');
    }

    /**
     * Display the medicine adjustment report.
     *
     * @return \Illuminate\View\View
     */
    public function medicineAdjustmentReport()
    {
        return view('reports.medicine_adjustment');
    }
    
    /**
     * Display the Company Claim Report listing page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function companyClaimReport(Request $request)
    {
        $companies = Company::withCount(['patients'])
            ->orderBy('name')
            ->paginate(10);
            
        return view('reports.company_claim', compact('companies'));
    }
    
    /**
     * Display the detailed Company Claim Report for a specific company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function companyClaimDetail(Request $request, Company $company)
    {
        // With Livewire, we only need to pass the company to the view
        // All filtering and data loading is handled by the Livewire component
        return view('reports.company_claim_detail', compact('company'));
    }
    
    /**
     * Display the Purchase Report.
     *
     * @return \Illuminate\View\View
     */
    public function purchaseReport()
    {
        return view('reports.purchase_report');
    }
    
    /**
     * Display the Stock Report.
     *
     * @return \Illuminate\View\View
     */
    public function stockReport()
    {
        return view('reports.stock_report');
    }
}
