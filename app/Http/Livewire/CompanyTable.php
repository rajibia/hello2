<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CompanyTable extends LivewireTableComponent
{
    protected $model = Company::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc');
    }

    public function builder(): Builder
    {
        return Company::select('id', 'name', 'code', 'is_active', 'created_at');
    }


    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'is_active')
                ->format(fn($value, $row) => $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>')
                ->html(),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('d M Y')),

            Column::make('Action')
                ->label(fn($row) => view('company.columns.action', ['row' => $row])),
        ];
    }

    public function render()
    {
        return parent::render();
    }
}
