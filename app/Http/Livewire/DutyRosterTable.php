<?php

namespace App\Http\Livewire;

use App\Models\AssignRoster;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class DutyRosterTable extends LivewireTableComponent
{
    protected $model = AssignRoster::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Staff', 'user_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->user ? $row->user->first_name . ' ' . $row->user->last_name : 'N/A';
                }),

            Column::make('Date', 'roster_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->roster ? $row->roster->start_date->format('Y-m-d') : 'N/A';
                }),

            Column::make('Shift Start', 'roster.shift.shift_start')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->roster && $row->roster->shift ? $row->roster->shift->shift_start->format('H:i') : 'N/A';
                }),

            Column::make('Shift End', 'roster.shift.shift_end')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->roster && $row->roster->shift ? $row->roster->shift->shift_end->format('H:i') : 'N/A';
                }),

            Column::make('Break Duration', 'roster.shift.break_duration')
                ->format(function ($value, $row) {
                    return $row->roster && $row->roster->shift 
                        ? $row->roster->shift->break_duration . ' mins'
                        : 'N/A';
                }),

            Column::make('Shift', 'roster.shift.shift_name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->roster && $row->roster->shift ? $row->roster->shift->shift_name : 'N/A';
                }),

            Column::make('Department', 'department_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->department ? $row->department->name : 'N/A';
                }),
        ];
    }

    public function builder(): Builder
    {
        return AssignRoster::query()
            ->with(['user', 'roster.shift', 'department']);
    }
}
