<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;


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
    Route::get('products-edit/{id}',[ProductController::class,'edit_2'])->name('products.edit_2');
    Route::post('products-update_2',[ProductController::class,'update_2'])->name('products.update_2');
    Route::resource('vendors',VendorController::class);

    Route::group(['as' => 'vendors.account.'], function () {
        Route::get('vendor-products/{id}',[VendorController::class,'vendors_products'])->name('products');
        Route::get('vendor-customers/{id}',[VendorController::class,'vendors_customers'])->name('customers');
        Route::get('vendor-orders/{id}',[VendorController::class,'vendors_orders'])->name('orders');
        Route::get('vendor-wishlist/{id}',[VendorController::class,'wishlist'])->name('wishlist');
        Route::post('vendor-customers-delete',[VendorController::class,'vendors_customers_detele'])->name('customers.delete');
        Route::post('vendor-customers-add-customers',[VendorController::class,'add_customers'])->name('customers.add_customers');
        Route::post('vendor-customers-change-status-customers',[VendorController::class,'customers_change_status'])->name('customers.change_status');
        Route::post('vendor-customers-add-stores',[VendorController::class,'customers_add_stores'])->name('customers.add_stores');
    });

    Route::group(['as' => 'customers.account.'], function () {
        Route::get('customers-stores/{id}',[CustomerController::class,'stores'])->name('stores');
        Route::get('customers-orders/{id}',[CustomerController::class,'orders'])->name('orders');
        Route::get('customers-wishlist/{id}',[CustomerController::class,'wishlist'])->name('wishlist');
        Route::get('customers-carts/{id}',[CustomerController::class,'carts'])->name('carts');
    });

    Route::resource('customers',CustomerController::class);
    Route::resource('orders',OrderController::class);
    Route::get('orders/invoive/{id}',[OrderController::class,'order_invoice'])->name('orders.invoice');
    Route::post('delete-image',[ProductController::class, 'delete_product_image'])->name('delete-product-image');

});

Route::get('login', [LoginController::class, 'index'])->name('admin.login');
Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::post('check-login', [LoginController::class, 'check_login'])->name('check-login');


Route::get('order-history/{id}',[OrderController::class,'OrderHistory'])->name('order-history');