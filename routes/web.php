<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NotificationController;
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


Route::get('payment',[Controller::class,'payment']);
    
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
        Route::get('vendor-slabs/{id}',[VendorController::class,'slabs'])->name('slabs');
        Route::get('vendor-notifications/{id}',[VendorController::class,'notifications'])->name('notifications');
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
        Route::get('customers-notifications/{id}',[CustomerController::class,'notifications'])->name('notifications');
    });

    Route::group(['as' => 'notifications.'], function () {
        Route::get('notifications',[NotificationController::class,'index'])->name('index');
        Route::get('notifications-create',[NotificationController::class,'create'])->name('create');
        Route::post('notifications-store',[NotificationController::class,'store'])->name('store');
        Route::post('notifications-update/{id}',[NotificationController::class,'update'])->name('update');
        Route::get('notifications-edit/{id}',[NotificationController::class,'edit'])->name('edit');
        Route::get('notifications-delete/{id}',[NotificationController::class,'delete'])->name('delete');
        Route::get('sent-admin-message/{id}',[NotificationController::class,'sentAdminNotification'])->name('sent-admin-notofication');

    });

    Route::resource('customers',CustomerController::class);
    Route::resource('orders',OrderController::class);
    Route::get('orders/invoive/{id}',[OrderController::class,'order_invoice'])->name('orders.invoice');
    Route::post('delete-image',[ProductController::class, 'delete_product_image'])->name('delete-product-image');

    Route::get('change-order-status/{id}/{status}',[OrderController::class,'changeOrderStatus'])->name('change-order-status');

    Route::get('delete_notifications/{id}',[NotificationController::class,'delete_notifications'])->name('delete_notifications');
    Route::post('add_bulk_product',[ProductController::class,'addBulkProduct'])->name('add_bulk_csv');

    Route::post('update_multiple_image',[ProductController::class,'edit_multiple_product_image'])->name('product.edit_multiple_product_image');
    Route::post('delete_multiple_images',[ProductController::class,'delete_multiple_images'])->name('product.delete_multiple_images');
    Route::post('update_multiple_products_image',[ProductController::class,'update_multiple_products_image'])->name('update_multiple_products_image');

    Route::post('changes_slab_status',[Controller::class,'changes_slab_status'])->name('changes_slab_status');
    Route::post('changes_category_status',[Controller::class,'changes_category_status'])->name('changes_category_status');
    Route::post('changes_product_status',[Controller::class,'changes_product_status'])->name('changes_product_status');
    Route::post('changes_store_link_status',[Controller::class,'changes_store_link_status'])->name('changes_store_link_status');
    Route::post('changes_notification_status',[Controller::class,'changes_notification_status'])->name('changes_notification_status');
});

Route::get('sentNotification',[Controller::class,'SendNotification']);

Route::get('login', [LoginController::class, 'index'])->name('admin.login');
Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::post('check-login', [LoginController::class, 'check_login'])->name('check-login');


Route::get('order-history/{id}',[OrderController::class,'OrderHistory'])->name('order-history');
Route::get('order-invoice/{id}',[OrderController::class,'OrderInvoice'])->name('order-invoice');

Route::get('create_thumbnil_image',[Controller::class,'createthumbnil']);