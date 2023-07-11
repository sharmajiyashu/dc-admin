<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard(){
        $total_category = Category::where('status','Active')->count();
        $total_product = Product::select('products.*')->join('users','users.id','=','products.user_id')->join('roles','roles.id','=','users.role_id')->where(['users.role_id'=> Role::$admin,'products.status' => 'Active'])->with('category')->orderBy('id','desc')->count();
        $total_vendors = User::where('role_id',Role::$vendor)->count();
        $total_customers = User::where('role_id',Role::$customer)->count();
        $total_orders = Order::count();

        return view('admin.dashboard',compact('total_category','total_product','total_vendors','total_customers','total_orders'))->with('success','ALLSSVHJBHJSBJVJ');
    }
}
