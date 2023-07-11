<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUpdateSlabApi;
use App\Http\Requests\AddCustomerInSlabApi;
use App\Http\Requests\GetSlabCustomerApi;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Slab;
use App\Models\StoreLink;
use App\Traits\ApiResponse;


class SlabController extends Controller
{
    use ApiResponse;
    
    public function CreateUpdate(CreateUpdateSlabApi $request){
        try{
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $product = Slab::updateOrCreate(['id' => $request->id],$data);
            return $this->sendSuccess('CREATE UPDATE SLAB SUCCESSFULLY');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetSlab(Request $request){
        try{
            $slabs = Slab::where('user_id',$request->user()->id)->orWhere('is_default','1')->get();
            return $this->sendSuccess('SLAB DATA FETCH SUCCESSFULLY',$slabs);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function AddCustomerInSlab(AddCustomerInSlabApi $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $store_link = StoreLink::where('vendor_id',$request->user()->id)->where('user_id',$request->user_id)->first();
                if(!empty($store_link)){
                    StoreLink::where('id',$store_link->id)->update(['slab_id'=>$request->slab_id]);
                    return $this->sendSuccess('ADD CUSTOMER IN SLAB SUCCESSFULLY');
                }else{
                    return $this->sendFailed('CUSTOMER NOT LINKED IN STORE CODE');
                }
            }else{
                return $this->sendFailed('THIS IS ONLY FOR VENDOR');
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetSlabCustomer(GetSlabCustomerApi $request){
        try{
            $store_link = StoreLink::where(['slab_id' => $request->slab_id , 'vendor_id' => $request->user()->id])->get();
            foreach($store_link As $key=>$val){
                $customer = Customer::where('id',$val->user_id)->first();
                $val['customer_name'] = isset($customer->name) ? $customer->name :'';
                $val['customer_mobile'] = isset($customer->mobile) ? $customer->mobile :'';
            }
            return $this->sendSuccess('GET SLAB CUSTOMERS SUCCESSFULLY',$store_link);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }



}
