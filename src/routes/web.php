<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\MyPageController;
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

Route::get("/mail", [ProductController::class, "mail"]);
Route::get("/first-time", [ProductController::class, "firstTime"]);
Route::get("/", [ProductController::class, "index"]);
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MyPageController::class, 'index']);
    Route::get('/mypage/profile', [MyPageController::class, 'edit']);
    Route::post('/mypage/profile', [MyPageController::class, 'upsert']);
});
