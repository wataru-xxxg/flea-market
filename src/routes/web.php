<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

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

Route::get("/mail", [ItemController::class, "mail"]);
Route::get("/first-time", [ItemController::class, "firstTime"]);
Route::get("/", [ItemController::class, "index"]);
Route::get("/item/{item_id}", [ItemController::class, "item"])->name("item");
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index']);
    Route::get('/mypage/profile', [MyPageController::class, 'edit']);
    Route::post('/mypage/profile', [MyPageController::class, 'upsert']);
    Route::get("/sell", [ItemController::class, "sell"]);
    Route::post("/sell", [ItemController::class, "exhibit"]);
    Route::get("/item/favorite/{item_id}", [ItemController::class, "favorite"]);
    Route::get("/item/comment/{item_id}", [ItemController::class, "comment"]);
    Route::get("/purchase/{item_id}", [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address'])->name('address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::post('/stripe/payment', [StripeController::class, 'payment'])->name('payment');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('cancel');
});
