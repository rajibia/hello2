<?php

namespace App\Http\Livewire;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Traits\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LabTable extends LivewireTableComponent
{
    //    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'labs.add-button';

    protected $model = Lab::class;

    public $FilterComponent = ['labs.filter-button', Lab::FILTER_STATUS_ARRAY];

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
            ->setDefaultSort('labs.created_at', 'desc')
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
            Column::make(__('messages.lab.lab'), 'name')
                ->view('labs.templates.columns.name')
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
                ->view('labs.templates.columns.status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('labs.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Lab::query()->select('labs.*');
        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 1) {
                $q->where('status', Lab::ACTIVE);
            }
            if ($this->statusFilter == 2) {
                $q->where('status', Lab::INACTIVE);
            }
        });

        return $query;
    }
}
