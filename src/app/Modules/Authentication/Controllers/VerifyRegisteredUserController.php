<?php

namespace App\Modules\Authentication\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyRegisteredUserController extends Controller
{
    public function index(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json([
                'message' => 'Oops! you are already a verified user.',
            ], 400);
        }
        return response()->json([
            'message' => 'Please verify your email',
        ], 200);
    }

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
        if($request->user()->hasVerifiedEmail()){
            return response()->json([
                'message' => 'Oops! you are already a verified user.',
            ], 400);
        }
        $request->fulfill();
        return response()->json([
            'message' => 'Verified Successfully',
        ], 200);
    }


}
