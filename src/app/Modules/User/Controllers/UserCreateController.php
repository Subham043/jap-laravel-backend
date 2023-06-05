<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use App\Modules\User\Requests\UserCreatePostRequest;
use App\Modules\User\Services\UserService;

class UserCreateController extends Controller
{
    private $userService;
    private $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->middleware('permission:create users', ['only' => ['get','post']]);
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function get(){
        $roles = $this->roleService->all();
        return view('admin.pages.user.create', compact('roles'));
    }

    public function post(UserCreatePostRequest $request){

        try {
            //code...
            $user = $this->userService->create(
                $request->except('role')
            );
            $this->userService->syncRoles([$request->role], $user);
            return redirect()->intended(route('user.create.get'))->with('success_status', 'User created successfully.');
        } catch (\Throwable $th) {
            return redirect()->intended(route('user.create.get'))->with('error_status', 'Something went wrong. Please try again');
        }

    }
}
