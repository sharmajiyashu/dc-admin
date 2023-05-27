<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterLoginMobileRequest;
use App\Models\Customer;
use App\Models\Role;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponse;
    function customerRegisterLoginMobile(CustomerRegisterLoginMobileRequest $request){
        try{
        $customer = Customer::updateOrCreate(['mobile' => $request->mobile,'role_id' => Role::$customer]);
        $otp = rand(1000,9999);
        $customer->otp = $otp;
        $customer->save();
        $token =  $customer->createToken($customer->mobile)->plainTextToken;
        $token = explode('|',$token)[1];
        return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['otp_verify' => $customer->otp_verify,'accessToken' => $token]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function check(){
        
    }
}
