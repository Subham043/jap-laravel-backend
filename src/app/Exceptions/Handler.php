<?php

namespace App\Exceptions;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'message' => 'Data not found.'
            ], $e->getStatusCode());
        });
        $this->renderable(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'message' => 'Too many attempts, please try again after sometime'
            ], $e->getStatusCode());
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        });
        $this->renderable(function (UnauthorizedException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        });
        $this->renderable(function (HttpException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        });
        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => "Data not found."
            ], 404);
        });
        $this->renderable(function (DecryptException $e, Request $request) {
            return response()->json([
                'message' => "Oops! You don't have the permission to access this!"
            ], 404);
        });
    }
}
