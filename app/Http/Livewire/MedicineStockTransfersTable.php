<?php

namespace App\Http\Livewire;

use App\Models\PurchaseMedicine;
use App\Models\StockTransfer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MedicineStockTransfersTable extends LivewireTableComponent
{
    protected $model = [StockTransfer::class, 'getSupplierPurchase'];

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
        $this->setDefaultSort('stock_transfers.created_at', 'desc');
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
            Column::make('Transfer From', 'transfer_from')
                ->sortable()->searchable(),
            Column::make('Transfer To', 'transfer_to')
                ->sortable()->searchable(),
            Column::make('Transfer Quantity', 'transfer_quantity')
                ->sortable()->searchable(),
            Column::make('User', 'user.first_name')
                ->sortable()->searchable(),
        ];
    }

    public function builder(): Builder
    {
        $query = StockTransfer::where('medicine_id', $this->medicineId)->with('user')
            ->select('stock_transfers.*');

        return $query;
    }
}
