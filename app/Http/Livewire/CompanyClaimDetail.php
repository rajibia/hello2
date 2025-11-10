<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyClaimDetail extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $company;
    public $dateFilter = 'this_month';
    public $fromDate;
    public $toDate;
    public $billType = '';
    public $paymentStatus = '';
    public $perPage = 20;
    public $patientId = '';
    public $companyPatients = [];
    
    protected $queryString = [
        'dateFilter' => ['except' => 'this_month'],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        'billType' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'patientId' => ['except' => ''],
        'perPage' => ['except' => 20],
    ];
    
    public function mount(Company $company)
    {
        $this->company = $company;
        $this->setDefaultDates();
        $this->loadCompanyPatients();
    }
    
    public function loadCompanyPatients()
    {
        $this->companyPatients = $this->company->patients()
            ->with('user')
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->user->full_name . ' (' . $patient->patient_unique_id . ')'
                ];
            })
            ->toArray();    
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
    
    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
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
        // Build the base query for patients with eager loading relationships
        $baseQuery = $this->company->patients()
            ->with(['user']); // Always load the user relationship
            
        // Filter by patient ID if selected
        if ($this->patientId) {
            $baseQuery->where('id', $this->patientId);
        }
        
        // Define the relationships to load based on filters
        $relationships = [
            'invoices' => function($query) {
                $query->whereDate('invoice_date', '>=', $this->fromDate)
                      ->whereDate('invoice_date', '<=', $this->toDate);
                
                if ($this->paymentStatus) {
                    $query->where('payment_status', $this->paymentStatus);
                }
            },
            'medicine_bills' => function($query) {
                $query->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                
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
            }
        ];
        
        // Eager load all relationships
        foreach ($relationships as $relation => $callback) {
            $baseQuery->with([$relation => $callback]);
        }
        
        // Apply bill type filter if specified
        if ($this->billType) {
            switch ($this->billType) {
                case 'opd_invoice':
                    $baseQuery->whereHas('invoices', function($q) {
                        $q->whereDate('invoice_date', '>=', $this->fromDate)
                          ->whereDate('invoice_date', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
                case 'medicine_bill':
                    $baseQuery->whereHas('medicine_bills', function($q) {
                        $q->whereDate('created_at', '>=', $this->fromDate)
                          ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
                case 'ipd_bill':
                    $baseQuery->whereHas('ipd_bills', function($q) {
                        $q->whereDate('created_at', '>=', $this->fromDate)
                          ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
                case 'pathology_test':
                    $baseQuery->whereHas('pathologyTests', function($q) {
                        $q->whereDate('created_at', '>=', $this->fromDate)
                          ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
                case 'radiology_test':
                    $baseQuery->whereHas('radiologyTests', function($q) {
                        $q->whereDate('created_at', '>=', $this->fromDate)
                          ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
                case 'maternity':
                    $baseQuery->whereHas('maternity', function($q) {
                        $q->whereDate('created_at', '>=', $this->fromDate)
                          ->whereDate('created_at', '<=', $this->toDate);
                        
                        if ($this->paymentStatus) {
                            $q->where('payment_status', $this->paymentStatus);
                        }
                    });
                    break;
            }
        } else {
            // If no specific bill type is selected, filter by date across all bill types
            $baseQuery->where(function($query) {
                $query->whereHas('invoices', function($q) {
                    $q->whereDate('invoice_date', '>=', $this->fromDate)
                      ->whereDate('invoice_date', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                })
                ->orWhereHas('medicine_bills', function($q) {
                    $q->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                })
                ->orWhereHas('ipd_bills', function($q) {
                    $q->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                })
                ->orWhereHas('pathologyTests', function($q) {
                    $q->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                })
                ->orWhereHas('radiologyTests', function($q) {
                    $q->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                })
                ->orWhereHas('maternity', function($q) {
                    $q->whereDate('created_at', '>=', $this->fromDate)
                      ->whereDate('created_at', '<=', $this->toDate);
                    
                    if ($this->paymentStatus) {
                        $q->where('payment_status', $this->paymentStatus);
                    }
                });
            });
        }
        
        // Get paginated patients with all their related data in a single query
        $patientsWithBills = $baseQuery->paginate($this->perPage);
        
        return [
            'patients' => $patientsWithBills->items(),
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
                $totalPaid += ($invoice->amount - $invoice->balance);
                $totalDue += $invoice->balance;
            }
            
            // Count Medicine Bills
            foreach ($patient->medicine_bills as $bill) {
                $totalBills++;
                $totalAmount += $bill->net_amount;
                $totalPaid += ($bill->net_amount - $bill->balance);
                $totalDue += $bill->balance;
            }
            
            // Count IPD Bills
            foreach ($patient->ipd_bills as $bill) {
                if ($bill->bill) {
                    $totalBills++;
                    $totalAmount += $bill->bill->net_payable_amount;
                    $totalPaid += $bill->bill->total_payments;
                    $totalDue += ($bill->bill->net_payable_amount - $bill->bill->total_payments);
                }
            }
            
            // Count Pathology Tests
            foreach ($patient->pathologyTests as $test) {
                $totalBills++;
                $totalAmount += $test->total;
                $totalPaid += ($test->total - $test->balance);
                $totalDue += $test->balance;
            }
            
            // Count Radiology Tests
            foreach ($patient->radiologyTests as $test) {
                $totalBills++;
                $totalAmount += $test->total;
                $totalPaid += ($test->total - $test->balance);
                $totalDue += $test->balance;
            }
            
            // Count Maternity Bills
            foreach ($patient->maternity as $maternity) {
                $totalBills++;
                $totalAmount += $maternity->standard_charge;
                $totalPaid += ($maternity->standard_charge - $maternity->balance);
                $totalDue += $maternity->balance;
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
        return view('livewire.company-claim-detail', [
            'patientBills' => $this->patientBills,
            'summaryData' => $this->summaryData,
        ]);
    }
}
