<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\MedicineBill;
use App\Models\Patient;
use App\Models\SaleMedicine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PharmacyBillReport extends Component
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

    public function getPharmacyBills()
    {
        // Start with Medicine Bills query
        $query = MedicineBill::with(['patient.patientUser', 'doctor.doctorUser', 'saleMedicine.medicine'])
            ->whereBetween(DB::raw('DATE(created_at)'), [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        
        // Apply doctor filter if selected
        if ($this->doctorFilter !== 'all') {
            $query->where('doctor_id', $this->doctorFilter);
        }
        
        // Apply search filter if provided
        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $q->where('bill_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('patient.patientUser', function (Builder $q) {
                        $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        // Get all pharmacy bills first to calculate totals
        $allPharmacyBills = $query->get();
        
        // Calculate totals
        $totalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;
        
        // Filter records based on payment status
        $filteredIds = [];
        
        foreach ($allPharmacyBills as $bill) {
            $totalAmount += $bill->net_amount;
            $paidAmount = $bill->paid_amount ?? 0;
            $balance = $bill->net_amount - $paidAmount;
            
            // Add to totals
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
            
            // Apply payment status filter
            $status = '';
            if ($bill->payment_status == MedicineBill::FULLPAID) {
                $status = 'paid';
            } elseif ($bill->payment_status == MedicineBill::PARTIALY_PAID) {
                $status = 'partially_paid';
            } else {
                $status = 'pending';
            }
            
            if ($this->paymentStatusFilter === 'all' || 
                ($this->paymentStatusFilter === 'paid' && $status === 'paid') ||
                ($this->paymentStatusFilter === 'pending' && ($status === 'pending' || $status === 'partially_paid'))) {
                $filteredIds[] = $bill->id;
            }
        }
        
        // Apply payment status filter to query
        if ($this->paymentStatusFilter !== 'all') {
            $query->whereIn('id', $filteredIds);
        }
        
        // Get paginated records
        $pharmacyBills = $query->paginate($this->perPage);
        
        // Process records for display
        $billRecords = [];
        
        foreach ($pharmacyBills as $bill) {
            // Calculate balance
            $paidAmount = $bill->paid_amount ?? 0;
            $balance = $bill->net_amount - $paidAmount;
            
            // Determine payment status
            $status = '';
            if ($bill->payment_status == MedicineBill::FULLPAID) {
                $status = 'paid';
            } elseif ($bill->payment_status == MedicineBill::PARTIALY_PAID) {
                $status = 'partially_paid';
            } else {
                $status = 'pending';
            }
            
            // Get medicine details
            $medicines = [];
            foreach ($bill->saleMedicine as $saleMedicine) {
                $medicines[] = [
                    'name' => $saleMedicine->medicine->name,
                    'quantity' => $saleMedicine->sale_quantity,
                    'price' => $saleMedicine->sale_price ?? 0,
                    'amount' => $saleMedicine->amount ?? 0,
                ];
            }
            
            // Add to records array
            $billRecords[] = [
                'id' => $bill->id,
                'bill_number' => $bill->bill_number,
                'bill_date' => Carbon::parse($bill->created_at)->format('M d, Y'),
                'patient' => [
                    'id' => $bill->patient->id,
                    'name' => $bill->patient->patientUser->full_name,
                    'email' => $bill->patient->patientUser->email,
                    'image_url' => $bill->patient->patientUser->image_url,
                ],
                'doctor' => [
                    'id' => $bill->doctor ? $bill->doctor->id : null,
                    'name' => $bill->doctor ? $bill->doctor->doctorUser->full_name : 'N/A',
                    'email' => $bill->doctor ? $bill->doctor->doctorUser->email : '',
                    'image_url' => $bill->doctor ? $bill->doctor->doctorUser->image_url : '',
                ],
                'total_amount' => $bill->net_amount,
                'paid_amount' => $paidAmount,
                'balance' => $balance,
                'status' => $status,
                'medicines' => $medicines,
                'discount' => $bill->discount ?? 0,
                'tax_amount' => $bill->tax_amount ?? 0,
            ];
        }
        
        return [
            'data' => $billRecords,
            'total' => $pharmacyBills->total(),
            'per_page' => $this->perPage,
            'current_page' => $pharmacyBills->currentPage(),
            'last_page' => $pharmacyBills->lastPage(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'paginator' => $pharmacyBills,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $pharmacyBills = $this->getPharmacyBills();
        
        return view('livewire.pharmacy-bill-report', [
            'pharmacyBills' => $pharmacyBills,
        ]);
    }
}
