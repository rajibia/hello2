<?php

namespace App\Http\Livewire;

use App\Models\Scan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Traits\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ScanTable extends LivewireTableComponent
{
    //    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'scans.add-button';

    protected $model = Scan::class;

    public $FilterComponent = ['scans.filter-button', Scan::FILTER_STATUS_ARRAY];

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
            ->setDefaultSort('scans.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            // if ($column->isField('service_tax') || $column->isField('hospital_rate') || $column->isField('total')) {
                // return [
                //     'class' => 'text-end',
                //     'style' => 'padding-right: 2rem !important',
                // ];
            // }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.scan.scan'), 'name')
                ->view('scans.templates.columns.name')
                ->sortable()
                ->searchable(),
            Column::make('Isurance', 'insurance_name')
                    ->sortable()
                    ->searchable(),
            Column::make('Tariff / Rate', 'tariff')
                ->sortable()
                ->searchable(),
            Column::make('Topup', 'topup')
                ->sortable()
                ->searchable(),
            Column::make('Non-insured amount', 'non_insured_amount')
                ->sortable()
                ->searchable(),    
            Column::make('GDRG code', 'gdrg_code')
                ->sortable()
                ->searchable(),    
            
            Column::make(__('messages.common.status'), 'status')
                ->view('scans.templates.columns.status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('scans.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Scan::query()->select('scans.*');
        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 1) {
                $q->where('status', Scan::ACTIVE);
            }
            if ($this->statusFilter == 2) {
                $q->where('status', Scan::INACTIVE);
            }
        });

        return $query;
    }
}
