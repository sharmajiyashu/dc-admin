<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\PayUService\Exception;


class ApiController extends Controller
{
    public function checkmobile(Request $request){
        
        try {
            if(empty($request->mobile)){
                return json_encode([
                    'status' => 'false',
                    'message' => 'Mobile Number is Required',
                    'data' => '',
                ]);
            }else{
                $data = User::where('mobile',$request->mobile)->first();
                if(!empty($data)){
                    return json_encode([
                        'status' => 'true',
                        'message' => 'You are not register',
                        'data' => $data,
                    ]);
                }else{
                    return json_encode([
                        'status' => 'false',
                        'message' => 'You are not register',
                        'data' => '',
                    ]);
                }
            }
        }catch(\Exception $e){
            return json_encode([
                'status' => 'false',
                'message' => $e,
                'data' => '',
            ]);
        }

    }

    public function CustomerRegister(Request $request){
        try{
            Customer::create($request->all());
        }catch (\Exception $e) {
            return json_encode([
                'status' => 'false',
                'message' => $e,
                'data' => '',
            ]);
        }
    }
}
