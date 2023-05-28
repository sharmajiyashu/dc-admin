<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterLoginMobileRequest;
use App\Http\Requests\CustomersUpdateDetail;
use App\Models\Customer;
use App\Models\Role;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class CustomerController extends Controller
{
    use ApiResponse;
    function customerRegisterLoginMobile(CustomerRegisterLoginMobileRequest $request){
        try{
            $customer = Customer::updateOrCreate(['mobile' => $request->mobile]);
            $otp = rand(1000,9999);
            $customer->otp = $otp;
            $customer->is_register = '0';
            $customer->save();
            $token =  $customer->createToken($customer->mobile)->plainTextToken;
            $token = explode('|',$token)[1];
            return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['is_register' => $customer->is_register,'accessToken' => $token,'otp' => $otp]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function CustomersUpdateDetail(CustomersUpdateDetail $request){
        try{
            if($request->hasFile('image')) {
                $image       = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid().'.'.$extension;
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->save(public_path('images/customers/'.$filename));
            }
            $data = $request->validated();
            $data['image'] = isset($filename) ? $filename : '';
            $data['dob'] = date('Y-m-d H:i:s',strtotime($request->dob));
            $data['otp_verify'] = 'yes';
            $data['is_register'] = '1';
            $data['role_id'] = Role::$customer;
            Customer::where('id',$request->user()->id)->update($data);
            return $this->sendSuccess('CUSTOMER DETAIL UPLOAD SUCCESSFULLY',['data' => $request->user()]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }


}
