<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreLink;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\CreateOrderApi;
use App\Http\Requests\DeleteStoreRequestApi;
use App\Http\Requests\UpdateStoreStatusApi;
use App\Http\Requests\CustomersListApi;
use App\Http\Requests\AddCustomersByMobileApi;
use App\Models\Role;
use App\Models\Slab;

class StoreLinkController extends Controller
{
    use ApiResponse;
    public function SentRequest(CreateOrderApi $request){
        try{
            if(StoreLink::where(['store_code' => $request->store_code , 'user_id' => $request->user()->id])->first()){
                return $this->sendFailed('THE REQUEST IS ALREADY SUBMITTED',200);
            }else{
                $vendor = Vendor::where('store_code' ,$request->store_code)->first();
                $store = StoreLink::create(['user_id' => $request->user()->id ,'vendor_id' => $vendor->id,'store_code' => $vendor->store_code]);
                return $this->sendSuccess('SENT REQUEST SUCCESS SUCCESSFULLY', $store);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function DeleteRequest(DeleteStoreRequestApi $request){
        try{
            $store = StoreLink::where(['id' => $request->id]);
            $customer = Customer::where('id',$store->first()->user_id);
            if($customer->first()->active_store_code == $store->first()->store_code){
                $customer->update(['active_store_code' => '']);
            }
            $store->delete();
            return $this->sendSuccess('DELETE REQUEST SUCCESS SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function ListRequest(Request $request){
        try{
            $stores = StoreLink::where('user_id',$request->user()->id)->orderBy('id','DESC')->get();
            foreach($stores as $key=>$val){
                $vendor = $this->GetVendorDetail($val->vendor_id);
                $val->store_name = isset($vendor->store_name) ? $vendor->store_name :'';
                $val->store_image = isset($vendor->store_image) ? $vendor->store_image :'';
                $val->vendor_name = isset($vendor->name) ? $vendor->name :'';
            }
            return $this->sendSuccess('STORES DATA FETCH SUCCESSFULLY',$stores);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetVendorDetail($id){
        $vendor = Vendor::where('id',$id)->first();
        $vendor->store_image = asset('public/images/users/'.$vendor->image);
        return $vendor;
    }

    public function CustomersList(CustomersListApi $request){
        try{
            $stores = StoreLink::where('vendor_id',$request->user()->id);
            if(!empty($request->status)){
                $stores->where('status',$request->status);
            }
            $stores->orderBy('id','DESC');
            $stores = $stores->get();
            $results = [];
            foreach($stores as $key=>$val){
                $vendor = $this->GetVendorDetail($val->user_id);
                $slab = Slab::where('id',$val->slab_id)->first();
                $val->customer_name = isset($vendor->name)  ? $vendor->name :'';
                $val->customer_mobile = isset($vendor->mobile)  ? $vendor->mobile :'';
                $val->customer_city = isset($vendor->city)  ? $vendor->city :'';
                $val->slab_name = isset($slab->name) ? $slab->name :'';
                if($vendor->is_register != '1'){
                    unset($stores[$key]);
                }else{
                    $results[] = $val;
                }
            }
            return $this->sendSuccess('CUSTOMERS DATA FETCH SUCCESSFULLY',$results);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function ChangeRequestStatus(UpdateStoreStatusApi $request){
        try{
            StoreLink::where(['id'=>$request->id,'vendor_id' => $request->user()->id])->update(['status' => $request->status]);
            return $this->sendSuccess('CUSTOMERS STORE STATUS CHANGE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function AddCustomersByMobile(AddCustomersByMobileApi $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $customer = Vendor::where('mobile' ,$request->mobile)->where('role_id',Role::$customer)->first();
                $slab_check = Slab::where('id',$request->slab_id)->where('user_id',$request->user()->id)->first();
                if(!empty($slab_check)){
                    $slab_id = $slab_check->id;
                }else{
                    $slab_id = Slab::where('is_default','1')->first();
                    $slab_id = isset($slab_id->id) ? $slab_id->id :'';
                }

                if(!empty($customer)){
                    if(StoreLink::where(['user_id' => $customer->id , 'vendor_id' => $request->user()->id])->first()){
                        return $this->sendFailed('THE REQUEST IS ALREADY SUBMITTED',200);
                    }else{
                        $store = StoreLink::create(['vendor_id' => $request->user()->id ,'user_id' => $customer->id,'store_code' => $request->user()->store_code,'status' => '1','in_add' => '1' ,'slab_id' => $slab_id]);
                        Vendor::where('id',$customer->id)->update(['active_store_code' => $request->user()->store_code]);
                        return $this->sendSuccess('SENT REQUEST SUCCESS SUCCESSFULLY', $store);
                    }
                }else{
                    $customer = Customer::create(['mobile'=>$request->mobile ,'role_id' => Role::$customer ,'active_store_code' => $request->user()->store_code]);
                    $store = StoreLink::create(['vendor_id' => $request->user()->id,'user_id' => $customer->id,'store_code' => $request->user()->store_code ,'status' => '1','in_add' => '1' ,'slab_id' => $slab_id]);
                    return $this->sendSuccess('SENT REQUEST SUCCESS SUCCESSFULLY', $store);
                }
            }else{
                return $this->sendFailed('THIS IS ONLY FOR VENDOR',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function UpdateActiveStore(CreateOrderApi $request){
        try{
            Vendor::where('id',$request->user()->id)->update(['active_store_code'=> $request->store_code]);
            return $this->sendSuccess('ACTIVE STORE CODE UPDATE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetInAddCustomer(Request $request){
        try{
            $in_ads = StoreLink::where('vendor_id',$request->user()->id)->where('in_add','1')->get();
            foreach($in_ads as $key=>$val){
                $vendor = $this->GetVendorDetail($val->user_id);
                $val->customer_name = isset($vendor->name)  ? $vendor->name :'';
                $val->customer_mobile = isset($vendor->mobile)  ? $vendor->mobile :'';
                $val->customer_city = isset($vendor->city)  ? $vendor->city :'';
                $val->is_register  = isset($vendor->is_register)  ? $vendor->is_register :'';
            }
            return $this->sendSuccess('ADD IN CUSTOMERS FETCH SUCCESSFULLY',$in_ads);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function RemoveAdInCustomers(DeleteStoreRequestApi $request){
        try{
            StoreLink::where('vendor_id',$request->user()->id)->where('id',$request->id)->update(['in_add' => '0']);
            return $this->sendSuccess('REMOVE ADD IN CUSTOMERS FROM LIST SUCCESSFULLY');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }
    


}
