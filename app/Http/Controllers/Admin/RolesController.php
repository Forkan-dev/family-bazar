<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('permissions');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('guard_name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortColumn = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortColumn, $sortDirection);

        // Pagination
        $roles = $query->paginate($request->get('per_page', 10));

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();

        return Inertia::render('Admin/Roles/Form', [
            'permissions' => $permissions,
            'selectedPermissions' => [],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'guard_name' => 'nullable|string',
            'selectedPermissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name]);
        $role->syncPermissions($request->selectedPermissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');

        return Inertia::render('Admin/Roles/Form', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');

        return Inertia::render('Admin/Roles/Form', [
            'role' => $role,
            'permissions' => $permissions,
            'selectedPermissions' => $role->permissions->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'guard_name' => 'nullable|string',
            'selectedPermissions' => 'array',
        ]);

        $role->update(['name' => $request->name, 'guard_name' => $request->guard_name]);
        $role->syncPermissions($request->selectedPermissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function assignRoleForm()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return Inertia::render('Admin/Roles/AssignRole', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Roles assigned successfully.');
    }
}
