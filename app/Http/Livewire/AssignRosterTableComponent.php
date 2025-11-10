<?php

namespace App\Http\Livewire;

use App\Models\AssignRoster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AssignRosterTableComponent extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    // public $showFilterOnHeader = true;
    public $buttonComponent = 'assign_roster.add-button';
    public $filterButtonComponent = 'assign_roster.filter-button';

    // protected $FilterComponent = ['assign_roster.filter-button', AssignRoster::STATUS_ARR];

    protected $model = AssignRoster::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id'); 
    }

    public function columns(): array
    {
        $actionBtn = Column::make(__('messages.common.action'), 'id')
        ->view('assign_roster.templates.columns.actions');

        return [                
            Column::make('Roster Start Date', 'roster_id')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return $row->roster ? $row->roster->start_date : 'N/A';
                }),

            Column::make('Roster End Date', 'roster_id')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return $row->roster ? $row->roster->end_date : 'N/A';
                }),

            Column::make('User', 'user_id')
                ->sortable()
                ->searchable(),
            
            Column::make('Department', 'department_id')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return $row->department ? $row->department->name : 'N/A';
                }),
                
            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable(),
                
            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->searchable(),

            $actionBtn,
        ];
    }

    public function builder(): Builder
    {
        return AssignRoster::query()->orderByDesc('created_at');
    }
}
