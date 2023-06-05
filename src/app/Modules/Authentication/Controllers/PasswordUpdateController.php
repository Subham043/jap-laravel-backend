<?php

namespace App\Modules\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\RateLimitService;
use App\Modules\User\Services\UserService;
use App\Modules\Authentication\Services\AuthService;
use App\Modules\Authentication\Requests\PasswordPostRequest;

class PasswordUpdateController extends Controller
{
    private $userService;
    private $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function post(PasswordPostRequest $request)
    {
        try {
            //code...
            $user = $this->authService->authenticated_user();
            $this->userService->update(
                $request->only('password'),
                $user
            );
            (new RateLimitService($request))->clearRateLimit();
            return response()->json([
                'message' => "Password Updated successfully.",
            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'message' => "something went wrong. Please try again",
            ], 400);
        }

    }
}
