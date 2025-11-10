<?php

namespace App\Http\Livewire;

use App\Models\Roster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RosterTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;
    
    public $buttonComponent = 'rosters.add-button';
    public $filterButtonComponent = 'rosters.filter-button';

    protected $model = Roster::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id'); 
    }

    public function columns(): array
    {
        $actionBtn = Column::make(__('messages.common.action'), 'id')
        ->view('rosters.templates.columns.actions');
            
        return [
            Column::make('Shift Name', 'shift_id')
                ->view('rosters.templates.columns.shift_name')
                ->sortable()
                ->searchable(),
                
            Column::make('Start Date', 'start_date')
                ->sortable()
                ->searchable(),

            Column::make('End Date', 'end_date')
                ->sortable()
                ->searchable(),

            $actionBtn,
        ];
    }

    public function builder(): Builder
    {
        return Roster::query()->orderByDesc('created_at');
    }
}
