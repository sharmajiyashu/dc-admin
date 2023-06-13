<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUpdateProductApi;
use App\Traits\ApiResponse;


class ProductController extends Controller
{
    use ApiResponse;
    public function addProduct(CreateUpdateProductApi $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $data = $request->validated();
                $data['detail'] = $request->detail;
                $data['unit'] = $request->unit;
                $data['user_id'] = $request->user()->id;
                if(!empty($request->image)){    
                    $image = [];
                    foreach($request->image as $key=>$val){ 
                        $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                        $val->move(public_path('images/products'), $image_name);
                        $image[] = $image_name;
                    }
                    $dd_image = json_encode($image);
                    $data['images'] = isset($dd_image) ? $dd_image : '';
                }
                $product = Product::create($data);
                return $this->sendSuccess('PRODUCT STORE SUCCESSFULLY',['data' => $product]);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
        
        
        // Product::create($data);

    }
}
