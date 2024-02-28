<?php

namespace App\Modules\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\RateLimitService;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Requests\ResetPasswordPostRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function get($token){

        return view('reset_password');
    }

    public function post(ResetPasswordPostRequest $request, $token){

        $status = Password::reset(
            [...$request->safe()->only('email', 'password', 'password_confirmation'), 'token' => $token],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET){
            (new RateLimitService($request))->clearRateLimit();
            return redirect(route('success'))->with('success_status', __($status));
        }
        return back()->with(['error_status' => __($status)]);

    }
}
