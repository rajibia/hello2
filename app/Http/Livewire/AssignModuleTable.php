<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AssignModuleTable extends DataTableComponent
{
    public $showButtonOnHeader = true;
    public $buttonComponent = 'roles.blank';
    public $showFilterOnHeader = false;

    protected $model = Permission::class;

    public $rolePermissions = [];
	public $role;
	
    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function mount(Role $role)
    {
       $this->role = $role;
	   $this->rolePermissions = \DB::table('role_has_permissions')
		->where('role_id', $role->id)
		->get(['permission_id', 'permission'])
		->groupBy('permission_id')
		->map(fn($items) => $items->pluck('permission')->toArray())
		->toArray();
		//print_r($this->rolePermissions);die;
    }
	
	
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
            'class' => 'text-center font-bold',
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
                ->format(fn($value, $row) => $this->renderCheckbox($row, 'add'))
                ->html(),

            Column::make('Edit', 'edit')
                ->format(fn($value, $row) => $this->renderCheckbox($row, 'edit'))
                ->html(),

            Column::make('Delete', 'delete')
                ->format(fn($value, $row) => $this->renderCheckbox($row, 'delete'))
                ->html(),

            Column::make('View', 'view')
                ->format(fn($value, $row) => $this->renderCheckbox($row, 'view'))
                ->html(),
        ];
    }

    private function renderCheckbox($row, $action)
    {
        if (!$row->$action) {
            return ''; // don't show checkbox if action not available
        }

        $checked = (isset($this->rolePermissions[$row->id]) && in_array($action, $this->rolePermissions[$row->id]))
            ? 'checked'
            : '';

        return '<input type="checkbox" wire:click="togglePermission(' . $row->id . ', \'' . $action . '\')" 
                       name="permissions[' . $row->id . '][]" 
                       value="' . $action . '" ' . $checked . ' />';
    }
	
	public function togglePermission($permissionId, $action)
	{
		$entry = \DB::table('role_has_permissions')
			->where('role_id', $this->role->id)
			->where('permission_id', $permissionId)
			->where('permission', $action)
			->first();
	
		if ($entry) {
			// Uncheck: remove it
			\DB::table('role_has_permissions')
				->where('id', $entry->id)
				->delete();
		} else {
			// Check: insert it
			\DB::table('role_has_permissions')->insert([
				'role_id' => $this->role->id,
				'permission_id' => $permissionId,
				'permission' => $action,
			]);
		}
	
		// Refresh permission state
		$this->rolePermissions = \DB::table('role_has_permissions')
			->where('role_id', $this->role->id)
			->get()
			->groupBy('permission_id')
			->map(fn($items) => $items->pluck('permission')->toArray())
			->toArray();
		
		 $this->dispatchBrowserEvent('permission-updated', [
			'message' => 'Permission updated successfully!',
			'type' => 'success', // optional: 'success', 'error', etc.
		]);
	}

}
