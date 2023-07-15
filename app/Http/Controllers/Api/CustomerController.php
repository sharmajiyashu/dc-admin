<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterLoginMobileRequest;
use App\Http\Requests\CustomersUpdateDetail;
use App\Http\Requests\VarifyOtp;
use App\Http\Requests\UploadApi;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class CustomerController extends Controller
{
    use ApiResponse;
    function customerRegisterLoginMobile(CustomerRegisterLoginMobileRequest $request){
        try{
            $customer = Customer::updateOrCreate(['mobile' => $request->mobile,'role_id' => Role::$customer]);
            // $otp = rand(1000,9999);
            $otp = 1234;
            $customer->otp = $otp;
            $customer->save();
            return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['is_register' => $customer->is_register,'otp' => $otp,'user_id' => $customer->id,'pin' => $customer->pin]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function VarifyOtp(VarifyOtp $request){
        if(!empty($request->otp)){
            $customer = Customer::where(['otp'=>$request->otp,'id'=>$request->user_id])->first();
            $type = 'OTP';
        }elseif(!empty($request->pin)){
            $customer = Customer::where(['pin'=>$request->pin,'id'=>$request->user_id])->first();
            $type = 'PIN';
        }else{
            return $this->sendFailed('PLEASE ENTER PIN AND OTP',200);
        }

        
        $total_product = Product::where('user_id',$request->user_id)->count();
        if(!empty($customer)){
            $this->GenerateVendorCategory($customer->id);
            
            $customer->otp_verify = '1';
            $customer->save();
            $token =  $customer->createToken($customer->mobile)->plainTextToken;
            $token = explode('|',$token)[1];
            return $this->sendSuccess($type.' VERIFIED SUCCESSFULLY',['is_register' => $customer->is_register,'user_id' => $customer->id,'active_store_code' => $customer->active_store_code,'total_product' => $total_product,'accessToken' => $token]);
        }else{
            return $this->sendFailed('INVALID '.$type,200);
        }
    }

    function GenerateVendorCategory($id){
        $vendor = Vendor::where('id',$id)->Vendor()->status(Vendor::$active)->first();
        if(!empty($vendor)){
            if($vendor->is_register != 1){
                $products = Category::where('is_admin','1')->get();
                foreach($products as $key=>$val){
                    $check_category = Category::where('user_id',$vendor->id)->where('admin_id',$val->id)->count();
                    if($check_category == 0){
                        $data = [
                            'title' => $val->title,
                            'image' => $val->image,
                            'packing_quantity' => $val->packing_quantity,
                            'status' => $val->status,
                            'admin_id' => $val->id,
                            'user_id' => $vendor->id,
                        ];
                        Category::create($data);
                    }
                }
            }
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
            $data['pin'] = $request->pin;
            Customer::where('id',$request->user()->id)->update($data);
            $customer = Customer::where('id',$request->user()->id)->first();
            return $this->sendSuccess('CUSTOMER DETAIL UPLOAD SUCCESSFULLY',$customer);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function UploadImage(UploadApi $request){
        try{
            if($request->hasFile('image')) {
                $image       = $request->file('image');
                $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $image->move(public_path('images/users'), $image_name);   
                
                Vendor::where('id',$request->user()->id)->update(['image' => $image_name]);
                return $this->sendSuccess('UPLOAD IMAGE SUCCESSFULLY',['image' => asset('public/images/users/'.$image_name)]);
            }else{
                return $this->sendFailed('IMAGE IS INVALID',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function ViewProfile(Request $request){
        try{
            $request->user()->image = asset('public/images/users/'.$request->user()->image);
            $request->user()->store_image = asset('public/images/users/'.$request->user()->store_image);

            $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
            $request->user()->active_store_name = isset($vendor->store_name) ? $vendor->store_name :'';
            if(!empty($vendor)){
                $request->user()->active_store_image = asset('public/images/users/'.$vendor->store_image);
            }else{
                $request->user()->active_store_image = "";
            }
            return $this->sendSuccess('DETAIL FETCH SUCCESS',$request->user());
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetStates(){
        try{
            $states = DB::table('states')->select('id','name','iso2 as code')->orderBy('name','ASC')->get();
            return $this->sendSuccess('STATES FETCH SUCCESSFULLY',$states);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetCities(Request $request){
        try{
            $states = DB::table('cities')->where('state_id',$request->state_id)->select('id','name')->orderBy('name','ASC')->get();
            return $this->sendSuccess('CITIES FETCH SUCCESSFULLY',$states);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

}
