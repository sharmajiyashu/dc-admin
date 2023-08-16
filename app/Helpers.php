<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\SentNotification;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\User;
use App\Models\Vendor;

class Helper {
    

    public static function SendNotification($device_id,$title,$body,$image){

        $url = 'https://fcm.googleapis.com/fcm/send';

		$headers = array(
			'Authorization: key=AAAAH0BTkPk:APA91bH8aZKWH7Ar6887BCQ-M5xyuyeftynBsrt6K9Cgk-3kYefAP3JJOmQ4-PElVoD5QqcoaAuhyKkPLEpGhihcg3B1ua0HqPCHunwv63k8CXhJc2cMtdaKYbOfFpxZU45BrRqLBwET',
			'Content-Type: application/json',
		);

		// $image = "https://staging.premad.in/dc-dukaan/public/images/products/169167394770-pp.jPG";

		$device_id = "eoSS0IvqSliQUHI0U2zSwE:APA91bGldRvKPo874pXsxzIoSw2nFQMdkBWMw77JQKC5__kt9TXGsKbQy_oaBggQzF1wkBATsMFjVhKptD51PNofspdou8CA1I8Ej4ohWFRbLdAJCPP83i1X5N_VU0COZ-Ym44btj-lQ";

		$data = array(
			"to" => $device_id,
			"notification" =>
			array(
				"title" 			=> $title,
				"body"  			=> $body,
				// "request_number"  	=> $booking,
				"sound" 			=> 'default',
				'badge'             => '1',
				'action_type'       => 'transfer',
				'image' => $image,
				'id' =>  isset($post_id) ? $post_id : '',
			),
			"data" =>
			array(
				"title" 			=> $title,
				"body"  			=> $body,
				// "request_number"  	=> $booking,
				"sound" 			=> 'default',
				'badge'             => '1',
				'action_type'       => 'transfer',
				'image' => $image ,
				'id' =>  isset($post_id) ? $post_id : '',
			)
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
		
    }

	public static function sentAdminNotification($id){
		$sent = SentNotification::where('id',$id)->first();
		if(!empty($sent)){
			if($sent->to_customers == '1'){
				$customers = Customer::where('role_id',Role::$customer)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'hyy '.$val->name.' customer '.$sent->title;
					$image = "";
					Helper::SendNotification('',$title,$sent->body,$image);
				}
			}
			if($sent->to_vendors == '1'){
				$customers = Customer::where('role_id',Role::$vendor)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'hyy '.$val->name.' Vendor '.$sent->title;
					$image = "";
					Helper::SendNotification('',$title,$sent->body,$image);
				}
			}
		}
	}

	public static function getDefaultSlab(){
		$default_slab = Slab::where(['is_default'=>'1' ,'status' => '1'])->first();
		if(!empty($default_slab)){
			return $default_slab->id;
		}else{
			$default_slab = Slab::create(['is_default'=>'1' ,'status' => '1','name' => 'Default' ,'days' => 0]);
			return $default_slab->id;
		}
	}

	public static function sentMessageToCreateUpdateProduct($product_id,$type){
		$product = Product::where('id',$product_id)->first();
		$vendor = Vendor::where('id',$product->user_id)->first();
		$slab_links = SlabLink::where(['product_id' => $product->id ,'user_id' => $vendor->id])->get();
		foreach($slab_links as $key => $val){
			$store_customers = StoreLink::where(['slab_id' => $val->slab_id ,'vendor_id' => $vendor->id,'status' => StoreLink::$active])->get();
			foreach($store_customers as $key=>$val){
				$user = Helper::getUserDetail($val->user_id);
				$device_id = isset($user->remember_token) ? $user->remember_token :'';
				$title = 'A new product has been added to '.$vendor->store_name.'!';
				$body = 'Store name : '.$vendor->store_name.', Product Name : '.$product->name;
				if(!empty($product->images)){
					$images = json_decode($product->image);
					$image = isset($images[0]) ? $images[0] : '';
				}else{
					$image = '';
				}
				$image = asset('public/images/products/'.$image);
				Helper::SendNotification($device_id,$title,$body,$image);
			}
		}
	}

	public static function getUserDetail($user_id){
		return User::where('id',$user_id)->first();
	}

	public static function sentOrderChange($order_id,$status){
		$order_detail = Order::where('id',$order_id)->first();
		$user = Helper::getUserDetail($order_detail->user_id);
		$device_id = isset($user->remember_token) ? $user->remember_token :'';
		$title = 'Knock knock! Your Order has been '.$status.'!';
		$body = 'Order Id : '.$order_detail->order_id;
		$order_image = Helper::getOrderProductImage($order_id);
		Helper::SendNotification($device_id,$title,$body,$order_image);
	}

	public static function getOrderProductImage($order_id){
		$carts =  Cart::where('order_id',$order_id)->get();
        $image = '';
		foreach($carts as $key=>$val){
			$product = Helper::getProductData($val->product_id);
			if(!empty($product->images)){
				$image = $product->images;
			}
		}
		if(!empty($image)){
				return isset($image[0]) ? $image[0] :'';
		}else{
				return '';
		}
	}

	public static function getProductData($product_id){
		$product = Product::where('id',$product_id)->first();
		if(!empty($product)){
			$category = Category::where('id',$product->category_id)->first();
			$images = json_decode($product->images);
			if(!empty($images)){
				$new_img = [];
				foreach($images as $val){
					$new_img[] = asset('public/images/products/'.$val);
				}
			}
			$product->images = isset($new_img)  ? $new_img :'';
			$product->category_name = isset($category->title)  ? $category->title :'';
			$cat_image = isset($category->image) ? $category->image :'';
			$product->category_image = asset('public/images/categories/'.$cat_image);
			return $product;
		}else{
			return "";
		}
	}

	public static function sentMessageCreateOrder($order_id){
		$order = Order::where('id',$order_id)->first();
		$user = Helper::getUserDetail($order->user_id);
		$user_title = 'Your order has been placed!';
		$user_body = 'Order Id : '.$order->order_id;
		$user_device_id = isset($user->remember_token) ? $user->remember_token :'';
		$order_user_image = Helper::getOrderProductImage($order_id);
		Helper::SendNotification($user_device_id,$user_title,$user_body,$order_user_image);

		$vendor = Helper::getUserDetail($order->vendor_id);
		$vendor_title = "A new order has been placed by ".$user->name;
		$body = "Mobile : ".$user->mobile.', City : '.$user->city;
		$vendor_device_id = isset($vendor->remember_token) ? $vendor->remember_token :'';
		Helper::SendNotification($vendor_device_id,$vendor_title,$body,$order_user_image);
	}

	public static function sentNotificationAddCustomerbyMobile($user_id,$vendor_id){
		$user = Helper::getUserDetail($user_id);
		$vendor = Helper::getUserDetail($vendor_id); 
		$device_id = isset($user->remember_token) ? $user->remember_token :'';
		// $title = $vendor->name." added you Kindly enjoy the shooping ";
		// $body = "Store Code : ".$vendor->store_code .', City : '.$vendor->city;
		$title = "You have been added to ".$vendor->store_name.'!';
		$body = "“".$vendor->name."” added you kindly enjoy the shooping";
		$image = asset('public/images/users/'.$vendor->store_image);
		Helper::SendNotification($device_id,$title,$body,$image);
	}

	public static function sentNotificationForActiveInactiveUser($user_id,$vendor_id,$type){
		$user = Helper::getUserDetail($user_id);
		$vendor = Helper::getUserDetail($vendor_id); 
		$image = asset('public/images/users/'.$vendor->store_image);
		$device_id = isset($user->remember_token) ? $user->remember_token :'';
		if($type == StoreLink::$active){
			$title = 'Store '.$vendor->store_name.' have been activated!';
			$body = "“".$vendor->name."” activate your account kindly enjoy the shooping";
		}else{
			$title = 'Store '.$vendor->store_name.' have been deactivated!';
			$body = "“".$vendor->name."” your account has been deactivated, kindly contact the store";
		}
		Helper::SendNotification($device_id,$title,$body,$image);
	}

}
