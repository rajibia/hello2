<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;

class PermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
		
        // Group permissions by module
        $permissions = Permission::all();		
		return view('permissions.index', compact(['roles','permissions']));
    }
	
	public function edit(Permission $permission)
    {
        return response()->json([
			'success' => true,
			'data' => $permission,
			'message' => 'Permission retrieved successfully.'
		]);
    }
	
	public function update(Request $request, Permission $permission)
    {
        $request->validate([
          	'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        // Exclude _method to prevent mass assignment errors
        $input = $request->except('_method');

        // Extract permissions array, default to empty array if not set
		$actions = $input['permissions'] ?? [];
		
		// Prepare boolean flags for each permission action
		$add = in_array('add', $actions) ? 1 : 0;
		$edit = in_array('edit', $actions) ? 1 : 0;
		$delete = in_array('delete', $actions) ? 1 : 0;
		$view = in_array('view', $actions) ? 1 : 0;
		
		// Update the permission model
		$permission->update([
			'name' => $input['name'],
			'add' => $add,
			'edit' => $edit,
			'delete' => $delete,
			'view' => $view,
		]);

        return response()->json([
            'success' => true,
            'message' => __('messages.permission-settings.permission') . ' ' . __('messages.common.updated_successfully'),
            'data' => $permission
        ]);
    }
	
	public function getPermissions(Role $role)
	{
		$permissions = $role->permissions()
			->select('module_id', 'type') // type = add/edit/delete/view
			->get();
	
		return response()->json([
			'success' => true,
			'permissions' => $permissions
		]);
	}
	
	public function store(Request $request)
    {
        $request->validate([
            'module' => 'required|string|max:255|unique:permissions,name',
            'permissions' => 'array'
        ]);

        $module = strtolower($request->module);
        $actions = $request->permissions ?? []; // e.g. ['add','edit','delete','view']

        
		$permissions = Permission::create([
			'name'   => $module,              // e.g. 'user'
			'add'    => in_array('add', $actions) ? 1 : 0,
			'edit'   => in_array('edit', $actions) ? 1 : 0,
			'delete' => in_array('delete', $actions) ? 1 : 0,
			'view'   => in_array('view', $actions) ? 1 : 0,
			'guard_name' => 'web',            // adjust if needed
		]);
		
        // Return JSON response
		return response()->json([
			'success' => true,
			'message' => __('messages.permission-settings.permission') . ' ' . __('added successfully'),
			'data' => $permissions
		]);
    }
	
	public function destroy(Permission $permission)
	{
		try {
			$permission->delete();
	
			return response()->json([
				'success' => true,
				'message' => __('messages.permission-settings.permission') . ' ' . __('messages.common.deleted_successfully')
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => __('messages.permission-settings.permission') . ' ' . __('messages.common.cant_be_deleted')
			], 500);
		}
	}
}
?>
