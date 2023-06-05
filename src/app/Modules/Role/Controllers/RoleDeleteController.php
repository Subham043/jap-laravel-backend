<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;

class RoleDeleteController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:delete roles', ['only' => ['get']]);
        $this->roleService = $roleService;
    }

    public function get($id){
        $role = $this->roleService->getById($id);

        try {
            //code...
            $this->roleService->delete(
                $role
            );
            $this->roleService->syncPermissions([] ,$role);
            return redirect()->back()->with('success_status', 'Role deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_status', 'Something went wrong. Please try again');
        }
    }

}
