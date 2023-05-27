<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CustomerController;

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

Route::post('customer_register_login_mobile',[CustomerController::class,'customerRegisterLoginMobile']);
Route::get('checkmobile', [ApiController::class, 'checkmobile'])->name('checkmobile');
Route::post('CustomerRegister', [ApiController::class, 'CustomerRegister'])->name('CustomerRegister');
