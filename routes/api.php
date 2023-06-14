<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('customer_update_details',[CustomerController::class,'CustomersUpdateDetail']);
    Route::post('vendors_update_details',[VendorController::class,'VendorsUpdateDetail']);
    Route::post('create_update_product',[ProductController::class,'CreateUpdateProduct']);
    Route::post('get_vendor_products',[ProductController::class,'GetVendorProducts']);
    Route::post('get_admin_products',[ProductController::class,'GetAdminProducts']);
    Route::post('get_all_categories',[ProductController::class,'GetAllCategories']);
});
Route::post('customer_register_login_mobile',[CustomerController::class,'customerRegisterLoginMobile']);
Route::post('vendor_register_login_mobile',[VendorController::class,'VendorRegisterLoginMobile']);
Route::post('verify_otp',[CustomerController::class,'VarifyOtp']);



