<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\StockAdjustment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MedicineAdjustmentReport extends Component
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
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getAdjustments()
    {
        // Start with StockAdjustment query
        $query = StockAdjustment::with(['medicine', 'medicine.category', 'medicine.brand', 'user'])
            ->whereBetween(DB::raw('DATE(created_at)'), [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
            
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
        $adjustments = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        // Calculate totals for dispensary and store changes
        $totalDispensaryChange = 0;
        $totalStoreChange = 0;
        
        foreach ($adjustments as $adjustment) {
            $dispensaryChange = $adjustment->current_quantity - $adjustment->initial_quantity;
            $storeChange = $adjustment->current_store_quantity - $adjustment->initial_store_quantity;
            
            $totalDispensaryChange += $dispensaryChange;
            $totalStoreChange += $storeChange;
        }
        
        // Process records for display
        $adjustmentRecords = [];
        
        foreach ($adjustments as $adjustment) {
            $medicine = $adjustment->medicine;
            $dispensaryChange = $adjustment->current_quantity - $adjustment->initial_quantity;
            $storeChange = $adjustment->current_store_quantity - $adjustment->initial_store_quantity;
            
            // Add to records array
            $adjustmentRecords[] = [
                'id' => $adjustment->id,
                'date' => Carbon::parse($adjustment->created_at)->format('M d, Y h:i A'),
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
                'initial_dispensary_quantity' => $adjustment->initial_quantity,
                'current_dispensary_quantity' => $adjustment->current_quantity,
                'dispensary_change' => $dispensaryChange,
                'initial_store_quantity' => $adjustment->initial_store_quantity,
                'current_store_quantity' => $adjustment->current_store_quantity,
                'store_change' => $storeChange,
                'user' => [
                    'id' => $adjustment->user_id,
                    'name' => $adjustment->user ? $adjustment->user->full_name : 'N/A',
                ],
            ];
        }
        
        return [
            'data' => $adjustmentRecords,
            'total' => $adjustments->total(),
            'per_page' => $this->perPage,
            'current_page' => $adjustments->currentPage(),
            'last_page' => $adjustments->lastPage(),
            'total_dispensary_change' => $totalDispensaryChange,
            'total_store_change' => $totalStoreChange,
            'paginator' => $adjustments,
        ];
    }

    public function render()
    {
        $adjustments = $this->getAdjustments();
        
        return view('livewire.medicine-adjustment-report', [
            'adjustments' => $adjustments,
        ]);
    }
}
