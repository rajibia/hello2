<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ExpiryMedicineReport extends Component
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
    public $expiryStatusFilter = 'all';
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
        $this->endDate = Carbon::today()->addMonths(3)->format('Y-m-d');
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
                $this->endDate = Carbon::today()->addMonths(3)->format('Y-m-d');
                // When selecting 'today', also reset other filters to match other reports behavior
                $this->categoryFilter = 'all';
                $this->brandFilter = 'all';
                $this->expiryStatusFilter = 'all';
                $this->searchQuery = '';
                break;
            case 'this_month':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->addMonth()->format('Y-m-d');
                break;
            case 'next_3_months':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->addMonths(3)->format('Y-m-d');
                break;
            case 'next_6_months':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->addMonths(6)->format('Y-m-d');
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
        $this->expiryStatusFilter = 'all';
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addMonths(3)->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getMedicines()
    {
        // Start with Medicine query for expired or expiring medicines
        $query = Medicine::with(['category', 'brand'])
            ->where(function($query) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', Carbon::parse($this->endDate)->endOfDay())
                    ->where('available_quantity', '>', 0); // Only show medicines with stock
            });
            
        // Apply category filter
        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }
        
        // Apply brand filter
        if ($this->brandFilter !== 'all') {
            $query->where('brand_id', $this->brandFilter);
        }
        
        // Apply expiry status filter
        if ($this->expiryStatusFilter !== 'all') {
            if ($this->expiryStatusFilter === 'expired') {
                $query->where('expiry_date', '<', Carbon::today()->format('Y-m-d'));
            } elseif ($this->expiryStatusFilter === 'expiring_soon') {
                // Medicines expiring in the next 30 days
                $query->where('expiry_date', '>=', Carbon::today()->format('Y-m-d'))
                      ->where('expiry_date', '<=', Carbon::today()->addDays(30)->format('Y-m-d'));
            } elseif ($this->expiryStatusFilter === 'expiring_later') {
                // Medicines expiring after 30 days
                $query->where('expiry_date', '>', Carbon::today()->addDays(30)->format('Y-m-d'));
            }
        }
        
        // Apply search query
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('salt_composition', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('category', function (Builder $query) {
                        $query->where('name', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->orWhereHas('brand', function (Builder $query) {
                        $query->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        // Get paginated records
        $medicines = $query->paginate($this->perPage);
        
        // Calculate totals
        $totalBuyingValue = 0;
        $totalSellingValue = 0;
        $totalQuantity = 0;
        $totalAvailableQuantity = 0;
        
        foreach ($medicines as $medicine) {
            $totalBuyingValue += $medicine->buying_price * $medicine->available_quantity;
            $totalSellingValue += $medicine->selling_price * $medicine->available_quantity;
            $totalQuantity += $medicine->quantity;
            $totalAvailableQuantity += $medicine->available_quantity;
        }
        
        // Process records for display
        $medicineRecords = [];
        
        foreach ($medicines as $medicine) {
            // Check expiry status
            $expiryStatus = 'valid';
            $daysUntilExpiry = 0;
            
            if (!empty($medicine->expiry_date)) {
                $expiryDate = Carbon::parse($medicine->expiry_date);
                $today = Carbon::today();
                $daysUntilExpiry = $today->diffInDays($expiryDate, false); // negative if expired
                
                if ($expiryDate->lt($today)) {
                    $expiryStatus = 'expired';
                } elseif ($expiryDate->lt($today->addDays(30))) {
                    $expiryStatus = 'expiring_soon';
                } else {
                    $expiryStatus = 'expiring_later';
                }
            }
            
            // Add to records array
            $medicineRecords[] = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'salt_composition' => $medicine->salt_composition,
                'category' => [
                    'id' => $medicine->category_id,
                    'name' => $medicine->category ? $medicine->category->name : 'N/A',
                ],
                'brand' => [
                    'id' => $medicine->brand_id,
                    'name' => $medicine->brand ? $medicine->brand->name : 'N/A',
                ],
                'buying_price' => $medicine->buying_price,
                'selling_price' => $medicine->selling_price,
                'quantity' => $medicine->quantity,
                'available_quantity' => $medicine->available_quantity,
                'expiry_date' => $medicine->expiry_date ? Carbon::parse($medicine->expiry_date)->format('M d, Y') : 'N/A',
                'days_until_expiry' => $daysUntilExpiry,
                'expiry_status' => $expiryStatus,
                'stock_value' => $medicine->available_quantity * $medicine->buying_price,
                'potential_revenue' => $medicine->available_quantity * $medicine->selling_price,
                'profit_margin' => $medicine->selling_price > 0 ? 
                    (($medicine->selling_price - $medicine->buying_price) / $medicine->selling_price) * 100 : 0,
            ];
        }
        
        return [
            'data' => $medicineRecords,
            'total' => $medicines->total(),
            'per_page' => $this->perPage,
            'current_page' => $medicines->currentPage(),
            'last_page' => $medicines->lastPage(),
            'total_buying_value' => $totalBuyingValue,
            'total_selling_value' => $totalSellingValue,
            'total_quantity' => $totalQuantity,
            'total_available_quantity' => $totalAvailableQuantity,
            'paginator' => $medicines,
        ];
    }

    public function render()
    {
        $medicines = $this->getMedicines();
        
        return view('livewire.expiry-medicine-report', [
            'medicines' => $medicines,
        ]);
    }
}
