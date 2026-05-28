<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles and permissions.
     */
    public function index()
    {
        $roles = Role::whereIn('name', ['owner', 'admin', 'kasir', 'mekanik'])->get();
        $permissions = Permission::all();

        return view('role-permission.index', compact('roles', 'permissions'));
    }

    /**
     * Toggle a permission for a role.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'status' => 'required|boolean',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        // Owner role cannot lose manage-permissions
        if ($role->name === 'owner' && $permission->name === 'manage-permissions' && !$request->status) {
            return response()->json([
                'success' => false,
                'message' => 'Owner must always have manage-permissions privilege!',
            ], 400);
        }

        if ($request->status) {
            $role->givePermissionTo($permission);
            $action = 'granted';
        } else {
            $role->revokePermissionTo($permission);
            $action = 'revoked';
        }

        return response()->json([
            'success' => true,
            'message' => "Permission '{$permission->name}' has been successfully {$action} for role '{$role->name}'.",
        ]);
    }
}
