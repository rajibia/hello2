<?php

namespace App\Http\Livewire;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class StockReport extends Component
{
    use WithPagination;

    public $searchQuery = '';
    public $categoryFilter = 'all';
    public $brandFilter = 'all';
    public $stockStatusFilter = 'all';
    public $expiryFilter = 'all';
    public $perPage = 10;
    public $categories = [];
    public $brands = [];
    public $stockStatuses = [];
    public $expiryStatuses = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        // Load all categories for the filter dropdown
        $this->categories = Category::orderBy('name')->pluck('name', 'id')->toArray();
        $this->categories = ['all' => 'All Categories'] + $this->categories;
        
        // Load all brands for the filter dropdown
        $this->brands = Brand::orderBy('name')->pluck('name', 'id')->toArray();
        $this->brands = ['all' => 'All Brands'] + $this->brands;
        
        // Stock status options
        $this->stockStatuses = [
            'all' => 'All Stock',
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock (< 10)',
            'out_of_stock' => 'Out of Stock',
        ];
        
        // Expiry status options
        $this->expiryStatuses = [
            'all' => 'All Items',
            'expired' => 'Expired',
            'expiring_soon' => 'Expiring Soon (< 30 days)',
            'valid' => 'Valid',
        ];
    }

    public function clearFilters()
    {
        $this->searchQuery = '';
        $this->categoryFilter = 'all';
        $this->brandFilter = 'all';
        $this->stockStatusFilter = 'all';
        $this->expiryFilter = 'all';
        $this->resetPage();
    }

    public function getStockItems()
    {
        // Start with medicines query
        $query = Medicine::query()
            ->with(['category', 'brand']);
        
        // Apply category filter
        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }
        
        // Apply brand filter
        if ($this->brandFilter !== 'all') {
            $query->where('brand_id', $this->brandFilter);
        }
        
        // Apply stock status filter
        switch ($this->stockStatusFilter) {
            case 'in_stock':
                $query->where('available_quantity', '>', 0);
                break;
            case 'low_stock':
                $query->where('available_quantity', '>', 0)
                      ->where('available_quantity', '<', 10);
                break;
            case 'out_of_stock':
                $query->where('available_quantity', '=', 0);
                break;
        }
        
        // Apply expiry filter
        switch ($this->expiryFilter) {
            case 'expired':
                $query->whereDate('expiry_date', '<', Carbon::today());
                break;
            case 'expiring_soon':
                $query->whereDate('expiry_date', '>=', Carbon::today())
                      ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30));
                break;
            case 'valid':
                $query->whereDate('expiry_date', '>', Carbon::today()->addDays(30));
                break;
        }
        
        // Apply search filter
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('salt_composition', 'like', '%' . $this->searchQuery . '%')
                  ->orWhereHas('category', function (Builder $sq) {
                      $sq->where('name', 'like', '%' . $this->searchQuery . '%');
                  })
                  ->orWhereHas('brand', function (Builder $sq) {
                      $sq->where('name', 'like', '%' . $this->searchQuery . '%');
                  });
            });
        }
        
        // Get all medicine records first to calculate totals
        $allMedicines = $query->get();
        
        // Calculate total values
        $totalItems = $allMedicines->count();
        $totalQuantity = $allMedicines->sum('quantity');
        $totalStoreQuantity = $allMedicines->sum('store_quantity');
        $totalAvailableQuantity = $allMedicines->sum('available_quantity');
        $totalValue = $allMedicines->sum(function ($medicine) {
            return $medicine->available_quantity * $medicine->buying_price;
        });
        
        // Get paginated records
        $medicines = $query->orderBy('name')->paginate($this->perPage);
        
        // Process records for display
        $stockItems = [];
        
        foreach ($medicines as $medicine) {
            // Calculate days until expiry
            $daysUntilExpiry = null;
            $expiryStatus = 'N/A';
            
            if ($medicine->expiry_date) {
                $expiryDate = Carbon::parse($medicine->expiry_date);
                $today = Carbon::today();
                
                if ($expiryDate->lt($today)) {
                    $expiryStatus = 'Expired';
                } else {
                    $daysUntilExpiry = $today->diffInDays($expiryDate);
                    if ($daysUntilExpiry <= 30) {
                        $expiryStatus = 'Expiring Soon';
                    } else {
                        $expiryStatus = 'Valid';
                    }
                }
            }
            
            // Determine stock status
            $stockStatus = 'Out of Stock';
            if ($medicine->available_quantity > 0) {
                $stockStatus = $medicine->available_quantity < 10 ? 'Low Stock' : 'In Stock';
            }
            
            // Add to records array
            $stockItems[] = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'category' => $medicine->category ? $medicine->category->name : 'N/A',
                'brand' => $medicine->brand ? $medicine->brand->name : 'N/A',
                'salt_composition' => $medicine->salt_composition ?? 'N/A',
                'buying_price' => $medicine->buying_price,
                'selling_price' => $medicine->selling_price,
                'quantity' => $medicine->quantity,
                'store_quantity' => $medicine->store_quantity,
                'available_quantity' => $medicine->available_quantity,
                'expiry_date' => $medicine->expiry_date ? Carbon::parse($medicine->expiry_date)->format('M d, Y') : 'N/A',
                'days_until_expiry' => $daysUntilExpiry,
                'expiry_status' => $expiryStatus,
                'stock_status' => $stockStatus,
                'total_value' => $medicine->available_quantity * $medicine->buying_price,
            ];
        }
        
        return [
            'data' => $stockItems,
            'total' => $medicines->total(),
            'per_page' => $this->perPage,
            'current_page' => $medicines->currentPage(),
            'last_page' => $medicines->lastPage(),
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'total_store_quantity' => $totalStoreQuantity,
            'total_available_quantity' => $totalAvailableQuantity,
            'total_value' => $totalValue,
            'paginator' => $medicines,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $stockItems = $this->getStockItems();
        
        return view('livewire.stock-report', [
            'stockItems' => $stockItems,
        ]);
    }
}
