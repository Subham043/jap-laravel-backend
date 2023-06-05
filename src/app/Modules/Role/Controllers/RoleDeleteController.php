<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;

class RoleDeleteController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:delete roles', ['only' => ['delete']]);
        $this->roleService = $roleService;
    }

    public function delete($id){
        $role = $this->roleService->getById($id);

        try {
            //code...
            $this->roleService->delete(
                $role
            );
            $this->roleService->syncPermissions([] ,$role);
            return response()->json([
                'message' => "Role deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
