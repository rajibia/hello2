<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SupplierTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $buttonComponent = 'suppliers.add-button';

    public $FilterComponent = ['suppliers.filter-button', Supplier::FILTER_STATUS_ARR];

    protected $model = Supplier::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function changeFilter($param, $value)
    {
        $this->resetPage($this->getComputedPageName());
        $this->statusFilter = $value;
        $this->setBuilder($this->builder());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('suppliers.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '4') {
                return [
                    'width' => '8%',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.suppliers'), 'name')->view('suppliers.columns.supplier')
                ->sortable()->searchable(),
            Column::make(__('messages.user.phone'), 'phone')->view('suppliers.columns.phone')
                ->sortable()->searchable(),
            Column::make(__('messages.user.address1'), 'address')
                ->sortable()->searchable(),
            Column::make(__('messages.common.status'), 'status')->view('suppliers.columns.status'),
            Column::make(__('messages.common.action'), 'id')->view('suppliers.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Supplier::select('suppliers.*');
        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 1) {
                $q->where('status', Supplier::ACTIVE);
            }
            if ($this->statusFilter == 2) {
                $q->where('status', Supplier::INACTIVE);
            }
        });

        return $query;
    }
}
