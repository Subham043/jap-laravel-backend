<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;

class UserDeleteController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('permission:delete users', ['only' => ['delete']]);
        $this->userService = $userService;
    }

    public function delete($id){
        $user = $this->userService->getById($id);

        try {
            //code...
            $this->userService->delete(
                $user
            );
            $this->userService->syncRoles([], $user);
            return response()->json([
                'message' => "User deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again",
            ], 400);
        }
    }

}
