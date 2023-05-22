<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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

Route::group(['middleware' => 'AdminAuth'], function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    });
    Route::resource('categories',CategoryController::class);
    Route::resource('products',ProductController::class);
    Route::post('delete-image', [ProductController::class, 'delete_product_image'])->name('delete-product-image');

});

Route::get('login', [LoginController::class, 'index'])->name('admin.login');
Route::post('check-login', [LoginController::class, 'check_login'])->name('check-login');
