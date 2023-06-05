<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Resources\RoleCollection;
use App\Modules\Role\Services\RoleService;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:list roles', ['only' => ['get']]);
        $this->roleService = $roleService;
    }

    public function get(){
        $role = $this->roleService->all();
        return response()->json([
            'role' => RoleCollection::collection($role),
        ], 200);
    }

}
