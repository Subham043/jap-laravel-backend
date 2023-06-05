<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use App\Modules\Role\Requests\RoleCreatePostRequest;

class RoleCreateController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:create roles', ['only' => ['get','post']]);
        $this->roleService = $roleService;
    }

    public function get(){
        $permissions = $this->roleService->allPermissions();
        return view('admin.pages.role.create', compact('permissions'));
    }

    public function post(RoleCreatePostRequest $request){

        try {
            //code...
            $role = $this->roleService->create(
                $request->except('permissions')
            );
            $this->roleService->syncPermissions($request->permissions, $role);
            return redirect()->intended(route('role.create.get'))->with('success_status', 'Role created successfully.');
        } catch (\Throwable $th) {
            return redirect()->intended(route('role.create.get'))->with('error_status', 'Something went wrong. Please try again');
        }

    }
}
