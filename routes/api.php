<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StoreLinkController;
use App\Http\Controllers\Api\SlabController;

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
    Route::get('get_home_data',[CustomerController::class,'getHomeData']);
    Route::post('vendors_update_details',[VendorController::class,'VendorsUpdateDetail']);
    Route::post('create_update_product',[ProductController::class,'CreateUpdateProduct']);
    Route::post('delete_product',[ProductController::class,'DeleteProduct']);
    Route::get('get_vendor_products',[ProductController::class,'GetVendorProducts']);
    Route::post('get_admin_products',[ProductController::class,'GetAdminProducts']);
    Route::post('get_all_categories',[ProductController::class,'GetAllCategories']);
    Route::post('add_product_in_cart',[CartController::class,'AddProductInCart']);
    Route::post('cart_items',[CartController::class,'CartItem']);
    Route::get('wishlist_items',[CartController::class,'wishListItems']);
    Route::post('remove_cart_item',[CartController::class,'RemoveCartItem']);
    Route::post('remove_wish_item',[CartController::class,'RemoveWishItem']);
    Route::post('decrement_cart_quantity',[CartController::class,'DecrementCartQuantity']);
    Route::post('create_order',[OrderController::class,'CreateOrder']);
    Route::get('get_products',[ProductController::class,'GetProduct']);
    Route::post('new_arrival_products',[ProductController::class,'NewArrivalProducts']);
    Route::get('customer_order_history',[OrderController::class,'CustomerOrderHistory']);
    Route::post('customer_order_detail',[OrderController::class,'CustomerOrderDetail']);
    Route::post('send_request_to_store',[StoreLinkController::class,'SentRequest']);
    Route::post('delete_store_request',[StoreLinkController::class,'DeleteRequest']);
    Route::get('store_request_list',[StoreLinkController::class,'ListRequest']);
    Route::post('customers_list',[StoreLinkController::class,'CustomersList']);
    Route::post('change_store_status',[StoreLinkController::class,'ChangeRequestStatus']);
    Route::post('link_customers_by_mobile',[StoreLinkController::class,'AddCustomersByMobile']);
    Route::post('update_active_store',[StoreLinkController::class,'UpdateActiveStore']);
    Route::post('upload_profile_image',[CustomerController::class,'UploadImage']);
    Route::post('upload_store_image',[VendorController::class,'UploadStoreImage']);
    Route::get('vendor_orders_history',[OrderController::class,'VendorOrderHistory']);
    Route::post('vendor_orders_detail',[OrderController::class,'VendorOrderDetail']);
    Route::post('get_product_by_name',[ProductController::class,'GetProductByName']);
    Route::get('view_profile',[CustomerController::class,'ViewProfile']);
    Route::post('create_update_slab',[SlabController::class,'CreateUpdate']);
    Route::get('get_slabs',[SlabController::class,'GetSlab']);
    Route::get('get_in_add_customers',[StoreLinkController::class,'GetInAddCustomer']);
    Route::post('remove_add_in_customers',[StoreLinkController::class,'RemoveAdInCustomers']);
    Route::get('vendor_wishlist_items',[VendorController::class,'WithListItem']);
    Route::post('add_customer_in_slab',[SlabController::class,'AddCustomerInSlab']);
    Route::post('get_slab_customer',[SlabController::class,'GetSlabCustomer']);
    Route::post('change_order_status',[OrderController::class,'ChangeOrderStatus']);
    Route::post('delete_order_by_date',[OrderController::class,'DeleteByDate']);
    Route::post('add_bulk_product',[ProductController::class,'AddBulkProduct']);
    Route::post('change_status',[VendorController::class,'ChangeStatus']);
    Route::post('update_category_quantity',[VendorController::class,'UpdateCategoryPackage']);
    Route::get('get_states',[CustomerController::class,'GetStates']);
    Route::post('get_cities',[CustomerController::class,'GetCities']);
    Route::get('remove_cart_items',[CartController::class,'RemoveAllItem']);
    Route::post('add_product_in_slab',[SlabController::class,'AddProductInSlab']);
    Route::post('get_slab_product',[ProductController::class,'GetSlabProduct']);
    Route::get('get_notification',[VendorController::class,'getNotificatons']);
    Route::post('remove_product_from_slab',[SlabController::class,'removeProductFromSlab']);
    Route::post('delete_slab',[SlabController::class,'deleteSlab']);
    Route::get('notify_on_off',[VendorController::class,'changeNotifyStatus']);
    Route::post('seen_notification',[VendorController::class,'seenNotification']);
    Route::get('delete_account',[CustomerController::class,'deleteAccount']);
});
Route::post('customer_register_login_mobile',[CustomerController::class,'customerRegisterLoginMobile']);
Route::post('vendor_register_login_mobile',[VendorController::class,'VendorRegisterLoginMobile']);
Route::post('verify_otp',[CustomerController::class,'VarifyOtp']);
Route::get('get_demo_store',[ApiController::class,'getDemoStores']);



