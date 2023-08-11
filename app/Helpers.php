<?php

namespace App\Helpers;

class Helper {
    

    public static function SendNotification($device_id,$title,$body){

		
        $url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=AAAAoaUk1hk:APA91bHyPHvsNrAv00dnyhAeJ-lHmLydNNT7-_lqJPfuYZOMAQnPrMQKxUjL3toyO7GckoMksffLjMf_7j4Uk0HHuBqBTuHiddQufWHAsfepjlLovdNRaa1UXdrEx32GZu3AK-Ciwj9B',
			'Content-Type: application/json',
		);

		$image = "https://staging.premad.in/dc-dukaan/public/images/products/169167394770-pp.jPG";
		$device_id = "d2D7YkHGSt69SxjUlM1xoV:APA91bEjBOPEko7sIboVCd-o23ilxJaMU5EVYFG8E6Al5z8y_OppalCCR1LRbCyICiMCjG0UHc6aac9SDb7gWSB9S3Q-iAIB3DuYP2HyIb8ZC72kvaNrr9OI6GrxED26MYkGBFVERCCF";

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


}
