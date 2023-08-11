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
use GuzzleHttp\Client;

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


    public function SendNotification()
	{
		$device_id = "";
		$title = "Test Title";
		$body = "Test Body";
		Helper::SendNotification($device_id,$title,$body);
	}

}
