<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Customer;
use App\Models\DemoProduct;
use Illuminate\Http\Request;
use App\Services\PayUService\Exception;
use App\Traits\ApiResponse;

class ApiController extends Controller
{
    use ApiResponse;
    public function checkmobile(Request $request){
        
        try {
            if(empty($request->mobile)){
                return json_encode([
                    'status' => 'false',
                    'message' => 'Mobile Number is Required',
                    'data' => '',
                ]);
            }else{
                $data = User::where('mobile',$request->mobile)->first();
                if(!empty($data)){
                    return json_encode([
                        'status' => 'true',
                        'message' => 'You are not register',
                        'data' => $data,
                    ]);
                }else{
                    return json_encode([
                        'status' => 'false',
                        'message' => 'You are not register',
                        'data' => '',
                    ]);
                }
            }
        }catch(\Exception $e){
            return json_encode([
                'status' => 'false',
                'message' => $e,
                'data' => '',
            ]);
        }

    }

    public function CustomerRegister(Request $request){
        try{
            Customer::create($request->all());
        }catch (\Exception $e) {
            return json_encode([
                'status' => 'false',
                'message' => $e,
                'data' => '',
            ]);
        }
    }

    public function getNotificatons(){
       
    }

    public function getDemoStores(){
        $category = Category::where('status',Category::$active)->where('is_admin','1')->get()->map(function($category){
            if (!empty($category->image)) {
				$category->image = asset('public/images/categories/' . $category->image);
			}
            return $category;
        });
        $product = DemoProduct::where('status',DemoProduct::$active)->get()->map(function($product){
            $image = $product->images;
            $product->images = Helper::transformImages($image);
            $product->original_images = Helper::transformOrignilImages($image);
            $check_category = Category::find($product->category_id);
            if($check_category){
                if($check_category->status == Category::$active){
                    return $product;
                }
            }
        });
        return $this->sendSuccess('Demo store fetch successfully',[
            'categories' => $category,
            'arrival_products' => $product->filter()->values()->take(10),
            'products' => $product->filter()->values(),
        ]);
    }

}
