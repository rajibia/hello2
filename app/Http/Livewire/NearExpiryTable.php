<?php

namespace App\Http\Livewire;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NearExpiryTable extends LivewireTableComponent
{
    protected $model = Medicine::class;

    public $showButtonOnHeader = false;

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
            ->setDefaultSort('expiry_date', 'asc')
            ->setQueryStringStatus(false);
    }

    public function builder(): Builder
    {
        $today = now();
        $threshold = now()->addDays(30); // Change this to 60 or 90 for your logic

        return Medicine::with('category', 'brand')
            ->whereBetween('expiry_date', [$today, $threshold])
            ->select('medicines.*');
    }

    public function columns(): array
    {
        return [
            Column::make('Medicine', 'name')
                ->view('medicines.templates.columns.name')
                ->searchable()
                ->sortable(),

            Column::make('Category', 'category.name')
                ->searchable()
                ->sortable(),

            Column::make('Store Quantity', 'store_quantity')
                ->view('medicines.templates.columns.store_quantity')
                ->sortable(),

            Column::make('Available Quantity', 'available_quantity')
                ->view('medicines.templates.columns.avalable_quantity')
                ->sortable(),

            Column::make('Expiry Date', 'expiry_date')
                ->view('medicines.templates.columns.expiry_date')
                ->sortable(),

            Column::make('Status')
                ->label(fn($row) => view('medicines.templates.columns.near_expiry', ['row' => $row])),

            Column::make('Action', 'id')
                ->view('medicines.action'),
        ];
    }

    public function render()
    {
        return parent::render();
    }
}
