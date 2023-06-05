<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\UserUpdatePostRequest;
use App\Modules\User\Services\UserService;

class UserUpdateController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('permission:edit users', ['only' => ['get', 'post']]);
        $this->userService = $userService;
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
            if($request->role){
                $this->userService->syncRoles([$request->role], $user);
            }
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
                $request->user()->sendEmailVerificationNotification();
                $request->user()->save();
            }
            return response()->json([
                'message' => "User updated successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
