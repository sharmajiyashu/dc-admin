<?php

namespace App\Http\Controllers\Api;
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
use App\Models\Slab;
use App\Models\StoreLink;

class VendorController extends Controller
{
    use ApiResponse;

    function VendorRegisterLoginMobile(VendorRegisterLoginMobileRequest $request){
        try{
            $customer = Vendor::updateOrCreate(['mobile' => $request->mobile,'role_id' => Role::$vendor]);

            if(empty($customer->store_code)){
                $customer->store_code = $this->GenerateStoreCode();
            }
            // $otp = rand(1000,9999);
            $otp = 1234;
            $customer->otp = $otp;
            $customer->save();
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
            $carts = WishCart::where('vendor_id',$request->user()->id)->orderBy('id','DESC')->get();
            foreach($carts as $key=>$val){
                $customer = Customer::where('id',$val->user_id)->first();
                $val['customer_name'] = isset($customer->name) ? $customer->name :'';
                $val['customer_mobile'] = isset($customer->mobile) ? $customer->mobile :'';
                $product = Product::where('id',$val->product_id)->first();
                $val['product_name'] = isset($this->GetProductData($val->product_id)->name) ? $this->GetProductData($val->product_id)->name :'';
                $val['category_name'] = isset($this->CategoryData($this->GetProductData($val->product_id)->category_id)->title) ? $this->CategoryData($this->GetProductData($val->product_id)->category_id)->title :'';
                
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
                if($slab->status == Slab::$active){
                    Slab::where('id',$request->slab_id)->update(['status' => Slab::$inactive]);
                }else{
                    Slab::where('id',$request->slab_id)->update(['status' => Slab::$active]);
                }
            }

            if(!empty($request->link_id)){
                $store_link = StoreLink::where('id',$request->link_id)->first();
                if($store_link->status == StoreLink::$active){
                    StoreLink::where('id',$request->link_id)->update(['status' => Slab::$inactive]);
                }else{
                    StoreLink::where('id',$request->link_id)->update(['status' => Slab::$active]);
                }
            }

            if(!empty($request->category_id)){
                $category = Category::where('id',$request->category_id)->first();
                if($category->status == Category::$active){
                    Category::where('id',$request->category_id)->update(['status' => Category::$inactive]);
                }else{
                    Category::where('id',$request->category_id)->update(['status' => Category::$active]);
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


}
