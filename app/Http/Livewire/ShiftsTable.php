<?php

namespace App\Http\Livewire;

use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ShiftsTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;
    public $buttonComponent = 'shifts.add-button';
    public $filterButtonComponent = 'shifts.filter-button';

    protected $model = Shift::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id'); 
    }

    public function columns(): array
    {
        $actionBtn = Column::make(__('messages.common.action'), 'id')
            ->view('shifts.templates.columns.actions');

        return [
            Column::make('Shift Name', 'shift_name')
                ->view('shifts.templates.columns.shift_name')
                ->sortable()
                ->searchable(),
                
            Column::make('Shift Start', 'shift_start')
                ->sortable()
                ->searchable(),

            Column::make('Shift End', 'shift_end')
                ->sortable()
                ->searchable(),

            Column::make('Break Duration', 'break_duration')
                ->sortable()
                ->searchable(),

            $actionBtn,
        ];
    }
}
