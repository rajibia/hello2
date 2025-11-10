<?php

namespace App\Http\Livewire;

use App\Models\ChargeType;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ChargeTypeTable extends LivewireTableComponent
{
    public $buttonComponent = 'charge_types.templates.button.add-button';

    public $showButtonOnHeader = true;

    protected $model = ChargeType::class;

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
            ->setDefaultSort('charge_types.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('name') || $column->isField('description') || $column->isField('charge_type')) {
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
            Column::make('Id', 'id')
                ->sortable()
                ->hideIf('id'),
            Column::make(__('messages.charge_type.charge_type'), 'name')
                ->sortable()
                ->searchable(),
           
            Column::make(__('messages.common.action'), 'id')
                ->view('charge_types.action'),

        ];
    }
}
