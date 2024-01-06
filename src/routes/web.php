<?php

use App\Modules\Authentication\Controllers\ResetPasswordController;
use App\Modules\Category\Controllers\CategoryExcelController;
use App\Modules\Contact\Controllers\ContactExcelController;
use App\Modules\Enquiry\Controllers\EnquiryExcelController;
use App\Modules\Order\Controllers\InvoiceController;
use App\Modules\Order\Controllers\OrderExcelController;
use App\Modules\Order\Controllers\OrderPaymentController;
use App\Modules\Product\Controllers\ProductExcelController;
use App\Modules\User\Controllers\UserExcelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return "yes we are live";
});

Route::get('/success', function () {
    return view('success');
})->name('success');

Route::prefix('auth')->group(function () {
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'get'])->name('reset_password')->middleware('signed');
    Route::post('/reset-password/{token}', [ResetPasswordController::class, 'post'])->name('reset_password.post')->middleware('signed');
});

Route::get('/make-order-payment/{receipt}', [OrderPaymentController::class, 'get'])->name('make_payment');
Route::post('/verify-order-payment/{receipt}', [OrderPaymentController::class, 'post'])->name('verify_payment');

Route::prefix('/api/v1')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/invoice/{reciept}', [InvoiceController::class, 'get'])->name('invoice.pdf');
    Route::get('/enquiry/excel', [EnquiryExcelController::class, 'get'])->name('enquiry.excel');
    Route::get('/contact/excel', [ContactExcelController::class, 'get'])->name('contact.excel');
    Route::get('/category/excel', [CategoryExcelController::class, 'get'])->name('category.excel');
    Route::get('/product/excel', [ProductExcelController::class, 'get'])->name('product.excel');
    Route::get('/order/excel', [OrderExcelController::class, 'get'])->name('order.excel');
    Route::get('/user/excel', [UserExcelController::class, 'get'])->name('user.excel');
});
