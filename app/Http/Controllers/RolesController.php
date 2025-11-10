<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
		return view('roles.index', compact('roles'));
    }
	
	public function module_assign(Role $role)
    {
        $roleId	=	$role->id;
		$permissions = Permission::all();
		$rolePermissions = \DB::table('role_has_permissions')
        ->where('role_id', $roleId)
        ->pluck('permission_id')
        ->toArray();
		//print_r($rolePermissions);die;
		return view('roles.module_assign', compact(['permissions','role','rolePermissions']));
    }
	
	 
	public function edit(Role $role)
    {
        return response()->json([
			'success' => true,
			'data' => $role,
			'message' => 'Role retrieved successfully.'
		]);
    }
	
	public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        // Exclude _method to prevent mass assignment errors
        $input = $request->except('_method');

        $role->update($input);

        return response()->json([
            'success' => true,
            'message' => __('messages.role-settings.role') . ' ' . __('messages.common.updated_successfully'),
            'data' => $role
        ]);
    }

    public function destroy(Role $role)
	{
		try {
			$role->delete();
	
			return response()->json([
				'success' => true,
				'message' => __('messages.role-settings.role') . ' ' . __('messages.common.deleted_successfully')
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => __('messages.role-settings.role') . ' ' . __('messages.common.cant_be_deleted')
			], 500);
		}
	}

	
	public function store(Request $request)
	{
		// Validate input
		$request->validate([
			'name' => 'required|string|max:255|unique:roles,name',
		]);
	
		// Create new role
		$role = Role::create([
			'name' => $request->name,
			'gaurd_name' => 'web'
		]);
	
		// Return JSON response
		return response()->json([
			'success' => true,
			'message' => __('messages.role-settings.role') . ' ' . __('add successfully'),
			'data' => $role
		]);
	}

}

?>
