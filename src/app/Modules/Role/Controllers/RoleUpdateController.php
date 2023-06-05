<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use App\Modules\Role\Requests\RoleUpdatePostRequest;

class RoleUpdateController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:edit roles', ['only' => ['get', 'post']]);
        $this->roleService = $roleService;
    }

    public function get($id){
        $data = $this->roleService->getById($id);
        $permissions = $this->roleService->allPermissions();
        $role_permissions = $data->permissions->pluck('name')->toArray();
        return view('admin.pages.role.update', compact(['permissions', 'role_permissions', 'data']));
    }

    public function post(RoleUpdatePostRequest $request, $id){
        $role = $this->roleService->getById($id);

        try {
            //code...
            $this->roleService->update(
                $request->except(['permissions']),
                $role
            );
            $this->roleService->syncPermissions($request->permissions, $role);
            return redirect()->intended(route('role.update.get', $role->id))->with('success_status', 'Role updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->intended(route('role.update.get', $role->id))->with('error_status', 'Something went wrong. Please try again');
        }

    }
}
