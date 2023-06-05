<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;

class UserDeleteController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('permission:delete users', ['only' => ['get']]);
        $this->userService = $userService;
    }

    public function get($id){
        $user = $this->userService->getById($id);

        try {
            //code...
            $this->userService->delete(
                $user
            );
            $this->userService->syncRoles([], $user);
            return redirect()->back()->with('success_status', 'User deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_status', 'Something went wrong. Please try again');
        }
    }

}
