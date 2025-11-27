<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Company;
use App\Models\Patient;
use App\Models\LabVisit;
use App\Models\LabTest;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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





// app/Http/Controllers/ReportsController.php



public function labMonthly(Request $request)
{
    // Get month input safely
    $monthInput = $request->input('month'); // e.g., "2025-04"
    
    if ($monthInput && preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
        
        $startDate = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
    } else {
        // fallback to current month
        $startDate = now()->startOfMonth();
    }

    $endDate = $startDate->copy()->endOfMonth();

    // === Attendance ===
    $attendance = \App\Models\LabVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->with('company')
        ->get()
        ->groupBy('company_id')
        ->map(fn($group) => [
            'company' => $group->first()->company?->name ?? 'Walk-in',
            'frequency' => $group->count(),
        ])
        ->values();

    $totalAttendance = $attendance->sum('frequency');

    return view('reports.lab-monthly', [
        'attendance' => $attendance,
        'totalAttendance' => $totalAttendance,
        'month' => $startDate->format('F Y'),
    ]);
}


private function generatePdf($data)
{
    $pdf = \PDF::loadView('reports.pdf.lab-monthly', $data);
    $filename = "Laboratory_Monthly_Report_{$data['monthName']}_{$data['year']}.pdf";
    return $pdf->download($filename);
}

    /**
     * Export a single patient's claim as PDF (respects Livewire filters)
     */
    public function exportPatientClaimPdf(Request $request, Company $company, Patient $patient)
    {
        // 1. Ensure patient belongs to this company
        if ($patient->company_id != $company->id) {
            abort(404);
        }

        // 2. Get filters from URL (same as Livewire)
        $fromDate       = $request->input('fromDate', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate         = $request->input('toDate',   Carbon::now()->endOfMonth()->format('Y-m-d'));
        $billType       = $request->input('billType', '');
        $paymentStatus  = $request->input('paymentStatus', '');

        // 3. Load patient + all bill types with date & status filters
        $patient = Patient::with(['user'])
            ->with(['invoices' => fn($q) => $q
                ->whereDate('invoice_date', '>=', $fromDate)
                ->whereDate('invoice_date', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->with(['medicine_bills' => fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->with(['ipd_bills.bill' => fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->with(['pathologyTests' => fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->with(['radiologyTests' => fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->with(['maternity' => fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            ])
            ->findOrFail($patient->id);

        // 4. Optional: Filter by specific bill type
        if ($billType) {
            $relation = $billType . 's'; // e.g., 'invoice' → 'invoices'
            $patient->whereHas($relation, fn($q) => $q
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->when($paymentStatus, fn($q) => $q->where('payment_status', $paymentStatus))
            );
        }

        // 5. Load medicine line items (matches your Blade query)
        $medicineItems = DB::table('sale_medicines')
            ->join('medicine_bills', 'sale_medicines.medicine_bill_id', '=', 'medicine_bills.id')
            ->join('medicines', 'sale_medicines.medicine_id', '=', 'medicines.id')
            ->where('medicine_bills.patient_id', $patient->id)
            ->whereDate('medicine_bills.bill_date', '>=', $fromDate)
            ->whereDate('medicine_bills.bill_date', '<=', $toDate)
            ->when($paymentStatus, fn($q) => $q->where('medicine_bills.payment_status', $paymentStatus))
            ->select(
                'sale_medicines.sale_quantity',
                'sale_medicines.amount',
                'medicines.name as medicine_name',
                'medicine_bills.bill_number',
                'medicine_bills.bill_date',
                'medicine_bills.paid_amount'
            )
            ->get();

        // 6. Generate PDF
        $pdf = Pdf::loadView('reports.pdf.patient-claim', compact(
            'company', 'patient', 'medicineItems', 'fromDate', 'toDate'
        ));

        $pdf->setPaper('A4', 'landscape');

        $fileName = "Patient_Claim_{$patient->patient_unique_id}_" . now()->format('Ymd') . ".pdf";

        return $pdf->download($fileName);
    }

}