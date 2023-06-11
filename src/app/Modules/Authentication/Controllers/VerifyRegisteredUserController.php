<?php

namespace App\Modules\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyRegisteredUserController extends Controller
{

    public function resend_notification(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json([
                'message' => 'Oops! you are already a verified user.',
            ], 400);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'message' => 'Verification link sent to your registered email.',
        ], 200);
    }

    public function verify_email(EmailVerificationRequest $request, $id, $hash){
        $request->fulfill();
        return redirect('http://localhost:8000');
    }


}
