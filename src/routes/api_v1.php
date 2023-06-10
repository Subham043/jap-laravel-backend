<?php

use App\Exceptions\CustomExceptions\UnauthenticatedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Modules\Authentication\Controllers\PasswordUpdateController;
use App\Modules\Authentication\Controllers\ForgotPasswordController;
use App\Modules\Authentication\Controllers\LoginController;
use App\Modules\Authentication\Controllers\LogoutController;
use App\Modules\Authentication\Controllers\ProfileController;
use App\Modules\Authentication\Controllers\RegisterController;
use App\Modules\Authentication\Controllers\ResetPasswordController;
use App\Modules\Role\Controllers\PermissionController;
use App\Modules\Role\Controllers\RoleController;
use App\Modules\Role\Controllers\RoleCreateController;
use App\Modules\Role\Controllers\RoleDeleteController;
use App\Modules\Role\Controllers\RoleDetailController;
use App\Modules\Role\Controllers\RolePaginateController;
use App\Modules\Role\Controllers\RoleUpdateController;
use App\Modules\User\Controllers\UserCreateController;
use App\Modules\User\Controllers\UserDeleteController;
use App\Modules\User\Controllers\UserDetailController;
use App\Modules\User\Controllers\UserPaginateController;
use App\Modules\User\Controllers\UserUpdateController;
use App\Modules\Category\Controllers\CategoryCreateController;
use App\Modules\Category\Controllers\CategoryDeleteController;
use App\Modules\Category\Controllers\CategoryDetailController;
use App\Modules\Category\Controllers\CategoryPaginateController;
use App\Modules\Category\Controllers\CategoryUpdateController;
use App\Modules\Product\Controllers\ProductCreateController;
use App\Modules\Product\Controllers\ProductDeleteController;
use App\Modules\Product\Controllers\ProductDetailController;
use App\Modules\Product\Controllers\ProductPaginateController;
use App\Modules\Product\Controllers\ProductUpdateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'post'])->name('login');
    Route::post('/register', [RegisterController::class, 'post'])->name('register');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'post'])->name('forgot_password');
    Route::post('/reset-password/{token}', [ResetPasswordController::class, 'post'])->name('reset_password')->middleware('signed');
});

Route::prefix('/email/verify')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [VerifyRegisteredUserController::class, 'index', 'as' => 'index'])->name('verification.notice');
    Route::post('/resend-notification', [VerifyRegisteredUserController::class, 'resend_notification', 'as' => 'resend_notification'])->middleware(['throttle:6,1'])->name('verification.send');
    Route::get('/{id}/{hash}', [VerifyRegisteredUserController::class, 'verify_email', 'as' => 'verify_email'])->middleware(['signed'])->name('verification.verify');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'get', 'as' => 'profile.get'])->name('profile.get');
        Route::post('/update', [ProfileController::class, 'post', 'as' => 'profile.post'])->name('profile.post');
        Route::post('/update-password', [PasswordUpdateController::class, 'post', 'as' => 'password.post'])->name('password.post');
    });

    Route::get('/permissions', [PermissionController::class, 'get'])->name('permission.all');
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'get'])->name('role.all');
        Route::get('/paginate', [RolePaginateController::class, 'get'])->name('role.paginate');
        Route::post('/create', [RoleCreateController::class, 'post'])->name('role.create');
        Route::post('/update/{id}', [RoleUpdateController::class, 'post'])->name('role.update');
        Route::delete('/delete/{id}', [RoleDeleteController::class, 'delete'])->name('role.delete');
        Route::get('/detail/{id}', [RoleDetailController::class, 'get'])->name('role.get');
    });

    Route::prefix('user')->group(function () {
        Route::get('/paginate', [UserPaginateController::class, 'get'])->name('user.paginate');
        Route::post('/create', [UserCreateController::class, 'post'])->name('user.create');
        Route::post('/update/{id}', [UserUpdateController::class, 'post'])->name('user.update');
        Route::delete('/delete/{id}', [UserDeleteController::class, 'delete'])->name('user.delete');
        Route::get('/detail/{id}', [UserDetailController::class, 'get'])->name('user.get');
    });

    Route::prefix('category')->group(function () {
        Route::get('/paginate', [CategoryPaginateController::class, 'get'])->name('category.paginate');
        Route::post('/create', [CategoryCreateController::class, 'post'])->name('category.create');
        Route::post('/update/{id}', [CategoryUpdateController::class, 'post'])->name('category.update');
        Route::delete('/delete/{id}', [CategoryDeleteController::class, 'delete'])->name('category.delete');
        Route::get('/detail/{id}', [CategoryDetailController::class, 'get'])->name('category.get');
    });

    Route::prefix('product')->group(function () {
        Route::get('/paginate', [ProductPaginateController::class, 'get'])->name('product.paginate');
        Route::post('/create', [ProductCreateController::class, 'post'])->name('product.create');
        Route::post('/update/{id}', [ProductUpdateController::class, 'post'])->name('product.update');
        Route::delete('/delete/{id}', [ProductDeleteController::class, 'delete'])->name('product.delete');
        Route::get('/detail/{id}', [ProductDetailController::class, 'get'])->name('product.get');
    });

    Route::post('/auth/logout', [LogoutController::class, 'post', 'as' => 'logout'])->name('logout');
});

Route::get('/unauthenticated', function (Request $request) {
   throw new UnauthenticatedException("Unauthenticated", 401);
})->name('unauthenticated');
