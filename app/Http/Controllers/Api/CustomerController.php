<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterLoginMobileRequest;
use App\Http\Requests\CustomersUpdateDetail;
use App\Http\Requests\VarifyOtp;
use App\Http\Requests\UploadApi;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Role;
use App\Models\Product;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Doctrine\Common\Lexer\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $remember_token = $request->device_token;
            $customer->remember_token = $remember_token;
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
            if(!empty($request->store_name)){
                $data['store_name'] = $request->store_name;
            }
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
            // $states = DB::table('states')->select('id','name','iso2 as code')->orderBy('name','ASC')->get();

            $states = config('states');

            $collect = collect($states);
            $filteredCollection = $collect->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'code' => $item['iso2']
                ];
            });
            return $this->sendSuccess('STATES FETCH SUCCESSFULLY',$filteredCollection);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetCities(Request $request){
        try{
            $state_id = $request->state_id;
            $states = config('cities');
            $collect = collect($states)->map(function($item) use($state_id){
                if($state_id == $item['state_id']){
                    return[
                        'id' => $item['id'],
                        'name' => $item['name']
                    ];
                }
            })->filter()
            ->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE)
            ->values();
            return $this->sendSuccess('CITIES FETCH SUCCESSFULLY',$collect);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function getHomeData(Request $request){
        try{
            if(!empty($request->user()->active_store_code)){
                $categories = Helper::getCustomerCategories($request->user()->id);
                $arrival_products = Helper::getCustomerArrivalsProducts($request->user()->id);
                $user_data = Vendor::find($request->user()->id);
                $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
                $user_data['active_store_name'] = $vendor->store_name;
                $cart_count = Cart::where('user_id',$request->user()->id)->where('store_code',$request->user()->active_store_code)->where('status','0')->count();
                $cart_amount = Cart::where('user_id',$request->user()->id)->where('store_code',$request->user()->active_store_code)->where('status','0')->sum('total');
                return $this->sendSuccess('Data FETCH SUCCESSFULLY',[
                    'categories' => $categories,
                    'arrival_products' => $arrival_products,
                    'profile' => $user_data,
                    'cart_detail' => [
                        'cart_count' => $cart_count,
                        'cart_amount' => $cart_amount
                    ]
                ]);
            }else{
                return $this->sendFailed('you do not have any active store plese select an active store',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function deleteAccount(){
        try{
            $user_id = auth()->user()->id;
            User::where('id',$user_id)->delete();
            SlabLink::where('user_id',$user_id)->delete();
            StoreLink::where('vendor_id',$user_id)->delete();
            StoreLink::where('user_id',$user_id)->delete();
            Slab::where('user_id',$user_id)->delete();
            return $this->sendSuccess('USER DELETE SUCCESSFULLY',);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

}
