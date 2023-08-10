<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use GuzzleHttp\Client;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard(){
        $total_category = Category::where('status','Active')->where('is_admin','1')->count();
        $total_product = Product::where('is_admin','1')->count();
        $total_vendors = User::where('role_id',Role::$vendor)->where('is_register','1')->count();
        $total_customers = User::where('role_id',Role::$customer)->where('is_register','1')->count();
        $total_orders = Order::count();
        return view('admin.dashboard',compact('total_category','total_product','total_vendors','total_customers','total_orders'))->with('success','ALLSSVHJBHJSBJVJ');
    }


    function payment()
    {
        $secret_key = "6AD45BC6624C4F909380E1FFFBFAEFD1";
        $access_code = "ce42b9d0-e434-49a1-84bc-1140e5a2cf0e";
        $strFormdata = self::data();
        $strFormdata = base64_encode($strFormdata);
        $secret_key = self::Hex2String($secret_key);
        $hash = strtoupper(hash_hmac('sha256', $strFormdata, $secret_key));
        // Set cURL options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-staging.pluralonline.com/api/v1/order/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strFormdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'cache-control: no-cache',
            'x-verify: ' . $hash
        ));
        // Execute cURL request and get response
        $response = curl_exec($ch);
        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        // Close cURL resource
        curl_close($ch);
        // Process the response
        if ($response) {
            $decodedResponse = json_decode($response, true);
            // Process the $decodedResponse as needed
        } else {
            echo 'Error: Empty response received.';
        }
        dd($decodedResponse);
        return 'end';
    }


    function Hex2String($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    function data()
    {
        return '{
            "request": {
                "merchant_data": {
                "merchant_id": "14750",
                "merchant_access_code": "ce42b9d0-e434-49a1-84bc-1140e5a2cf0e",
                "merchant_return_url": "https://www.devoysoftech.com/",
                "merchant_order_id": "API-DOM1O-DO-1"
                },
                "payment_info_data": {
                "amount": 200,
                "currency_code": "INR",
                "order_desc": "Test Order"
                },
                "customer_data": {
                "country_code": "91",
                "mobile_number": "9121004028",
                "email_id": "john.doe@gmail.com"
                },
                "billing_address_data": {
                "first_name": "John",
                "last_name": "Doe",
                "address1": "House No. 123",
                "address2": "Road XYZ",
                "pin_code": "111111",
                "city": "Bengaluru",
                "state": "Karnataka",
                "country": "India"
                },
                "shipping_address_data": {
                "first_name": "John",
                "last_name": "Doe",
                "address1": "House No. 123",
                "address2": "Road XYZ",
                "pin_code": "111111",
                "city": "Bengaluru",
                "state": "Karnataka",
                "country": "India"
                },
                "product_info_data": {
                "product_details": [
                    {
                    "product_name": "John",
                    "last_name": "Doe",
                    "address1": "House No. 123",
                    "address2": "Road XYZ",
                    "pin_code": "111111",
                    "city": "Bengaluru",
                    "state": "Karnataka",
                    "country": "India"
                    }
                ]
                },
                "shipping_charge_data": {
                "price": 40
                },
                "additional_data": {
                "rfu1": "123"
                }
            }
            }';
    }

}
