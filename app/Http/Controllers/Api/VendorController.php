<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Requests\VendorsUpdateDetail;
use App\Http\Requests\VendorRegisterLoginMobileRequest;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Vendor;
use App\Models\WishCart;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use App\Http\Requests\ChangeStatusApi;
use App\Http\Requests\UpdateCategoryPackingApi;
use App\Http\Requests\UploadApi;
use App\Models\Notification;
use App\Models\Slab;
use App\Models\StoreLink;
use App\Models\User;

class VendorController extends Controller
{
    use ApiResponse;

    function VendorRegisterLoginMobile(VendorRegisterLoginMobileRequest $request){
        try{
            $customer = Vendor::updateOrCreate(['mobile' => $request->mobile,'role_id' => Role::$vendor]);

            if(empty($customer->store_code)){
                $customer->store_code = $this->GenerateStoreCode();
            }
            $otp = rand(1000,9999);
            // $otp = 1234;
            $customer->otp = $otp;
            $customer->save();
            Helper::sendOtp($request->mobile,$otp);
            return $this->sendSuccess('USER OTP SENT SUCCESSFULLY',['is_register' => $customer->is_register,'otp' => $otp,'user_id' => $customer->id,'pin' => $customer->pin ]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function GenerateStoreCode(){
        $store_code = Str::upper(Str::random(7));
        if(Vendor::where('store_code',$store_code)->first()){
            $this->GenerateStoreCode();
        }else{
            return $store_code;
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
            $data['pin'] = $request->pin;
            $data['store_name'] = $request->store_name;
            Vendor::where('id',$request->user()->id)->update($data);
            $vandor = Vendor::where('id',$request->user()->id)->first();
            return $this->sendSuccess('Vandor DETAIL UPLOAD SUCCESSFULLY',$vandor);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function WithListItem(Request $request){
        try {
            $carts = WishCart::where('vendor_id',$request->user()->id)->where(['status' => '1'])->orderBy('id','DESC')->get();
            foreach($carts as $key=>$val){
                $customer = Customer::where('id',$val->user_id)->first();
                $val['customer_name'] = isset($customer->name) ? $customer->name :'';
                $val['customer_store_name'] = isset($customer->store_name) ? $customer->store_name :'';
                $val['customer_mobile'] = isset($customer->mobile) ? $customer->mobile :'';
                $product = Product::where('id',$val->product_id)->first();
                if(!empty($product->images)){
                    $images = json_decode($product->images);
                    if(!empty($images[0])){
                        $image = asset('public/images/products/'.$images[0]);
                    }else{
                        $image = '';
                    }
                }else{
                    $image = '';
                }
                $val['product_name'] = isset($this->GetProductData($val->product_id)->name) ? $this->GetProductData($val->product_id)->name :'';
                $val['category_name'] = isset($this->CategoryData($this->GetProductData($val->product_id)->category_id)->title) ? $this->CategoryData($this->GetProductData($val->product_id)->category_id)->title :'';
                $val['category_id'] = isset($this->CategoryData($this->GetProductData($val->product_id)->category_id)->id) ? $this->CategoryData($this->GetProductData($val->product_id)->category_id)->id :'';
                $val['product_image'] = $image;
            }
            return $this->sendSuccess('WISHLIST ITEMS GET SUCCESSFULLY',$carts);
        } catch (\Throwable $e) {
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function GetProductData($id){
        return Product::where('id',$id)->first();
    }

    function CategoryData($id){
        return Category::where('id',$id)->first();
    }

    public function ChangeStatus(ChangeStatusApi $request){
        try{

            if(!empty($request->product_id)){
                $product = Product::where('id',$request->product_id)->first();
                if($product->status == Product::$active){
                    Product::where('id',$request->product_id)->update(['status' => Product::$inactive]);
                }else{
                    Product::where('id',$request->product_id)->update(['status' => Product::$active]);
                }
            }

            if(!empty($request->slab_id)){
                $slab = Slab::where('id',$request->slab_id)->first();
                // if($slab->is_default != '1'){
                    if($slab->status == Slab::$active){
                        Slab::where('id',$request->slab_id)->update(['status' => Slab::$inactive]);
                    }else{
                        Slab::where('id',$request->slab_id)->update(['status' => Slab::$active]);
                    }    
                // }else{
                    // return $this->sendFailed('You cant change default slab',200);
                // }
            }

            if(!empty($request->link_id)){
                $store_link = StoreLink::where('id',$request->link_id)->first();
                if($store_link->status == StoreLink::$active){
                    StoreLink::where('id',$request->link_id)->update(['status' => StoreLink::$inactive]);
                }else{
                    StoreLink::where('id',$request->link_id)->update(['status' => StoreLink::$active]);
                }
            }

            if(!empty($request->category_id)){
                $category = Category::where('id',$request->category_id)->first();
                if($category->status == Category::$active){
                    Category::where('id',$request->category_id)->update(['status' => Category::$inactive]);
                    // Product::where('category_id',$request->category_id)->update(['status' => Product::$inactive]);
                }else{
                    Category::where('id',$request->category_id)->update(['status' => Category::$active]);
                    // Product::where('category_id',$request->category_id)->update(['status' => Product::$active]);
                }
            }

            if(!empty($request->user_id)){
                $customers = StoreLink::where(['user_id' => $request->user_id ,'vendor_id' => $request->user()->id])->first();
                if(!empty($customers)){
                    if($customers->status == StoreLink::$active){
                        $user_data = Customer::where('id',$request->user_id)->first();
                        if($request->user()->store_code == $user_data->active_store_code){
                            $store_link = StoreLink::where('user_id',$request->user_id)->where('status',StoreLink::$active)->first();
                            $user_data->update(['active_store_code' => isset($store_link->store_code) ? $store_link->store_code :'']);
                        }
                        StoreLink::where('id',$customers->id)->update(['status' => StoreLink::$inactive]);
                        Helper::sentNotificationForActiveInactiveUser($request->user_id,$request->user()->id,StoreLink::$inactive);
                    }else{
                        StoreLink::where('id',$customers->id)->update(['status' => StoreLink::$active]);
                        Helper::sentNotificationForActiveInactiveUser($request->user_id,$request->user()->id,StoreLink::$active);
                    }
                }
            }

            return $this->sendSuccess('STATUS CHANGE SUCCESSFULLY');

        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function UpdateCategoryPackage(UpdateCategoryPackingApi $request){
        try{
            Category::where('user_id',$request->user()->id)->where('id',$request->category_id)->update(['packing_quantity' => $request->packing_quantity]);
            return $this->sendSuccess('PACKING QUANTITY CHANGE SUCCESSFULLY');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function UploadStoreImage(UploadApi $request){
        try{
            if($request->hasFile('image')) {
                $image       = $request->file('image');
                $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $image->move(public_path('images/users'), $image_name);   
                
                Vendor::where('id',$request->user()->id)->update(['store_image' => $image_name]);
                return $this->sendSuccess('UPLOAD IMAGE SUCCESSFULLY',['image' => asset('public/images/users/'.$image_name)]);
            }else{
                return $this->sendFailed('IMAGE IS INVALID',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function getNotificatons(Request $request){
        try{
            $notifications = Notification::where('user_id',$request->user()->id)->orderBy('id','desc')->get();
            $count = Notification::where('user_id',$request->user()->id)->where('status','1')->orderBy('id','desc')->count();
            Helper::removeBefore7daysData();
            if(!empty($notifications)){
                return $this->sendSuccess('There are '.$count.' unseen  messages',['count'=> $count ,'is_notify' => $request->user()->is_notify, 'notifications' => $notifications]);
            }else{
                return $this->sendSuccess('There are '.$count.' unseen messages',[ 'count'=> $count ,'is_notify' => $request->user()->is_notify, 'notifications' => $notifications]);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function changeNotifyStatus(Request $request){
        try{
            if($request->user()->is_notify == '1'){
                User::where('id',$request->user()->id)->update(['is_notify' => '0']);
            }else{
                User::where('id',$request->user()->id)->update(['is_notify' => '1']);
            }
            $user = User::select('is_notify')->where('id',$request->user()->id)->first();
            if($user->is_notify == '1'){
                $msg = "Notification unmute successfully";
            }else{
                $msg = "Notification mute successfully";
            }
            return $this->sendSuccess($msg,$user);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function seenNotification(Request $request){
        try{
            $notification = Notification::find($request->notification_id);
            if(!empty($notification)){
                if($notification->status == '1'){
                    $notification->update(['status' => '0']);
                    return $this->sendSuccess('Notification unseen successfully',$notification);
                }else{
                    return $this->sendSuccess('Notification is already seen',$notification);
                }
            }else{
                return $this->sendFailed('selected id is invalid',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        } 
    }

}
