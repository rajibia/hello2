<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionHelper
{
    public static function hasModulePermission($module, $action): bool
    {
        $user = Auth::user();		
        if (! $user) {
            return false;
        }
		
        return $hasPermission = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $user->role_id)
            ->where('permissions.name', $module)        // match module name
            ->where('role_has_permissions.permission', $action) // match action
            ->exists();
    }
}
?>