<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\SentNotification;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper {
    

    public static function SendNotification($device_id,$title,$body,$image,$id){

        $url = 'https://fcm.googleapis.com/fcm/send';

		$headers = array(
			'Authorization: key=AAAAH0BTkPk:APA91bH8aZKWH7Ar6887BCQ-M5xyuyeftynBsrt6K9Cgk-3kYefAP3JJOmQ4-PElVoD5QqcoaAuhyKkPLEpGhihcg3B1ua0HqPCHunwv63k8CXhJc2cMtdaKYbOfFpxZU45BrRqLBwET',
			'Content-Type: application/json',
		);

		// $image = "https://staging.premad.in/dc-dukaan/public/images/products/169167394770-pp.jPG";

// 		$device_id = "eoSS0IvqSliQUHI0U2zSwE:APA91bGldRvKPo874pXsxzIoSw2nFQMdkBWMw77JQKC5__kt9TXGsKbQy_oaBggQzF1wkBATsMFjVhKptD51PNofspdou8CA1I8Ej4ohWFRbLdAJCPP83i1X5N_VU0COZ-Ym44btj-lQ";

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
				'id' =>  isset($id) ? $id : '',
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
				'id' =>  isset($id) ? $id : '',
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

		Helper::removeBefore7daysData();

		return $result;
		
    }

	public static function sentAdminNotification($id){
		$sent = SentNotification::where('id',$id)->first();
		if(!empty($sent)){
			$count = $sent->count + 1;
			if($sent->to_customers == '1'){
				$customers = Customer::where('role_id',Role::$customer)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'Hello '.$val->name.', '.$sent->title;
					$image = asset('public/images/notifications/'.$sent->image);
					$body = $sent->body;
					$device_id =  $val->remember_token; 
					$notification = Notification::create(['user_id' => $val->id ,'title' => $title ,'body' => $body ,'image' => $image]);
					if($customers->is_notify == 1){
						Helper::SendNotification($device_id,$title,$body,$image,$notification->id);
					}
				}
			}
			if($sent->to_vendors == '1'){
				$customers = Customer::where('role_id',Role::$vendor)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'Hello '.$val->name.', '.$sent->title;
					$body = $sent->body;
					$image = asset('public/images/notifications/'.$sent->image);
					$device_id =  $val->remember_token;
					$notification = Notification::create(['user_id' => $val->id ,'title' => $title ,'body' => $body ,'image' => $image]);
					if($customers->is_notify == 1){
						Helper::SendNotification($device_id,$title,$body,$image,$notification->id);
					}
				}
			}
			SentNotification::where('id',$id)->update(['count' => $count]);
		}
	}

	public static function getDefaultSlab(){
		$default_slab = Slab::where(['is_default'=>'1' ,'status' => '1'])->first();
		if(!empty($default_slab)){
			return $default_slab->id;
		}else{
			$default_slab = Slab::create(['is_default'=>'1' ,'status' => '1','name' => Helper::createUpperString('Default') ,'days' => 0]);
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
				if($type == 1){
					$title = 'A product has been updated to '.$vendor->store_name.'!';
				}else{
					$title = 'A new product has been added to '.$vendor->store_name.'!';
				}
				$body = 'Store name : '.$vendor->store_name.', Product Name : '.$product->name;
				if(!empty($product->images)){
					$images = json_decode($product->image);
					$image = isset($images[0]) ? $images[0] : '';
				}else{
					$image = '';
				}
				$image = asset('public/images/products/'.$image);
				$notification = Notification::create(['user_id' => $user->id ,'title' => $title ,'body' => $body ,'image' => $image]);
				if($user->is_notify == 1){
					Helper::SendNotification($device_id,$title,$body,$image,$notification->id);
				}
				
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
		$notification = Notification::create(['user_id' => $user->id ,'title' => $title ,'body' => $body ,'image' => $order_image]);
		if($user->is_notify == 1){
			Helper::SendNotification($device_id,$title,$body,$order_image,$notification->id);
		}
		
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
		$notification = Notification::create(['user_id' => $user->id ,'title' => $user_title ,'body' => $user_body ,'image' => $order_user_image]);
		if($user->is_notify == 1){
			Helper::SendNotification($user_device_id,$user_title,$user_body,$order_user_image,$notification->id);
		}
		

		$vendor = Helper::getUserDetail($order->vendor_id);
		$vendor_title = "A new order has been placed by ".$user->name;
		$body = "Mobile : ".$user->mobile.', City : '.$user->city;
		$vendor_device_id = isset($vendor->remember_token) ? $vendor->remember_token :'';
		$notification = Notification::create(['user_id' => $vendor->id ,'title' => $vendor_title ,'body' => $body ,'image' => $order_user_image]);
		if($vendor->is_notify == 1){
			Helper::SendNotification($vendor_device_id,$vendor_title,$body,$order_user_image,$notification->id);
		}
		

	}

	public static function sentNotificationAddCustomerbyMobile($user_id,$vendor_id){
		$user = Helper::getUserDetail($user_id);
		$vendor = Helper::getUserDetail($vendor_id); 
		$device_id = isset($user->remember_token) ? $user->remember_token :'';
		// $title = $vendor->name." added you Kindly enjoy the shopping ";
		// $body = "Store Code : ".$vendor->store_code .', City : '.$vendor->city;
		$title = "You have been added to ".$vendor->store_name.'!';
		$body = "“".$vendor->name."” added you kindly enjoy the shopping";
		$image = asset('public/images/users/'.$vendor->store_image);
		$notification = Notification::create(['user_id' => $user->id ,'title' => $title ,'body' => $body ,'image' => $image]);
		if($user->is_notify == 1){
			Helper::SendNotification($device_id,$title,$body,$image,$notification->id);
		}
		
	}

	public static function sentNotificationForActiveInactiveUser($user_id,$vendor_id,$type){
		$user = Helper::getUserDetail($user_id);
		$vendor = Helper::getUserDetail($vendor_id); 
		$image = asset('public/images/users/'.$vendor->store_image);
		$device_id = isset($user->remember_token) ? $user->remember_token :'';
		if($type == StoreLink::$active){
			$title = 'Store '.$vendor->store_name.' have been activated!';
			$body = "“".$vendor->name."” activate your account kindly enjoy the shopping";
		}else{
			$title = 'Store '.$vendor->store_name.' have been deactivated!';
			$body = "“".$vendor->name."” your account has been deactivated, kindly contact the store";
		}
		$notification = Notification::create(['user_id' => $user->id ,'title' => $title ,'body' => $body ,'image' => $image]);
		if($user->is_notify == 1){
			Helper::SendNotification($device_id,$title,$body,$image,$notification->id);
		}
	}

	public static function removeBefore7daysData(){
		$sevenDaysAgo = Carbon::now()->subDays(7)->toDateString();
			Notification::whereDate('created_at', '<', $sevenDaysAgo)
				->delete();
	}

	public static function createUpperString($name){
		return Str::upper($name);
	}

	public static function getCustomerCategories($user_id){
		$user = Customer::find($user_id);
		if (!$user) {
			return [];
		}
		$vendor = Vendor::where('store_code', $user->active_store_code)->first();
		$userIdToQuery = $vendor ? $vendor->id : 0;

		$categories = Category::where([
			'status' => Category::$active,
			'user_id' => $userIdToQuery,
			'is_admin' => '0',
		])->orderBy('title','asc')->get()->map(function($category){
			if (!empty($category->image)) {
				$category->image = asset('public/images/categories/' . $category->image);
			}
			$check_Category = Category::find($category->admin_id);
			if($check_Category->status == Category::$active){
				return $category;
			}
		})->filter()->values();
		return $categories;
	}
	

	public static function getCustomerArrivalsProducts($user_id){
		$user = User::find($user_id);
        $active_store = $user->active_store_code;
        $storeLink = StoreLink::where('user_id',$user_id)->where('store_code',$active_store)->first();
		if (!$user) {
			return [];
		}
		
		$slab_id = isset($storeLink->slab_id) ? $storeLink->slab_id :'';
		
		// echo $slab_id;die;

		if (!$storeLink || $storeLink->status !== StoreLink::$active) {
			Customer::where('id', $user->id)->update(['active_store_code' => '']);
			return [];
		}

		// Retrieve the products related to the active store's slab
		$products = Product::where('user_id', $storeLink->vendor_id)
			->where('status',1)
			->where('is_admin', '0')
			->latest()
			->get()->map(function ($product) use($slab_id){
				$image = $product->images;
				$product->images = self::transformImages($image);
				$product->original_images = Helper::transformOrignilImages($image);
				$slab_check = SlabLink::where(['product_id' => $product->id ,'user_id' => $product->user_id,'slab_id' => $slab_id])->exists();
				$slab_data = Slab::find($slab_id);
				if($slab_check && $slab_data->status == Slab::$active){
					return $product ? $product :'';
				}
			})->filter()->values()->take(10);

		return $products;
	}


	public static function transformImages($images){
		$imageUrls = [];
		$images = json_decode($images, true);
		if(!empty($images)){
			foreach ($images as $image) {
				$imageUrls[] = asset('public/images/products/thumb2/' . $image);
			}
		}else{
			return '';
		}
		return $imageUrls;
	}

	public static function transformOrignilImages($images){
		$imageUrls = [];
		if(empty($images)){
			$images = "";
		}else{
			$images = json_decode($images, true);
		}
		if(!empty($images)){
			foreach ($images as $image) {
				$imageUrls[] = asset('public/images/products/' . $image);
			}
		}else{
			return '';
		}
		return $imageUrls;
	}

	public static function getSlabNames($productId, $userId)
	{
		return SlabLink::where(['user_id' => $userId, 'product_id' => $productId])
			->get()
			->map(function ($slabLink) {
				$slab = Slab::find($slabLink->slab_id);
				return $slab ? $slab->name : '';
			});
	}

	public static function getCategoryTitle($categoryId)
	{
		$category = Category::find($categoryId);
		return $category ? $category->title : '';
	}


	public static function sendOtp($mobile,$otp){
		// $message = "Hi, Your Login OTP is 2563. From DC JEWELRY.";
		$message = "Hi, Your Login OTP is ".$otp.". From DC JEWELRY.";
		$DLT_TE_ID = "1207161967508062368";

		/* SMS API Configuration */
		$authKeyAPI = "333977656c727938373873"; //Your authentication key
		$senderIdAPI = "DCJLRY"; // Sender ID, While using route4 sender id should be 6 characters long.
		$routeAPI = 2; // route=1 for promotional, route=4 for transactional SMS. 
		$dev_mode = 0; // 1 for testing ; 0 for Live
		$country_code = 0;

		//Multiple mobiles numbers separated by comma
		$mobileNumber = $mobile;

		//Your message to send, Add URL encoding here.
		//$message = urlencode("Thanks for registering at Mr.Grocer, kindly note your ID:$userId & Password:$password2");

		//Prepare you post parameters
		$postData = array(
			'authkey' => $authKeyAPI,
			'country' => '91',
			'unicode' => '0',
			'mobiles' => $mobileNumber,
			'message' => $message,
			'sender' => $senderIdAPI,
			'route' => $routeAPI,
			'DLT_TE_ID' => $DLT_TE_ID,
			'dev_mode' => $dev_mode,
			'country' => $country_code
		);

		//API URL
		$url="http://control.yourbulksms.com/api/sendhttp.php";

		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		));

		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		//get response
		$output = curl_exec($ch);

		//Print error if any
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);
		}

		curl_close($ch);
	}

}
