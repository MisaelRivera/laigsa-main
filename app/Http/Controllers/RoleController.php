<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = RoleResource::collection(Role::all());
        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = PermissionResource::collection(Permission::all())->map(function ($permission) {
            return ['value' => $permission->id, 'label' => $permission->name];
        });
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $validated = $request->validated();
        $role = Role::create($validated);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()
            ->route('roles.index')
            ->with('message', "El rol $role->name y sus permisos han sido creados exitosamente");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $role->load('permissions');
        $permissions = PermissionResource::collection(Permission::all())->map(function ($permission) {
            return ['value' => $permission->id, 'label' => $permission->name];
        });
       return Inertia::render('Admin/Roles/Edit', [
        'role' =>  new RoleResource($role),
        'permissions' => $permissions
       ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->name = $validated['name'];
        $role->update();
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()
            ->route('roles.index')
            ->with('message', "El rol $role->name y sus permisos han sido editados correctamente");
    }

    public function removePermission (Role $role, Permission $permission)
    {
        $permissionName = $permission->name;
        $role->revokePermissionTo($permission);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()
            ->route('roles.index');
    }
}
