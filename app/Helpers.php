<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\Role;
use App\Models\SentNotification;

class Helper {
    

    public static function SendNotification($device_id,$title,$body){

		
        $url = 'https://fcm.googleapis.com/fcm/send';

		// $url = 'https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send';

		// $headers = array(
		// 	'Authorization: key=AAAAoaUk1hk:APA91bHyPHvsNrAv00dnyhAeJ-lHmLydNNT7-_lqJPfuYZOMAQnPrMQKxUjL3toyO7GckoMksffLjMf_7j4Uk0HHuBqBTuHiddQufWHAsfepjlLovdNRaa1UXdrEx32GZu3AK-Ciwj9B',
		// 	'Content-Type: application/json',
		// );

		$headers = array(
			'Authorization: key=AAAAH0BTkPk:APA91bH8aZKWH7Ar6887BCQ-M5xyuyeftynBsrt6K9Cgk-3kYefAP3JJOmQ4-PElVoD5QqcoaAuhyKkPLEpGhihcg3B1ua0HqPCHunwv63k8CXhJc2cMtdaKYbOfFpxZU45BrRqLBwET',
			'Content-Type: application/json',
		);

		$image = "https://staging.premad.in/dc-dukaan/public/images/products/169167394770-pp.jPG";
		
		// $device_id = "d2D7YkHGSt69SxjUlM1xoV:APA91bEjBOPEko7sIboVCd-o23ilxJaMU5EVYFG8E6Al5z8y_OppalCCR1LRbCyICiMCjG0UHc6aac9SDb7gWSB9S3Q-iAIB3DuYP2HyIb8ZC72kvaNrr9OI6GrxED26MYkGBFVERCCF";
		$device_id = "fPo8f1nFTTuv2hj3OIciZm:APA91bFHnZJnbXJU8SqnlaBvCBLER-818TpULzkw6Z9Dnbqik2_CUtvvKkUJHwcmJcIEjBPyfq7F9wYgBzB_K9TIEPx69OyX1TB9q4BNDJ4PMFmYm2pzy0q5OL_w7BF-Ba4rKeQfmDaD";

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
		
		print_r($result);die;

		return $result;
		
    }

	public static function sentAdminNotification($id){
		$sent = SentNotification::where('id',$id)->first();
		if(!empty($sent)){
			if($sent->to_customers == '1'){
				$customers = Customer::where('role_id',Role::$customer)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'hyy '.$val->name.' customer '.$sent->title;
					Helper::SendNotification('',$title,$sent->body);
				}
			}
			if($sent->to_vendors == '1'){
				$customers = Customer::where('role_id',Role::$vendor)->where('is_register','1')->get();
				foreach($customers as $key => $val){
					$title = 'hyy '.$val->name.' Vendor '.$sent->title;
					Helper::SendNotification('',$title,$sent->body);
				}
			}
		}
	}


}
