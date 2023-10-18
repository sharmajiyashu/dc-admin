<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUpdateSlabApi;
use App\Http\Requests\AddCustomerInSlabApi;
use App\Http\Requests\GetSlabCustomerApi;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Traits\ApiResponse;


class SlabController extends Controller
{
    use ApiResponse;
    
    public function CreateUpdate(CreateUpdateSlabApi $request){
        try{
            $data = $request->validated();
            $data['name'] = Helper::createUpperString($request->name);
            $data['user_id'] = $request->user()->id;
            $msg = 'added';
            if(!empty($request->id)){
                $msg = 'updated';
                $check = Slab::where(['name' => $data['name'] ,'user_id' => $request->user()->id])->where('id','!=',$request->id)->count();
            }else{
                $check = Slab::where(['name' => $data['name'] ,'user_id' => $request->user()->id])->count();
            }
            if($check > 0){
                return $this->sendFailed('The name has already been taken.',200);
            }
            $product = Slab::updateOrCreate(['id' => $request->id],$data);
            return $this->sendSuccess('Slab is '.$msg.' successfully');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetSlab(Request $request){
        try{
            $slabs = Slab::where('user_id',$request->user()->id)->orWhere('is_default','1')->get();
            foreach($slabs as $key=>$val){
                $total_products = SlabLink::where(['slab_id' => $val->id ,'user_id' => $request->user()->id])->count();
                $val['total_products'] = $total_products;
                $total_customers = StoreLink::where(['vendor_id' => $request->user()->id , 'slab_id' => $val->id])->count();
                $val['total_customers'] = $total_customers;
            }
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

    public function AddProductInSlab(Request $request){
        try{
            if(!empty($request->all())){
                $total_added = 0;
                $data = $request->all();
                $product = $data['product_id'];
                $slabs = $data['slab_id'];
                foreach($product as $key=>$val){
                    foreach($slabs as $k=>$v){
                        $slab_id = $v;
                        $product_id = $val;
                        $check = SlabLink::where(['product_id' => $product_id ,'slab_id' => $slab_id ,'user_id' => $request->user()->id])->count();
                        if($check == 0){
                            SlabLink::create(['product_id' => $product_id ,'slab_id' => $slab_id ,'user_id' => $request->user()->id]);
                            $total_added ++;
                        }
                    }
                }
                return $this->sendSuccess($total_added.' PRODUCT ADDED INTO SLAB SUCCESSFULLY','');
            }else{
                return $this->sendFailed('Data Is Empty',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function removeProductFromSlab(Request $request){
        try{
            $slab_link = SlabLink::where(['slab_id' => $request->slab_id ,'product_id' => $request->product_id ,'user_id' => $request->user()->id])->first();     

            if(!empty($request->products)){
                $count = 0;
                $js = json_decode($request->products);
                foreach($js as $key => $val){
                    $slab_link_2 = SlabLink::where(['slab_id' => $request->slab_id ,'product_id' => $val ,'user_id' => $request->user()->id])->first();     
                    if(!empty($slab_link_2)){
                        if($slab_link_2->slab_id == Helper::getDefaultSlab()){
                        }else{
                            $slab_link_2->delete();
                            $count ++;
                        }
                    }
                }
                if(!empty($slab_link)){
                    if($slab_link->slab_id == Helper::getDefaultSlab()){
                   
                    }else{
                        $slab_link->delete();
                        $count ++;
                    }
                }
                return $this->sendSuccess($count.'  Product remove from slab','');
            }

            if(!empty($slab_link)){
                if($slab_link->slab_id == Helper::getDefaultSlab()){
                    return $this->sendFailed('You Cant Delete product from default slab',200);
                }else{
                    $slab_link->delete();
                    return $this->sendSuccess(' Product remove from slab','');
                }
            }else{
                return $this->sendFailed('No Slabs link found',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function deleteSlab(GetSlabCustomerApi $request){
        try{
            $slab = Slab::where(['id' => $request->slab_id ,'user_id' => $request->user()->id])->first();
            if(!empty($slab)){
                $store = StoreLink::where('slab_id',$slab->id)->where('vendor_id',$request->user()->id);
                $store_count = $store->count();
                $store_link = $store->first();
                if($store_count > 0){
                    return $this->sendFailed('Cannot delete slab, '.$store_count.' customers linked. Apologies for inconvenience.',200);
                }else{
                    $slab->delete();
                    return $this->sendSuccess('Slab Delete successfully','');
                }
            }else{
                return $this->sendFailed('No Slabs found for this user',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }




}
