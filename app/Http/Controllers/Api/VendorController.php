<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\VendorsUpdateDetail;
use App\Http\Requests\VendorRegisterLoginMobileRequest;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Vendor;
use App\Traits\ApiResponse;

class VendorController extends Controller
{
    use ApiResponse;

    function VendorRegisterLoginMobile(VendorRegisterLoginMobileRequest $request){
        try{
            $customer = Vendor::updateOrCreate(['mobile' => $request->mobile,'role_id' => Role::$vendor]);
            // $otp = rand(1000,9999);
            $otp = 1234;
            $customer->otp = $otp;
            $customer->save();
            return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['is_register' => $customer->is_register,'otp' => $otp,'user_id' => $customer->id]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function VendorsUpdateDetail(VendorsUpdateDetail $request){
        try{
            $data = $request->validated();
            if($request->hasFile('image')) {
                $image       = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid().'.'.$extension;
                $image_resize = Image::make($image->getRealPath());              
                $image_resize->save(public_path('images/vandors/'.$filename));
                $data['image'] = isset($filename) ? $filename : '';
            }
            $data['dob'] = date('Y-m-d H:i:s',strtotime($request->dob));
            $data['is_register'] = '1';
            Vendor::where('id',$request->user()->id)->update($data);
            return $this->sendSuccess('Vandor DETAIL UPLOAD SUCCESSFULLY',['data' => $request->user()]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }
}
