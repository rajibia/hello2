<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PermissionTable extends DataTableComponent
{
    public $showButtonOnHeader = true;
    public $buttonComponent = 'permissions.add-button';
    public $showFilterOnHeader = false;

    protected $model = Permission::class;

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
            ->setDefaultSort('id', 'asc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(fn(Column $column) => [
            'class' => 'text-center',
        ]);
		
		$this->setPerPageAccepted([10, 25, 50, 100, 500])
         ->setPerPage(500); // default
		
        $this->setTdAttributes(fn(Column $column, $row) => [
            'class' => 'text-center',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Module', 'name')
                ->format(fn($value, $row) => ucfirst($row->name))
                ->sortable()
                ->searchable(),

            Column::make('Add', 'add')
                ->format(fn($value, $row) => $row->add ? '<span class="badge bg-success">Yes</span>' : '')->html(),

            Column::make('Edit', 'edit')
                ->format(fn($value, $row) => $row->edit ? '<span class="badge bg-success">Yes</span>' : '')->html(),

            Column::make('Delete', 'delete')
                ->format(fn($value, $row) => $row->delete ? '<span class="badge bg-success">Yes</span>' : '')->html(),

            Column::make('View', 'view')
                ->format(fn($value, $row) => $row->view ? '<span class="badge bg-success">Yes</span>' : '')->html(),

            Column::make('Action')
                ->label(fn($row) => view('permissions.action', ['row' => $row])),
        ];
    }
}
