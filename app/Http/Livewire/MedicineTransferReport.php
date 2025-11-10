<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\StockTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MedicineTransferReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    public $formattedStartDate;
    public $formattedEndDate;
    public $searchQuery = '';
    public $categoryFilter = 'all';
    public $brandFilter = 'all';
    public $transferDirectionFilter = 'all';
    public $perPage = 10;
    public $categories = [];
    public $brands = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
        $this->categories = Category::where('is_active', 1)->get()->sortBy('name');
        $this->brands = Brand::get()->sortBy('name');
    }

    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                // When selecting 'today', also reset other filters to match other reports behavior
                $this->categoryFilter = 'all';
                $this->brandFilter = 'all';
                $this->transferDirectionFilter = 'all';
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
        $this->categoryFilter = 'all';
        $this->brandFilter = 'all';
        $this->transferDirectionFilter = 'all';
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getTransfers()
    {
        // Start with StockTransfer query
        $query = StockTransfer::with(['medicine', 'medicine.category', 'medicine.brand', 'user'])
            ->whereBetween(DB::raw('DATE(created_at)'), [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
            
        // Apply transfer direction filter
        if ($this->transferDirectionFilter !== 'all') {
            if ($this->transferDirectionFilter === 'dispensary_to_store') {
                $query->where('transfer_from', 'Dispensary')
                      ->where('transfer_to', 'Store');
            } elseif ($this->transferDirectionFilter === 'store_to_dispensary') {
                $query->where('transfer_from', 'Store')
                      ->where('transfer_to', 'Dispensary');
            }
        }
        
        // Apply category filter (via medicine relationship)
        if ($this->categoryFilter !== 'all') {
            $query->whereHas('medicine', function (Builder $query) {
                $query->where('category_id', $this->categoryFilter);
            });
        }
        
        // Apply brand filter (via medicine relationship)
        if ($this->brandFilter !== 'all') {
            $query->whereHas('medicine', function (Builder $query) {
                $query->where('brand_id', $this->brandFilter);
            });
        }
        
        // Apply search query
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $query) {
                $query->whereHas('medicine', function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('salt_composition', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhereHas('medicine.category', function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhereHas('medicine.brand', function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhereHas('user', function (Builder $query) {
                    $query->where('first_name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%');
                });
            });
        }
        
        // Get paginated records
        $transfers = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        // Calculate totals
        $totalTransferQuantity = 0;
        $totalDispensaryToStore = 0;
        $totalStoreToDispensary = 0;
        
        foreach ($transfers as $transfer) {
            $totalTransferQuantity += $transfer->transfer_quantity;
            
            if ($transfer->transfer_from === 'Dispensary' && $transfer->transfer_to === 'Store') {
                $totalDispensaryToStore += $transfer->transfer_quantity;
            } elseif ($transfer->transfer_from === 'Store' && $transfer->transfer_to === 'Dispensary') {
                $totalStoreToDispensary += $transfer->transfer_quantity;
            }
        }
        
        // Process records for display
        $transferRecords = [];
        
        foreach ($transfers as $transfer) {
            $medicine = $transfer->medicine;
            
            // Add to records array
            $transferRecords[] = [
                'id' => $transfer->id,
                'date' => Carbon::parse($transfer->created_at)->format('M d, Y h:i A'),
                'medicine' => [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'salt_composition' => $medicine->salt_composition,
                ],
                'category' => [
                    'id' => $medicine->category_id,
                    'name' => $medicine->category ? $medicine->category->name : 'N/A',
                ],
                'brand' => [
                    'id' => $medicine->brand_id,
                    'name' => $medicine->brand ? $medicine->brand->name : 'N/A',
                ],
                'transfer_from' => $transfer->transfer_from,
                'transfer_to' => $transfer->transfer_to,
                'transfer_quantity' => $transfer->transfer_quantity,
                'dispensary_balance' => $transfer->dispensary_balance,
                'store_balance' => $transfer->store_balance,
                'user' => [
                    'id' => $transfer->user_id,
                    'name' => $transfer->user ? $transfer->user->full_name : 'N/A',
                ],
                'direction' => ($transfer->transfer_from === 'Dispensary' && $transfer->transfer_to === 'Store') ? 
                    'dispensary_to_store' : 'store_to_dispensary',
            ];
        }
        
        return [
            'data' => $transferRecords,
            'total' => $transfers->total(),
            'per_page' => $this->perPage,
            'current_page' => $transfers->currentPage(),
            'last_page' => $transfers->lastPage(),
            'total_transfer_quantity' => $totalTransferQuantity,
            'total_dispensary_to_store' => $totalDispensaryToStore,
            'total_store_to_dispensary' => $totalStoreToDispensary,
            'paginator' => $transfers,
        ];
    }

    public function render()
    {
        $transfers = $this->getTransfers();
        
        return view('livewire.medicine-transfer-report', [
            'transfers' => $transfers,
        ]);
    }
}
