<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;

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
});
Route::post('customer_register_login_mobile',[CustomerController::class,'customerRegisterLoginMobile']);


