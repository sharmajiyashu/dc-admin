<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


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


Route::get('/unauthrozed', function () {
    return response()->json([
                'statusCode' => 401,
                'status' => 'unauthrozed',
                'message' => 'Unauthrized user'
            ]);        
    })->name('login');
Route::group(['middleware' => 'AdminAuth'], function () {

    Route::get('/',[Controller::class,'dashboard'])->name('/');
    Route::resource('categories',CategoryController::class);
    Route::resource('products',ProductController::class);
    Route::post('delete-image', [ProductController::class, 'delete_product_image'])->name('delete-product-image');

});

Route::get('login', [LoginController::class, 'index'])->name('admin.login');
Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::post('check-login', [LoginController::class, 'check_login'])->name('check-login');
