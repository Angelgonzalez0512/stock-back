<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/user/login", [UserController::class, "login"]);

// routes protected by passport
Route::group(["middleware" => "auth:api"], function () {
    Route::post("/user/logout", [UserController::class, "logout"]);
    Route::get("/user/authenticated", [UserController::class, "userAutenticated"]);
    Route::resource("/user", UserController::class)->except(["create", "edit"]);
    Route::resource("product", ProductController::class)->except(["create", "edit"]);
    Route::resource("category", CategoryController::class)->except("create", "edit");
    Route::resource("transfer", TransferController::class)->except(["create", "edit"]);
});
