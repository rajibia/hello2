<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\RadiologyParameter;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class RadiologyParameterTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'radiology_parameter.add-button';

    protected $model = RadiologyParameter::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

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
            ->setDefaultSort('radiology_parameters.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                    'style' => 'padding-right:20px !important',
                ];
            }
            return [];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('name')) {
                return [
                    'class' => 'pt-5',
                ];
            }
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                    'style' => 'padding-right:20px !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()->hideIf('id'),
            Column::make(__('messages.radiology_category.name'), 'parameter_name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.new_change.reference_range'), 'reference_range')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.item.unit'), 'radiologyUnit.name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('radiology_parameter.action'),

        ];
    }

    public function builder(): Builder
    {
        return RadiologyParameter::with('radiologyUnit');
    }
}
