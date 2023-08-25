<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard(){
        $total_category = Category::where('status','Active')->where('is_admin','1')->count();
        $total_product = Product::where('is_admin','1')->count();
        $total_vendors = User::where('role_id',Role::$vendor)->where('is_register','1')->count();
        $total_customers = User::where('role_id',Role::$customer)->where('is_register','1')->count();
        $total_orders = Order::count();
        return view('admin.dashboard',compact('total_category','total_product','total_vendors','total_customers','total_orders'))->with('success','ALLSSVHJBHJSBJVJ');
    }


    public function changes_slab_status(Request $request){
        $payment_status = Slab::where('id',$request->id)->first();
        if($payment_status->status == Slab::$active){
            $payment_status->update(['status' => Slab::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Slab::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_category_status(Request $request){
        $payment_status = Category::where('id',$request->id)->first();
        if($payment_status->status == Category::$active){
            $payment_status->update(['status' => Category::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Category::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_product_status(Request $request){
        $payment_status = Product::where('id',$request->id)->first();
        if($payment_status->status == Product::$active){
            $payment_status->update(['status' => Product::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Product::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_store_link_status(Request $request){
        $payment_status = StoreLink::where('id',$request->id)->first();
        if($payment_status->status == StoreLink::$active){
            $payment_status->update(['status' => StoreLink::$inactive]);
            Helper::sentNotificationForActiveInactiveUser($payment_status->user_id,$payment_status->vendor_id,StoreLink::$inactive);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => StoreLink::$active]);
            Helper::sentNotificationForActiveInactiveUser($payment_status->user_id,$payment_status->vendor_id,StoreLink::$active);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_notification_status(Request $request){
        $payment_status = User::where('id',$request->id)->first();
        if($payment_status->is_notify == '1'){
            $payment_status->update(['is_notify' => '0']);
            return json_encode(['0' ,'Is notify Inactive Successfully']);
        }else{
            $payment_status->update(['is_notify' => '1']);
            return json_encode(['1' ,'Is notify Active Successfully']);
        }
    }

}
