<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\IpdBill;
use App\Models\IpdCharge;
use App\Models\IpdPatientDepartment;
use App\Models\IpdPayment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class IpdBalanceReport extends Component
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

    public function getIpdBalances()
    {
        // Start with IPD patient departments query
        $query = IpdPatientDepartment::with(['patient.patientUser', 'doctor.doctorUser', 'bill'])
            ->whereBetween('admission_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        
        // Apply doctor filter
        if ($this->doctorFilter !== 'all') {
            $query->where('doctor_id', $this->doctorFilter);
        }
        
        // Apply search filter
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $q) {
                $q->where('ipd_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('patient.patientUser', function (Builder $q) {
                        $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        // Get all IPD records first to calculate totals
        $allIpdRecords = $query->get();
        
        // Calculate totals
        $totalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;
        
        // Filter records based on payment status
        $filteredIds = [];
        
        foreach ($allIpdRecords as $ipd) {
            // Get charges for this IPD
            $charges = IpdCharge::where('ipd_patient_department_id', $ipd->id)->sum('applied_charge');
            
            // Get payments for this IPD
            $payments = IpdPayment::where('ipd_patient_department_id', $ipd->id)->sum('amount');
            
            $balance = $charges - $payments;
            
            // Add to totals regardless of filter
            $totalAmount += $charges;
            $totalPaid += $payments;
            $totalBalance += $balance;
            
            // Apply payment status filter
            $status = $balance > 0 ? 'pending' : 'paid';
            
            if ($this->paymentStatusFilter === 'all' || 
                ($this->paymentStatusFilter === 'paid' && $status === 'paid') ||
                ($this->paymentStatusFilter === 'pending' && $status === 'pending')) {
                $filteredIds[] = $ipd->id;
            }
        }
        
        // Apply payment status filter to query
        if ($this->paymentStatusFilter !== 'all') {
            $query->whereIn('id', $filteredIds);
        }
        
        // Get paginated records
        $ipdRecords = $query->paginate($this->perPage);
        
        // Process records for display
        $balanceRecords = [];
        
        foreach ($ipdRecords as $ipd) {
            // Get charges for this IPD
            $charges = IpdCharge::where('ipd_patient_department_id', $ipd->id)->sum('applied_charge');
            
            // Get payments for this IPD
            $payments = IpdPayment::where('ipd_patient_department_id', $ipd->id)->sum('amount');
            
            $balance = $charges - $payments;
            
            // Add to records array
            $balanceRecords[] = [
                'id' => $ipd->id,
                'ipd_number' => $ipd->ipd_number,
                'admission_date' => Carbon::parse($ipd->admission_date)->format('M d, Y'),
                'patient' => [
                    'id' => $ipd->patient->id,
                    'name' => $ipd->patient->patientUser->full_name,
                    'email' => $ipd->patient->patientUser->email,
                    'image_url' => $ipd->patient->patientUser->image_url,
                ],
                'doctor' => [
                    'id' => $ipd->doctor ? $ipd->doctor->id : null,
                    'name' => $ipd->doctor ? $ipd->doctor->doctorUser->full_name : 'N/A',
                    'email' => $ipd->doctor ? $ipd->doctor->doctorUser->email : '',
                    'image_url' => $ipd->doctor ? $ipd->doctor->doctorUser->image_url : '',
                ],
                'total_charges' => $charges,
                'paid_amount' => $payments,
                'balance' => $balance,
                'status' => $balance > 0 ? 'pending' : 'paid',
                'bill_id' => $ipd->bill ? $ipd->bill->id : null,
            ];
        }
        
        return [
            'data' => $balanceRecords,
            'total' => $ipdRecords->total(),
            'per_page' => $this->perPage,
            'current_page' => $ipdRecords->currentPage(),
            'last_page' => $ipdRecords->lastPage(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'paginator' => $ipdRecords,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $ipdBalances = $this->getIpdBalances();
        
        return view('livewire.ipd-balance-report', [
            'ipdBalances' => $ipdBalances,
        ]);
    }
}
