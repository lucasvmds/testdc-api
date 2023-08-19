<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('auth', [AuthController::class, 'login'])->name('auth.login');
Route::middleware('auth.token')->group(function(): void {
    Route::delete('auth', [AuthController::class, 'logout'])->name('auth.logout');
    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class);
});
