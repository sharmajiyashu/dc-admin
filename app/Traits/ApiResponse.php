<?php

namespace App\Traits;

trait ApiResponse
{
    function sendSuccess($message, $result = null)
    {
        $response = [
            'ResponseCode'  => 200,
            'Status'    => true,
            'Message' => $message,
            'Data' => $result
        ];

        // if (!empty($result)) {
        //     // $response['Data'] = $result;
        // }
        return response()->json($response, 200);
    }

    function sendFailed($errorMessage = [], $code = 200)
    {
        $response = [
            'ResponseCode'  => $code,
            'Status'    => false,
        ];

        if (!empty($errorMessage)) {
            $response['Message'] = $errorMessage;
        }
        return response()->json($response, $code);
    }

    function send_sms_api($mobile,$otp){
        $authKeyAPI = "16477ApfdsTzu5ef1d5fcP15"; //Your authentication key
        $senderIdAPI = "DCJLRY"; // Sender ID, While using route4 sender id should be 6 characters long.
        $routeAPI = 4; // route=1 for promotional, route=4 for transactional SMS. 
        $dev_mode = 0; // 1 for testing ; 0 for Live

        //Multiple mobiles numbers separated by comma
        $mobileNumber = $mobile;
        $message = urlencode("Hi, Your Login OTP is $otp. From DC JEWELRY.");
		$DLT_TE_ID = "1207161967508062368";

        //Your message to send, Add URL encoding here.
        // $message = urlencode("Thanks for registering at Mr.Grocer, kindly note your ID:$userId & Password:$password2");

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKeyAPI,
            'country' => '91',
            'unicode' => '0',
            'mobiles' => '9128433083',
            'message' => $message,
            'sender' => $senderIdAPI,
            'route' => $routeAPI,
            'DLT_TE_ID' => $DLT_TE_ID,
            'dev_mode' => $dev_mode,
        );

        
        //API URL
        $url="http://sms2.mrhitech.net/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            ,CURLOPT_FOLLOWLOCATION => true
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
