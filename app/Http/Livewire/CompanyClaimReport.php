<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyClaimReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $companyId;
    public $company;
    public $dateFilter = 'this_month';
    public $fromDate;
    public $toDate;
    public $billType = '';
    public $paymentStatus = '';
    public $perPage = 20;
    public $patientId = '';
    public $companyPatients = [];
    
    public function mount($companyId)
    {
        $this->companyId = $companyId;
        $this->company = Company::findOrFail($companyId);
        $this->loadCompanyPatients();
        $this->setDefaultDates();
    }
    
    public function loadCompanyPatients()
    {
        $this->companyPatients = $this->company->patients()
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();
    }
    
    public function setDefaultDates()
    {
        switch ($this->dateFilter) {
            case 'today':
                $this->fromDate = Carbon::today()->format('Y-m-d');
                $this->toDate = Carbon::today()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->fromDate = Carbon::yesterday()->format('Y-m-d');
                $this->toDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->fromDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->toDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->fromDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->toDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Keep existing dates if custom
                if (!$this->fromDate) {
                    $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                }
                if (!$this->toDate) {
                    $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                }
                break;
            default:
                $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->toDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
        }
    }
    
    public function updatedDateFilter()
    {
        $this->setDefaultDates();
        $this->resetPage();
    }
    
    public function updatedFromDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    public function updatedToDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    public function updatedBillType()
    {
        $this->resetPage();
    }
    
    public function updatedPaymentStatus()
    {
        $this->resetPage();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->dateFilter = 'this_month';
        $this->billType = '';
        $this->paymentStatus = '';
        $this->patientId = '';
        $this->setDefaultDates();
        $this->resetPage();
    }
    
    public function updatedPatientId()
    {
        $this->resetPage();
    }
    
    public function getPatientBillsProperty()
    {
        // Build the base query for patients
        $baseQuery = $this->company->patients();
        
        // Apply patient filter if specified
        if ($this->patientId) {
            $baseQuery->where('id', $this->patientId);
        }
        
        // Apply bill type filter if specified
        if ($this->billType) {
            $baseQuery->where(function($query) {
                switch ($this->billType) {
                    case 'opd_invoice':
                        $query->whereHas('invoices');
                        break;
                    case 'medicine_bill':
                        $query->whereHas('medicine_bills');
                        break;
                    case 'ipd_bill':
                        $query->whereHas('ipd_bills');
                        break;
                    case 'pathology_test':
                        $query->whereHas('pathologyTests');
                        break;
                    case 'radiology_test':
                        $query->whereHas('radiologyTests');
                        break;
                    case 'maternity':
                        $query->whereHas('maternity');
                        break;
                }
            });
        }
        
        // Get paginated patients
        $patientsWithBills = $baseQuery->with('user')->paginate($this->perPage);
        
        // Now get the full data for the paginated patients
        $patients = collect();
        foreach ($patientsWithBills as $patient) {
            $patientWithBills = $this->company->patients()
                ->with([
                    'invoices' => function($query) {
                        $query->whereDate('invoice_date', '>=', $this->fromDate)
                              ->whereDate('invoice_date', '<=', $this->toDate)
                              ->with('invoiceItems.charge.chargeCategory');
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'medicine_bills' => function($query) {
                        $query->whereDate('created_at', '>=', $this->fromDate)
                              ->whereDate('created_at', '<=', $this->toDate)
                              ->with('saleMedicine.medicine');
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'ipd_bills' => function($query) {
                        $query->whereDate('created_at', '>=', $this->fromDate)
                              ->whereDate('created_at', '<=', $this->toDate)
                              ->with('bill');
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'pathologyTests' => function($query) {
                        $query->whereDate('created_at', '>=', $this->fromDate)
                              ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'radiologyTests' => function($query) {
                        $query->whereDate('created_at', '>=', $this->fromDate)
                              ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'maternity' => function($query) {
                        $query->whereDate('created_at', '>=', $this->fromDate)
                              ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $query->where('payment_status', $this->paymentStatus);
                        }
                    },
                    'user'
                ])
                ->where('id', $patient->id)
                ->first();
            
            if ($patientWithBills) {
                $patients->push($patientWithBills);
            }
        }
        
        return [
            'patients' => $patients,
            'paginator' => $patientsWithBills,
        ];
    }
    
    public function getSummaryDataProperty()
    {
        $totalBills = 0;
        $totalAmount = 0;
        $totalPaid = 0;
        $totalDue = 0;
        
        foreach ($this->patientBills['patients'] as $patient) {
            // Count OPD Invoices
            foreach ($patient->invoices as $invoice) {
                $totalBills++;
                $totalAmount += $invoice->amount;
                $totalPaid += $invoice->amount_paid;
                $totalDue += $invoice->amount_due;
            }
            
            // Count Medicine Bills
            foreach ($patient->medicine_bills as $bill) {
                $totalBills++;
                $totalAmount += $bill->total;
                $totalPaid += $bill->paid_amount;
                $totalDue += $bill->due_amount;
            }
            
            // Count IPD Bills
            foreach ($patient->ipd_bills as $bill) {
                $totalBills++;
                $totalAmount += $bill->total_amount;
                $totalPaid += $bill->paid_amount;
                $totalDue += $bill->due_amount;
            }
            
            // Count Pathology Tests
            foreach ($patient->pathologyTests as $test) {
                $totalBills++;
                $totalAmount += $test->charge;
                $totalPaid += $test->paid_amount;
                $totalDue += ($test->charge - $test->paid_amount);
            }
            
            // Count Radiology Tests
            foreach ($patient->radiologyTests as $test) {
                $totalBills++;
                $totalAmount += $test->charge;
                $totalPaid += $test->paid_amount;
                $totalDue += ($test->charge - $test->paid_amount);
            }
            
            // Count Maternity Bills
            foreach ($patient->maternity as $maternity) {
                $totalBills++;
                $totalAmount += $maternity->package_price;
                $totalPaid += $maternity->paid_amount;
                $totalDue += $maternity->due_amount;
            }
        }
        
        return [
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'total_bills' => $totalBills,
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue,
        ];
    }
    
    public function render()
    {
        return view('livewire.company-claim-report', [
            'patientBills' => $this->patientBills,
            'summaryData' => $this->summaryData,
        ]);
    }
}
