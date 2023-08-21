<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
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
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::apiResource('products', ProductController::class);
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('sales', SaleController::class)->except(['update']);
});
