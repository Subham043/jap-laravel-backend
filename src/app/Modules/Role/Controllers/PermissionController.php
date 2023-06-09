<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Resources\PermissionCollection;
use App\Modules\Role\Services\RoleService;

class PermissionController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:list roles', ['only' => ['get']]);
        $this->roleService = $roleService;
    }

    public function get(){
        $permissions = $this->roleService->allPermissions();
        return response()->json([
            'permissions' => PermissionCollection::collection($permissions),
        ], 200);
    }

}
