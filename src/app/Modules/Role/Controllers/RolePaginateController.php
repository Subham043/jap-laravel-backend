<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use Illuminate\Http\Request;

class RolePaginateController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:list roles', ['only' => ['get']]);
        $this->roleService = $roleService;
    }

    public function get(Request $request){
        $data = $this->roleService->paginate($request->total ?? 10);
        return view('admin.pages.role.paginate', compact(['data']))
            ->with('search', $request->query('filter')['search'] ?? '');
    }

}
