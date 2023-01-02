<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\DashboardChartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\OrderForDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsForDashboardController;
use App\Models\Order;
use Illuminate\Http\Request;
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
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/profile-edit',[AuthController::class,'updateProfile']);
    Route::post('/password-edit',[AuthController::class,'updatePassword']);
    Route::post('/customer-address',[CustomerAddressController::class,'store']);
    Route::post('checkout',[OrderController::class,'store']);
    Route::get('order',[OrderController::class,'index']);
    Route::get('order-detail',[OrderDetailController::class,'index']);
    // Dashboard

    Route::post('/admin/products/update', [ProductsForDashboardController::class,'customUpdate']);
    Route::apiResource('/admin/products',ProductsForDashboardController::class);

    // Dashboard Chart
    Route::get('/admin/popular-products', [DashboardChartController::class,'popularProducts']);
    Route::get('/admin/orders-chart',[DashboardChartController::class,'ordersChart']);
    Route::apiResource('/admin/orders',OrderForDashboardController::class);

});

Route::apiResource('/products', ProductController::class);
Route::apiResource('category',\App\Http\Controllers\CategoryController::class);






