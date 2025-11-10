<?php

namespace App\Http\Livewire;

use App\Models\PurchaseMedicine;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    public $formattedStartDate;
    public $formattedEndDate;
    public $searchQuery = '';
    public $supplierFilter = 'all';
    public $paymentStatusFilter = 'all';
    public $perPage = 10;
    public $suppliers = [];
    public $paymentStatuses = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
        
        // Load all suppliers for the filter dropdown
        $this->suppliers = Supplier::orderBy('name')->pluck('name', 'id')->toArray();
        $this->suppliers = ['all' => 'All Suppliers'] + $this->suppliers;
        
        // Payment status options
        $this->paymentStatuses = [
            'all' => 'All Statuses',
            PurchaseMedicine::PAID => 'Paid',
            PurchaseMedicine::UNPAID => 'Unpaid',
        ];
    }

    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                // When selecting 'today', also reset other filters to match other reports behavior
                $this->supplierFilter = 'all';
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
        $this->supplierFilter = 'all';
        $this->paymentStatusFilter = 'all';
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getPurchases()
    {
        // Start with purchases query
        $query = PurchaseMedicine::query()
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        
        // Apply supplier filter
        if ($this->supplierFilter !== 'all') {
            $query->where('supplier_id', $this->supplierFilter);
        }
        
        // Apply payment status filter
        if ($this->paymentStatusFilter !== 'all') {
            $query->where('payment_status', $this->paymentStatusFilter);
        }
        
        // Apply search filter
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $q) {
                $q->where('purchase_no', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('payment_note', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('note', 'like', '%' . $this->searchQuery . '%')
                  ->orWhereHas('supplier', function (Builder $sq) {
                      $sq->where('name', 'like', '%' . $this->searchQuery . '%');
                  });
            });
        }
        
        // Get all purchase records first to calculate total
        $allPurchases = $query->get();
        
        // Calculate total amounts
        $totalAmount = $allPurchases->sum('net_amount');
        $totalPaid = $allPurchases->sum('paid_amount');
        $totalBalance = $allPurchases->sum('balance');
        
        // Get paginated records with eager loading
        $purchases = $query->with('supplier', 'purchasedMedcines.medicines')
                          ->orderBy('created_at', 'desc')
                          ->paginate($this->perPage);
        
        // Process records for display
        $purchaseRecords = [];
        $paymentMethods = PurchaseMedicine::PAYMENT_METHOD;
        $paymentStatuses = PurchaseMedicine::PAYMENT_STATUS;
        
        foreach ($purchases as $purchase) {
            // Add to records array
            $purchaseRecords[] = [
                'id' => $purchase->id,
                'purchase_no' => $purchase->purchase_no,
                'date' => Carbon::parse($purchase->created_at)->format('M d, Y'),
                'supplier' => $purchase->supplier ? $purchase->supplier->name : 'N/A',
                'supplier_id' => $purchase->supplier_id,
                'total' => $purchase->total,
                'discount' => $purchase->discount,
                'tax' => $purchase->tax,
                'net_amount' => $purchase->net_amount,
                'paid_amount' => $purchase->paid_amount,
                'balance' => $purchase->balance,
                'payment_type' => $paymentMethods[$purchase->payment_type] ?? 'Unknown',
                'payment_status' => $purchase->payment_status,
                'payment_status_label' => $paymentStatuses[$purchase->payment_status] ?? 'Unknown',
                'items_count' => $purchase->purchasedMedcines->count(),
            ];
        }
        
        return [
            'data' => $purchaseRecords,
            'total' => $purchases->total(),
            'per_page' => $this->perPage,
            'current_page' => $purchases->currentPage(),
            'last_page' => $purchases->lastPage(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'paginator' => $purchases,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $purchases = $this->getPurchases();
        
        return view('livewire.purchase-report', [
            'purchases' => $purchases,
        ]);
    }
}
