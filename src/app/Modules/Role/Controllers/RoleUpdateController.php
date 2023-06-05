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

    public function post(RoleUpdatePostRequest $request, $id){
        $role = $this->roleService->getById($id);

        try {
            //code...
            $this->roleService->update(
                $request->except(['permissions']),
                $role
            );
            $this->roleService->syncPermissions($request->permissions, $role);
            return response()->json([
                'message' => "Role updated successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
