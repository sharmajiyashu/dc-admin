<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard(){
        $total_category = Category::where('status','Active')->count();
        $total_product = Product::where('status','Active')->count();
        return view('admin.dashboard',compact('total_category','total_product'));
    }
}
