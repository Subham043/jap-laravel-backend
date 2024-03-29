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
use App\Modules\Category\Controllers\MainCategoryDetailController;
use App\Modules\Category\Controllers\MainCategoryPaginateController;
use App\Modules\Contact\Controllers\ContactCreateController;
use App\Modules\Contact\Controllers\ContactDeleteController;
use App\Modules\Contact\Controllers\ContactDetailController;
use App\Modules\Contact\Controllers\ContactPaginateController;
use App\Modules\Contact\Controllers\ContactUpdateController;
use App\Modules\Coupon\Controllers\CouponCreateController;
use App\Modules\Coupon\Controllers\CouponDeleteController;
use App\Modules\Coupon\Controllers\CouponDetailController;
use App\Modules\Coupon\Controllers\CouponPaginateController;
use App\Modules\Coupon\Controllers\CouponUpdateController;
use App\Modules\DeliveryCharge\Controllers\DeliveryChargeDetailController;
use App\Modules\DeliveryCharge\Controllers\DeliveryChargeSaveController;
use App\Modules\Gallery\Controllers\GalleryCreateController;
use App\Modules\Gallery\Controllers\GalleryDeleteController;
use App\Modules\Gallery\Controllers\GalleryDetailController;
use App\Modules\Gallery\Controllers\GalleryPaginateController;
use App\Modules\Gallery\Controllers\GalleryUpdateController;
use App\Modules\Gallery\Controllers\MainGalleryPaginateController;
use App\Modules\GalleryCategory\Controllers\GalleryCategoryCreateController;
use App\Modules\GalleryCategory\Controllers\GalleryCategoryDeleteController;
use App\Modules\GalleryCategory\Controllers\GalleryCategoryDetailController;
use App\Modules\GalleryCategory\Controllers\GalleryCategoryPaginateController;
use App\Modules\GalleryCategory\Controllers\GalleryCategoryUpdateController;
use App\Modules\GalleryCategory\Controllers\MainGalleryCategoryPaginateController;
use App\Modules\HomePage\Banner\Controllers\BannerCreateController;
use App\Modules\HomePage\Banner\Controllers\BannerDeleteController;
use App\Modules\HomePage\Banner\Controllers\BannerDetailController;
use App\Modules\HomePage\Banner\Controllers\BannerPaginateController;
use App\Modules\HomePage\Banner\Controllers\BannerUpdateController;
use App\Modules\HomePage\Banner\Controllers\UserBannerAllController;
use App\Modules\Order\Controllers\OrderDetailController;
use App\Modules\Order\Controllers\OrderLatestBillingInfoController;
use App\Modules\Order\Controllers\OrderPaginateController;
use App\Modules\Order\Controllers\OrderPlacedCancelController;
use App\Modules\Order\Controllers\OrderPlacedDetailController;
use App\Modules\Order\Controllers\OrderPlacedPaginateController;
use App\Modules\Order\Controllers\OrderStatusController;
use App\Modules\Order\Controllers\PlaceOrderController;
use App\Modules\Order\Controllers\VerifyPaymentController;
use App\Modules\Pincode\Controllers\PincodeCreateController;
use App\Modules\Pincode\Controllers\PincodeDeleteController;
use App\Modules\Pincode\Controllers\PincodeDetailController;
use App\Modules\Pincode\Controllers\PincodePaginateController;
use App\Modules\Pincode\Controllers\PincodeUpdateController;
use App\Modules\Product\Controllers\MainProductDetailController;
use App\Modules\Product\Controllers\MainProductPaginateController;
use App\Modules\Product\Controllers\MainProductPincodeController;
use App\Modules\Product\Controllers\ProductExcelUploadController;
use App\Modules\Tax\Controllers\TaxDetailController;
use App\Modules\Tax\Controllers\TaxSaveController;

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
});

