<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['get', 'post'], "/", [ItemController::class, "index"]);
Route::get("/item/{item_id}", [ItemController::class, "item"])->name("item");
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index']);
    Route::get('/mypage/profile', [MyPageController::class, 'edit']);
    Route::post('/mypage/profile', [MyPageController::class, 'upsert']);
    Route::get('/mypage/chat/{deal_id}', [MyPageController::class, 'chat']);
    Route::post('/mypage/chat/message', [MyPageController::class, 'message']);
    Route::get('/mypage/chat/message/{message_id}/edit', [MyPageController::class, 'editMessage']);
    Route::post('/mypage/chat/message/{message_id}/edit', [MyPageController::class, 'updateMessage']);
    Route::post('/mypage/chat/message/{message_id}/update', [MyPageController::class, 'updateMessageAjax']);
    Route::post('/mypage/chat/message/{message_id}/delete', [MyPageController::class, 'deleteMessageAjax']);
    Route::get("/sell", [ItemController::class, "sell"]);
    Route::post("/sell", [ItemController::class, "exhibit"]);
    Route::get("/item/favorite/{item_id}", [ItemController::class, "favorite"]);
    Route::post("/item/comment/{item_id}", [ItemController::class, "comment"]);
    Route::get("/purchase/{item_id}", [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::post('/stripe/payment', [StripeController::class, 'payment']);
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('cancel');
});
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
