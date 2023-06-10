<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Resources\UserCollection;
use App\Modules\User\Services\UserService;

class UserDetailController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('permission:list users', ['only' => ['get']]);
        $this->userService = $userService;
    }

    public function get($id){
        $user = $this->userService->getById($id);

        return response()->json([
            'user' => UserCollection::make($user),
        ], 200);
    }

}