Route::prefix('/email/verify')->group(function () {
    Route::post('/resend-notification', [VerifyRegisteredUserController::class, 'resend_notification', 'as' => 'resend_notification'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::get('/{id}/{hash}', [VerifyRegisteredUserController::class, 'verify_email', 'as' => 'verify_email'])->middleware(['signed'])->name('verification.verify');
});

Route::prefix('enquiry')->group(function () {
    Route::post('/create', [EnquiryCreateController::class, 'post'])->name('enquiry.create');
});

Route::prefix('contact')->group(function () {
    Route::post('/create', [ContactCreateController::class, 'post'])->name('contact.create');
});

Route::prefix('banner/main')->group(function () {
    Route::get('/all', [UserBannerAllController::class, 'get'])->name('banner.all.main');
});

Route::prefix('product/main')->group(function () {
    Route::get('/paginate', [MainProductPaginateController::class, 'get'])->name('product.paginate.main');
    Route::get('/detail/{slug}', [MainProductDetailController::class, 'get'])->name('product.detail.main');
    Route::post('/pincode/{slug}', [MainProductPincodeController::class, 'post'])->name('product.pincode.main');
    Route::prefix('/reviews/{product_id}')->group(function () {
        Route::post('/create', [ProductReviewCreateController::class, 'post'])->name('product.review.create.main');
    });
});

Route::prefix('gallery/main')->group(function () {
    Route::get('/paginate', [MainGalleryPaginateController::class, 'get'])->name('gallery.paginate.main');
});

Route::prefix('category/main')->group(function () {
    Route::get('/paginate', [MainCategoryPaginateController::class, 'get'])->name('category.paginate.main');
    Route::get('/detail/{slug}', [MainCategoryDetailController::class, 'get'])->name('category.detail.main');
});

Route::prefix('gallery-category/main')->group(function () {
    Route::get('/paginate', [MainGalleryCategoryPaginateController::class, 'get'])->name('gallery_category.paginate.main');
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

    Route::prefix('tax')->group(function () {
        Route::post('/', [TaxSaveController::class, 'post'])->name('tax.create');
        Route::get('/', [TaxDetailController::class, 'get'])->name('tax.get');
    });

    Route::prefix('delivery-charge')->group(function () {
        Route::post('/', [DeliveryChargeSaveController::class, 'post'])->name('delivery_charge.create');
        Route::get('/', [DeliveryChargeDetailController::class, 'get'])->name('delivery_charge.get');
    });

    Route::prefix('order')->group(function () {
        Route::get('/latest-billing-info', [OrderLatestBillingInfoController::class, 'get'])->name('order.billing.info');
        Route::post('/place', [PlaceOrderController::class, 'post'])->name('order.place');
        Route::get('/placed/paginate', [OrderPlacedPaginateController::class, 'get'])->name('order.placed.paginate');
        Route::get('/placed/detail/{receipt}', [OrderPlacedDetailController::class, 'get'])->name('order.placed.detail');
        Route::get('/placed/cancel/{receipt}', [OrderPlacedCancelController::class, 'get'])->name('order.cancel.detail');
        Route::post('/verify-payment', [VerifyPaymentController::class, 'post'])->name('order.verify_payment');
        Route::get('/paginate', [OrderPaginateController::class, 'get'])->name('order.paginate');
        Route::get('/detail/{id}', [OrderDetailController::class, 'get'])->name('order.detail');
        Route::post('/status/{id}', [OrderStatusController::class, 'post'])->name('order.status');
    });

    Route::prefix('enquiry')->group(function () {
        Route::get('/paginate', [EnquiryPaginateController::class, 'get'])->name('enquiry.paginate');
        Route::post('/update/{id}', [EnquiryUpdateController::class, 'post'])->name('enquiry.update');
        Route::delete('/delete/{id}', [EnquiryDeleteController::class, 'delete'])->name('enquiry.delete');
        Route::get('/detail/{id}', [EnquiryDetailController::class, 'get'])->name('enquiry.get');
    });

    Route::prefix('contact')->group(function () {
        Route::get('/paginate', [ContactPaginateController::class, 'get'])->name('contact.paginate');
        Route::post('/update/{id}', [ContactUpdateController::class, 'post'])->name('contact.update');
        Route::delete('/delete/{id}', [ContactDeleteController::class, 'delete'])->name('contact.delete');
        Route::get('/detail/{id}', [ContactDetailController::class, 'get'])->name('contact.get');
    });

    Route::prefix('banner')->group(function () {
        Route::get('/paginate', [BannerPaginateController::class, 'get'])->name('banner.paginate');
        Route::post('/create', [BannerCreateController::class, 'post'])->name('banner.create');
        Route::post('/update/{id}', [BannerUpdateController::class, 'post'])->name('banner.update');
        Route::delete('/delete/{id}', [BannerDeleteController::class, 'get'])->name('banner.delete');
        Route::get('/detail/{id}', [BannerDetailController::class, 'get'])->name('banner.get');
    });

    Route::prefix('category')->group(function () {
        Route::get('/paginate', [CategoryPaginateController::class, 'get'])->name('category.paginate');
        Route::post('/create', [CategoryCreateController::class, 'post'])->name('category.create');
        Route::post('/update/{id}', [CategoryUpdateController::class, 'post'])->name('category.update');
        Route::delete('/delete/{id}', [CategoryDeleteController::class, 'delete'])->name('category.delete');
        Route::get('/detail/{id}', [CategoryDetailController::class, 'get'])->name('category.get');
    });

    Route::prefix('gallery-category')->group(function () {
        Route::get('/paginate', [GalleryCategoryPaginateController::class, 'get'])->name('gallery_category.paginate');
        Route::post('/create', [GalleryCategoryCreateController::class, 'post'])->name('gallery_category.create');
        Route::post('/update/{id}', [GalleryCategoryUpdateController::class, 'post'])->name('gallery_category.update');
        Route::delete('/delete/{id}', [GalleryCategoryDeleteController::class, 'delete'])->name('gallery_category.delete');
        Route::get('/detail/{id}', [GalleryCategoryDetailController::class, 'get'])->name('gallery_category.get');
    });

    Route::prefix('product')->group(function () {
        Route::get('/paginate', [ProductPaginateController::class, 'get'])->name('product.paginate');
        Route::post('/create', [ProductCreateController::class, 'post'])->name('product.create');
        Route::post('/update/{id}', [ProductUpdateController::class, 'post'])->name('product.update');
        Route::delete('/delete/{id}', [ProductDeleteController::class, 'delete'])->name('product.delete');
        Route::get('/detail/{id}', [ProductDetailController::class, 'get'])->name('product.get');
        Route::post('/excel/upload', [ProductExcelUploadController::class, 'post'])->name('product.excel_upload');
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

    Route::prefix('gallery')->group(function () {
        Route::get('/paginate', [GalleryPaginateController::class, 'get'])->name('gallery.paginate');
        Route::post('/create', [GalleryCreateController::class, 'post'])->name('gallery.create');
        Route::post('/update/{id}', [GalleryUpdateController::class, 'post'])->name('gallery.update');
        Route::delete('/delete/{id}', [GalleryDeleteController::class, 'delete'])->name('gallery.delete');
        Route::get('/detail/{id}', [GalleryDetailController::class, 'get'])->name('gallery.get');
    });

    Route::prefix('coupon')->group(function () {
        Route::get('/paginate', [CouponPaginateController::class, 'get'])->name('coupon.paginate');
        Route::post('/create', [CouponCreateController::class, 'post'])->name('coupon.create');
        Route::post('/update/{id}', [CouponUpdateController::class, 'post'])->name('coupon.update');
        Route::delete('/delete/{id}', [CouponDeleteController::class, 'delete'])->name('coupon.delete');
        Route::get('/detail/{id}', [CouponDetailController::class, 'get'])->name('coupon.get');
    });

    Route::prefix('pincode')->group(function () {
        Route::get('/paginate', [PincodePaginateController::class, 'get'])->name('pincode.paginate');
        Route::post('/create', [PincodeCreateController::class, 'post'])->name('pincode.create');
        Route::post('/update/{id}', [PincodeUpdateController::class, 'post'])->name('pincode.update');
        Route::delete('/delete/{id}', [PincodeDeleteController::class, 'delete'])->name('pincode.delete');
        Route::get('/detail/{id}', [PincodeDetailController::class, 'get'])->name('pincode.get');
    });

    Route::post('/auth/logout', [LogoutController::class, 'post', 'as' => 'logout'])->name('logout');
});

Route::get('/unauthenticated', function (Request $request) {
   throw new UnauthenticatedException("Unauthenticated", 401);
})->name('unauthenticated');
