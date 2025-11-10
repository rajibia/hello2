<?php

namespace App\Http\Livewire;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UnitTable extends LivewireTableComponent
{
    protected $model = Unit::class;
    
    public $showButtonOnHeader = true;
    
    public $buttonComponent = 'units.add-button';
    
    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    
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
        $this->setPrimaryKey('id')
            ->setDefaultSort('units.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'w-75 ps-125 pe-5 text-center',
                    'style' => 'padding-left: 150px !important',
                ];
            }

            return [
                'class' => 'w-75',
            ];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('name')) {
                return [
                    'class' => 'p-5',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('units.action'),
        ];
    }

    public function builder(): Builder
    {
        return Unit::query()->select('units.*');
    }
}
