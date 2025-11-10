<?php

namespace App\Http\Livewire;

use App\Models\OpdPatientDepartment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OpdStatementReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    // Date filter properties
    public $dateFilter = 'today';
    public $startDate;
    public $endDate;
    
    // Listeners for Livewire events
    protected $listeners = ['refresh' => '$refresh'];
    
    // Mount and lifecycle methods
    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        
        // Initialize with today's date
        $this->changeDateFilter('today');
    }
    
    // Update methods for date changes
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
            case 'custom':
                // Dates are already set by the date inputs
                break;
        }
        
        $this->resetPage();
    }
    
    // Get OPD statements data
    public function getOpdStatements()
    {
        return OpdPatientDepartment::with([
                'patient.patientUser',
                'doctor.doctorUser'
            ])
            ->leftJoin('invoices', 'opd_patient_departments.invoice_id', '=', 'invoices.id')
            ->whereBetween(DB::raw('DATE(opd_patient_departments.appointment_date)'), [$this->startDate, $this->endDate])
            ->select(
                'opd_patient_departments.*',
                'invoices.invoice_id as invoice_number',
                'invoices.status as invoice_status'
            )
            ->orderBy('opd_patient_departments.appointment_date', 'desc')
            ->paginate(10);
    }
    
    // Format date for display
    public function formatDate($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        return Carbon::parse($date)->format('M d, Y');
    }
    
    // Calculate payment status
    public function getPaymentStatus($standardCharge, $paidAmount)
    {
        if (empty($paidAmount)) {
            return ['status' => 'unpaid', 'badge' => 'danger', 'text' => 'Unpaid'];
        }
        
        if ($paidAmount >= $standardCharge) {
            return ['status' => 'paid', 'badge' => 'success', 'text' => 'Paid'];
        }
        
        return ['status' => 'partial', 'badge' => 'warning', 'text' => 'Partial'];
    }
    
    // Calculate balance
    public function calculateBalance($standardCharge, $paidAmount)
    {
        $paidAmount = $paidAmount ?? 0;
        return $standardCharge - $paidAmount;
    }
    
    public function render()
    {
        $opdStatements = $this->getOpdStatements();
        
        $formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        
        return view('livewire.opd-statement-report', [
            'opdStatements' => $opdStatements,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate
        ]);
    }
}
