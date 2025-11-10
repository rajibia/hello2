<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PatientStatementReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    // Date filter properties
    public $dateFilter = 'this_month';
    public $startDate;
    public $endDate;
    
    // Patient filter
    public $patientId = null;
    public $searchTerm = '';
    
    // Listeners for Livewire events
    protected $listeners = ['refresh' => '$refresh'];
    
    // Mount and lifecycle methods
    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    
    // Update methods for date and patient changes
    public function updatedStartDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    public function updatedEndDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }
    
    public function patientSelected($patientId)
    {
        $this->patientId = $patientId;
        $this->resetPage();
    }
    
    // Change date filter method (Today, Yesterday, etc.)
    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->startDate = Carbon::yesterday()->format('Y-m-d');
                $this->endDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Dates are already set by the date inputs
                break;
        }
        
        $this->resetPage();
    }
    
    // Get patient statement data
    public function getPatientStatements()
    {
        // First, get all patients if no specific patient is selected
        if (!$this->patientId) {
            // Get patients who have invoices or bills within the selected date range
            return Patient::with(['patientUser'])
                ->where(function($query) {
                    $query->whereHas('invoices', function($q) {
                        $q->whereBetween('invoice_date', [$this->startDate, $this->endDate]);
                    })
                    ->orWhereHas('bills', function($q) {
                        $q->whereBetween('bill_date', [$this->startDate, $this->endDate]);
                    });
                })
                ->when($this->searchTerm, function($query) {
                    $query->whereHas('patientUser', function($q) {
                        $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->paginate(10);
        }
        
        // If a specific patient is selected, get their detailed statement
        return Patient::with([
            'patientUser',
            'invoices' => function($query) {
                $query->whereBetween('invoice_date', [$this->startDate, $this->endDate])
                      ->with('invoiceItems.charge.chargeCategory');
            },
            'bills' => function($query) {
                $query->whereBetween('bill_date', [$this->startDate, $this->endDate])
                      ->with('billItems', 'manualBillPayment');
            }
        ])->where('id', $this->patientId)
          ->paginate(1);
    }
    
    // Calculate total amounts
    public function calculateTotals($patient)
    {
        $invoiceTotal = $patient->invoices->sum('amount');
        $invoiceDiscount = $patient->invoices->sum(function($invoice) {
            return $invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0;
        });
        $invoicePaid = $patient->invoices->sum('paid_amount');
        
        $billTotal = $patient->bills->sum('amount');
        $billPaid = $patient->bills->sum(function($bill) {
            return $bill->manualBillPayment->sum('amount') ?? 0;
        });
        
        return [
            'total_charges' => $invoiceTotal + $billTotal,
            'total_discount' => $invoiceDiscount,
            'total_paid' => $invoicePaid + $billPaid,
            'total_due' => ($invoiceTotal + $billTotal) - ($invoiceDiscount + $invoicePaid + $billPaid)
        ];
    }
    
    // Format date for display
    public function formatDate($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        return Carbon::parse($date)->format('M d, Y');
    }
    
    // Get payment status
    public function getPaymentStatus($total, $paid)
    {
        if (empty($paid)) {
            return ['status' => 'unpaid', 'badge' => 'danger', 'text' => 'Unpaid'];
        }
        
        if ($paid >= $total) {
            return ['status' => 'paid', 'badge' => 'success', 'text' => 'Paid'];
        }
        
        return ['status' => 'partial', 'badge' => 'warning', 'text' => 'Partial'];
    }
    
    // Print report
    public function printReport()
    {
        $this->emit('print-patient-statement');
    }
    
    public function render()
    {
        $patientStatements = $this->getPatientStatements();
        
        $formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        
        return view('livewire.patient-statement-report', [
            'patientStatements' => $patientStatements,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate
        ]);
    }
}
