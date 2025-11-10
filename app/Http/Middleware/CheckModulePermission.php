<?php
namespace App\Http\Middleware;

use Auth;
use Closure;
use Flash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $module   // module name (dashboard, users, payroll, etc.)
     * @param  string  $action   // permission type (view, add, edit, delete)
     */
    public function handle(Request $request, Closure $next, $module = null, $action = null)
    {
        
		$user = Auth::user();
		
        if (! $user) {
            abort(403, 'Unauthorized');			
        }
	
        $hasPermission = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $user->role_id)
            ->where('permissions.name', $module)        // match module name
            ->where('role_has_permissions.permission', $action) // match action
            ->exists();
		
        if (! $hasPermission) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
?>