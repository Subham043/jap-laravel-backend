<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Services\RoleService;
use App\Modules\User\Requests\UserUpdatePostRequest;
use App\Modules\User\Services\UserService;

class UserUpdateController extends Controller
{
    private $userService;
    private $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->middleware('permission:edit users', ['only' => ['get', 'post']]);
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function get($id){
        $data = $this->userService->getById($id);
        $roles = $this->roleService->all();
        $user_roles = $data->getRoleNames()->toArray();
        return view('admin.pages.user.update', compact(['roles', 'user_roles', 'data']));
    }

    public function post(UserUpdatePostRequest $request, $id){
        $user = $this->userService->getById($id);
        $password = empty($request->password) ? [] : $request->only('password');

        try {
            //code...
            $this->userService->update(
                [...$request->except(['password', 'role']), ...$password],
                $user
            );
            $this->userService->syncRoles([$request->role], $user);
            return redirect()->intended(route('user.update.get', $user->id))->with('success_status', 'User updated successfully.');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->intended(route('user.update.get', $user->id))->with('error_status', 'Something went wrong. Please try again');
        }

    }
}
