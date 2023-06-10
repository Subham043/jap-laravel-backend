<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use App\Modules\Role\Requests\RoleCreatePostRequest;
use App\Modules\Role\Resources\RoleCollection;

class RoleCreateController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:create roles', ['only' => ['get','post']]);
        $this->roleService = $roleService;
    }

    public function post(RoleCreatePostRequest $request){

        try {
            //code...
            $role = $this->roleService->create(
                $request->safe()->except('permissions')
            );
            $this->roleService->syncPermissions($request->permissions, $role);
            return response()->json([
                'message' => "Role created successfully.",
                'role' => RoleCollection::make($role),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
