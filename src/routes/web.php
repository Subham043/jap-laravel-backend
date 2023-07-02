<?php

use App\Modules\Authentication\Controllers\ResetPasswordController;
use App\Modules\Order\Controllers\InvoiceController;
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

Route::get('/api/v1/invoice/{reciept}', [InvoiceController::class, 'get'])->name('invoice');
