<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\UserCreatePostRequest;
use App\Modules\user\Resources\UserCollection;
use App\Modules\User\Services\UserService;

class UserCreateController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('permission:create users', ['only' => ['get','post']]);
        $this->userService = $userService;
    }

    public function post(UserCreatePostRequest $request){

        try {
            //code...
            $user = $this->userService->create(
                $request->except('role')
            );
            if($request->role){
                $this->userService->syncRoles([$request->role], $user);
            }
            return response()->json([
                'message' => "User created successfully.",
                'user' => UserCollection::make($user),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }

    }
}
