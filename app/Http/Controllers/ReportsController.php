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
}
