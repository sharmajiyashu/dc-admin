<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterLoginMobileRequest;
use App\Http\Requests\CustomersUpdateDetail;
use App\Http\Requests\VarifyOtp;
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
            // $otp = rand(1000,9999);
            $otp = 1234;
            $customer->otp = $otp;
            if($customer->role_id == ''){
                $customer->role_id = $request->role_id;
                $customer->is_register = '0';
            }elseif($customer->role_id != $request->role_id){
                $data = Role::where('id',$customer->role_id)->first();
                return $this->sendFailed('You Are Already As '.$data->name,200);
            }
            $customer->save();
            return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['is_register' => $customer->is_register,'otp' => $otp,'user_id' => $customer->id]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function VarifyOtp(VarifyOtp $request){
        $customer = Customer::where(['otp'=>$request->otp,'id'=>$request->user_id])->first();
        if(!empty($customer)){
            $customer->otp_verify = '1';
            $customer->save();
            $token =  $customer->createToken($customer->mobile)->plainTextToken;
            $token = explode('|',$token)[1];
            return $this->sendSuccess(' OTP VERIFIED SUCCESSFULLY',['is_register' => $customer->is_register,'user_id' => $customer->id,'accessToken' => $token]);
        }else{
            return $this->sendFailed('INVALID OTP',200);
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
            $data['is_register'] = '1';
            $data['role_id'] = Role::$customer;
            Customer::where('id',$request->user()->id)->update($data);
            return $this->sendSuccess('CUSTOMER DETAIL UPLOAD SUCCESSFULLY',['data' => $request->user()]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }


}
