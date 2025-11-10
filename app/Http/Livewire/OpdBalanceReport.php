<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OpdPatientDepartment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OpdBalanceReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    public $formattedStartDate;
    public $formattedEndDate;
    public $searchQuery = '';
    public $doctorFilter = 'all';
    public $paymentStatusFilter = 'all';
    public $perPage = 10;
    public $doctors = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
        $this->doctors = Doctor::with('user')->get()->sortBy('user.first_name');
    }

    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                // When selecting 'today', also reset other filters to match other reports behavior
                $this->doctorFilter = 'all';
                $this->paymentStatusFilter = 'all';
                $this->searchQuery = '';
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
                // Keep the existing dates for custom filter
                break;
        }
        
        $this->updateFormattedDates();
    }

    public function updatedStartDate()
    {
        // If end date is before start date, update end date to match start date
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
        $this->dateFilter = 'custom';
        $this->updateFormattedDates();
    }

    public function updatedEndDate()
    {
        // If end date is before start date, update start date to match end date
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->startDate = $this->endDate;
        }
        $this->dateFilter = 'custom';
        $this->updateFormattedDates();
    }

    public function updateFormattedDates()
    {
        $this->formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $this->formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchQuery = '';
        $this->doctorFilter = 'all';
        $this->paymentStatusFilter = 'all';
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getOpdBalances()
    {
        // Start with OPD patient departments query
        $query = OpdPatientDepartment::with(['patient.patientUser', 'doctor.doctorUser'])
            ->whereBetween('appointment_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        
        // Apply doctor filter
        if ($this->doctorFilter !== 'all') {
            $query->where('doctor_id', $this->doctorFilter);
        }
        
        // Apply search query to patient name or OPD number
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $q) {
                $q->where('opd_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('patient.patientUser', function (Builder $q) {
                        $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        // Get all OPD records first to calculate totals
        $allOpdRecords = $query->get();
        
        // Calculate totals
        $totalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;
        
        // Filter records based on payment status
        $filteredIds = [];
        
        foreach ($allOpdRecords as $opd) {
            $standardCharge = $opd->standard_charge ?: 0;
            $paidAmount = $opd->paid_amount ? array_sum($opd->paid_amount) : 0;
            $balance = $standardCharge - $paidAmount;
            
            // Add to totals regardless of filter
            $totalAmount += $standardCharge;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
            
            // Apply payment status filter
            $status = $balance > 0 ? 'pending' : 'paid';
            
            if ($this->paymentStatusFilter === 'all' || 
                ($this->paymentStatusFilter === 'paid' && $status === 'paid') ||
                ($this->paymentStatusFilter === 'pending' && $status === 'pending')) {
                $filteredIds[] = $opd->id;
            }
        }
        
        // Apply payment status filter to query
        if ($this->paymentStatusFilter !== 'all') {
            $query->whereIn('id', $filteredIds);
        }
        
        // Get paginated records
        $opdRecords = $query->paginate($this->perPage);
        
        // Process records for display
        $balanceRecords = [];
        
        foreach ($opdRecords as $opd) {
            // Calculate charges and payments
            $standardCharge = $opd->standard_charge ?: 0;
            $paidAmount = $opd->paid_amount ? array_sum($opd->paid_amount) : 0;
            $balance = $standardCharge - $paidAmount;
            
            // Add to records array
            $balanceRecords[] = [
                'id' => $opd->id,
                'opd_number' => $opd->opd_number,
                'appointment_date' => Carbon::parse($opd->appointment_date)->format('M d, Y'),
                'patient' => [
                    'id' => $opd->patient->id,
                    'name' => $opd->patient->patientUser->full_name,
                    'email' => $opd->patient->patientUser->email,
                    'image_url' => $opd->patient->patientUser->image_url,
                ],
                'doctor' => [
                    'id' => $opd->doctor ? $opd->doctor->id : null,
                    'name' => $opd->doctor ? $opd->doctor->doctorUser->full_name : 'N/A',
                    'email' => $opd->doctor ? $opd->doctor->doctorUser->email : '',
                    'image_url' => $opd->doctor ? $opd->doctor->doctorUser->image_url : '',
                ],
                'standard_charge' => $standardCharge,
                'paid_amount' => $paidAmount,
                'balance' => $balance,
                'status' => $balance > 0 ? 'pending' : 'paid',
                'invoice_id' => $opd->invoice_id,
            ];
        }
        
        return [
            'data' => $balanceRecords,
            'total' => $opdRecords->total(),
            'per_page' => $this->perPage,
            'current_page' => $opdRecords->currentPage(),
            'last_page' => $opdRecords->lastPage(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'paginator' => $opdRecords,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    


    public function render()
    {
        $opdBalances = $this->getOpdBalances();
        
        return view('livewire.opd-balance-report', [
            'opdBalances' => $opdBalances,
        ]);
    }
}
