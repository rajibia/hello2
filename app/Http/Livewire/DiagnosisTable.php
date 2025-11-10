<?php

namespace App\Http\Livewire;

use App\Models\Diagnosis;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DiagnosisTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'diagnosis.add-button';

    public $FilterComponent = ['diagnosis.filter-button', Diagnosis::FILTER_STATUS_ARRAY];

    protected $model = Diagnosis::class;

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
            ->setDefaultSort('diagnosis.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            return [
                'class' => '',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '7') {
                return [
                    'class' => 'w-100px',
                ];
            }
            
            if ($column->isField('grouping') || $column->isField('insurance_name') || $column->isField('age') || $column->isField('non_insured_amount') || $column->isField('name') || $column->isField('tariff') || $column->isField('status')) {
                return [
                    'class' => 'p-3',
                ];
            }

            return [];
        });
        $this->setThAttributes(function (Column $column) {
            // if ($column->isField('tariff')) {
            //     return [
            //         'class' => 'text-end',
            //         'style' => 'padding-right: 7rem !important',
            //     ];
            // }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.package.diagnosis'), 'name')
                ->view('diagnosis.templates.columns.name')
                ->sortable()->searchable(),
            // Column::make(__('messages.diagnosis.quantity'), 'quantity')->view('diagnosis.templates.columns.quantity')
            //     ->sortable()->searchable(),
            Column::make('Insurance tarriff', 'tariff')->view('diagnosis.templates.columns.tariff')
                ->sortable()->searchable(),
            Column::make('Top up', 'topup')->view('diagnosis.templates.columns.topup')
                ->sortable()->searchable(),
            Column::make('Non-insured amount', 'non_insured_amount')->view('diagnosis.templates.columns.non_insured_amount')
                ->sortable()->searchable(),
                
            Column::make('G-DRG Code', 'gdrg_code')
                ->sortable()->searchable(),            
            Column::make('ICD 10 Code', 'icd_10_code')
                ->sortable()->searchable(),
            Column::make('Insurance', 'insurance_name')
                    ->sortable()->searchable(),
            Column::make('Age', 'age')
                ->sortable()->searchable(),
            Column::make('Grouping', 'grouping')
                ->sortable()->searchable(),
            Column::make('Speciality Code', 'speciality_code')
                ->sortable()->searchable(),
                
            Column::make(__('messages.common.status'), 'status')->view('diagnosis.templates.columns.status')
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')->view('diagnosis.action'),

        ];
    }

    public function builder(): Builder
    {
        /** @var Diagnosis $query */
        $query = Diagnosis::select('diagnosis.*');

        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == Diagnosis::ACTIVE) {
                $q->where('status', $this->statusFilter);
            }
            if ($this->statusFilter == 2) {
                $q->where('status', Diagnosis::INACTIVE);
            }
        });

        return $query;
    }
}
