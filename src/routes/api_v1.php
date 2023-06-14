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
use App\Modules\Authentication\Controllers\VerifyRegisteredUserController;
use App\Modules\Cart\Controllers\ApplyCouponController;
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
use App\Modules\Enquiry\Controllers\EnquiryCreateController;
use App\Modules\Enquiry\Controllers\EnquiryDeleteController;
use App\Modules\Enquiry\Controllers\EnquiryDetailController;
use App\Modules\Enquiry\Controllers\EnquiryPaginateController;
use App\Modules\Enquiry\Controllers\EnquiryUpdateController;
use App\Modules\Product\Controllers\ProductCreateController;
use App\Modules\Product\Controllers\ProductDeleteController;
use App\Modules\Product\Controllers\ProductDetailController;
use App\Modules\Product\Controllers\ProductPaginateController;
use App\Modules\Product\Controllers\ProductUpdateController;
use App\Modules\ProductImage\Controllers\ProductImageCreateController;
use App\Modules\ProductImage\Controllers\ProductImageDeleteController;
use App\Modules\ProductImage\Controllers\ProductImageDetailController;
use App\Modules\ProductImage\Controllers\ProductImagePaginateController;
use App\Modules\ProductImage\Controllers\ProductImageUpdateController;
use App\Modules\ProductReview\Controllers\ProductReviewCreateController;
use App\Modules\ProductReview\Controllers\ProductReviewDeleteController;
use App\Modules\ProductReview\Controllers\ProductReviewDetailController;
use App\Modules\ProductReview\Controllers\ProductReviewPaginateController;
use App\Modules\ProductReview\Controllers\ProductReviewUpdateController;
use App\Modules\Wishlist\Controllers\WishlistDetailController;
use App\Modules\Wishlist\Controllers\WishlistSaveController;
use App\Modules\Cart\Controllers\CartDetailController;
use App\Modules\Cart\Controllers\CartSaveController;
use App\Modules\Coupon\Controllers\CouponCreateController;
use App\Modules\Coupon\Controllers\CouponDeleteController;
use App\Modules\Coupon\Controllers\CouponDetailController;
use App\Modules\Coupon\Controllers\CouponPaginateController;
use App\Modules\Coupon\Controllers\CouponUpdateController;
use App\Modules\Order\Controllers\OrderPlacedDetailController;
use App\Modules\Order\Controllers\OrderPlacedPaginateController;
use App\Modules\Order\Controllers\PlaceOrderController;
use App\Modules\Order\Controllers\VerifyPaymentController;

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

Route::prefix('/email/verify')->group(function () {
    Route::post('/resend-notification', [VerifyRegisteredUserController::class, 'resend_notification', 'as' => 'resend_notification'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::get('/{id}/{hash}', [VerifyRegisteredUserController::class, 'verify_email', 'as' => 'verify_email'])->middleware(['signed'])->name('verification.verify');
});

Route::prefix('enquiry')->group(function () {
    Route::post('/create', [EnquiryCreateController::class, 'post'])->name('enquiry.create');
});

Route::middleware(['auth:sanctum'])->group(function () {

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

    Route::prefix('wishlist')->group(function () {
        Route::post('/', [WishlistSaveController::class, 'post'])->name('wishlist.create');
        Route::get('/', [WishlistDetailController::class, 'get'])->name('wishlist.get');
    });

    Route::prefix('cart')->group(function () {
        Route::post('/', [CartSaveController::class, 'post'])->name('cart.create');
        Route::get('/', [CartDetailController::class, 'get'])->name('cart.get');
        Route::post('/apply-coupon', [ApplyCouponController::class, 'post'])->name('coupon.apply');
    });

    Route::prefix('order')->group(function () {
        Route::post('/place', [PlaceOrderController::class, 'post'])->name('order.place');
        Route::get('/placed/paginate', [OrderPlacedPaginateController::class, 'get'])->name('order.placed.paginate');
        Route::get('/placed/detail/{receipt}', [OrderPlacedDetailController::class, 'get'])->name('order.placed.detail');
        Route::post('/verify-payment', [VerifyPaymentController::class, 'post'])->name('order.verify_payment');
    });

    Route::prefix('enquiry')->group(function () {
        Route::get('/paginate', [EnquiryPaginateController::class, 'get'])->name('enquiry.paginate');
        Route::post('/create', [EnquiryCreateController::class, 'post'])->name('enquiry.create');
        Route::post('/update/{id}', [EnquiryUpdateController::class, 'post'])->name('enquiry.update');
        Route::delete('/delete/{id}', [EnquiryDeleteController::class, 'delete'])->name('enquiry.delete');
        Route::get('/detail/{id}', [EnquiryDetailController::class, 'get'])->name('enquiry.get');
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
        Route::prefix('{product_id}')->group(function () {
            Route::prefix('images')->group(function () {
                Route::get('/paginate', [ProductImagePaginateController::class, 'get'])->name('product.image.paginate');
                Route::post('/create', [ProductImageCreateController::class, 'post'])->name('product.image.create');
                Route::post('/update/{id}', [ProductImageUpdateController::class, 'post'])->name('product.image.update');
                Route::delete('/delete/{id}', [ProductImageDeleteController::class, 'delete'])->name('product.image.delete');
                Route::get('/detail/{id}', [ProductImageDetailController::class, 'get'])->name('product.image.get');
            });
            Route::prefix('reviews')->group(function () {
                Route::get('/paginate', [ProductReviewPaginateController::class, 'get'])->name('product.review.paginate');
                Route::post('/create', [ProductReviewCreateController::class, 'post'])->name('product.review.create');
                Route::post('/update/{id}', [ProductReviewUpdateController::class, 'post'])->name('product.review.update');
                Route::delete('/delete/{id}', [ProductReviewDeleteController::class, 'delete'])->name('product.review.delete');
                Route::get('/detail/{id}', [ProductReviewDetailController::class, 'get'])->name('product.review.get');
            });
        });
    });

    Route::prefix('coupon')->group(function () {
        Route::get('/paginate', [CouponPaginateController::class, 'get'])->name('coupon.paginate');
        Route::post('/create', [CouponCreateController::class, 'post'])->name('coupon.create');
        Route::post('/update/{id}', [CouponUpdateController::class, 'post'])->name('coupon.update');
        Route::delete('/delete/{id}', [CouponDeleteController::class, 'delete'])->name('coupon.delete');
        Route::get('/detail/{id}', [CouponDetailController::class, 'get'])->name('coupon.get');
    });

    Route::post('/auth/logout', [LogoutController::class, 'post', 'as' => 'logout'])->name('logout');
});

Route::get('/unauthenticated', function (Request $request) {
   throw new UnauthenticatedException("Unauthenticated", 401);
})->name('unauthenticated');
