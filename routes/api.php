<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariationController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);
Route::middleware(['sanctum.custom_auth','auth:sanctum'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('products', ProductController::class);
    Route::post('products/{productId}/upload-image', [ProductImageController::class, 'upload']);

    Route::get('products/{product}/variations', [ProductVariationController::class, 'index']);
    Route::post('products/{product}/variations', [ProductVariationController::class, 'store']);
    Route::get('products/{product}/variations/{productVariation}', [ProductVariationController::class, 'show']);
    Route::put('products/{product}/variations/{productVariation}', [ProductVariationController::class, 'update']);
    Route::delete('products/{product}/variations/{productVariation}', [ProductVariationController::class, 'destroy']);
});
// Route::apiResource('customers', CustomerController::class)->middleware(['sanctum.custom_auth','auth:sanctum']);
