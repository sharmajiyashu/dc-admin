<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\VendorsUpdateDetail;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Vendor;
use App\Traits\ApiResponse;

class VendorController extends Controller
{
    use ApiResponse;
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
            $data['otp_verify'] = 'yes';
            $data['is_register'] = '1';
            $data['role_id'] = Role::$vendor;
            Vendor::where('id',$request->user()->id)->update($data);
            return $this->sendSuccess('Vandor DETAIL UPLOAD SUCCESSFULLY',['data' => $request->user()]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }
}
