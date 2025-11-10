<?php

namespace App\Http\Livewire;

use App\Models\PurchaseMedicine;
use App\Models\StockAdjustment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MedicineStockAdjustmentsTable extends LivewireTableComponent
{
    protected $model = [StockAdjustment::class, 'getSupplierPurchase'];

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'purchase-medicines.action';

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $medicineId;

    public function mount($medicineId)
    {
        $this->medicineId = $medicineId;
    }

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function configure(): void
    {
        $this->setQueryStringStatus(false);
        $this->setDefaultSort('stock_adjustments.created_at', 'desc');
        $this->setPrimaryKey('id');

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

    }

    public function columns(): array
    {
        return [
            Column::make('Initial Dispensary Quantity', 'initial_quantity')
                ->sortable()->searchable(),
            Column::make('Current Dispensary Quantity', 'current_quantity')
                ->sortable()->searchable(),
            Column::make('Initial Store Quantity', 'initial_store_quantity')
                ->sortable()->searchable(),
            Column::make('Current Store Quantity', 'current_store_quantity')
                ->sortable()->searchable(),
            Column::make('User', 'user.first_name')
                ->sortable()->searchable(),
        ];
    }

    public function builder(): Builder
    {
        $query = StockAdjustment::where('medicine_id', $this->medicineId)->with('user')
            ->select('stock_adjustments.*');

        return $query;
    }
}
