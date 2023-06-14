<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUpdateProductApi;
use App\Http\Requests\GetVendorProductsApi;
use App\Traits\ApiResponse;


class ProductController extends Controller
{
    use ApiResponse;
    public function CreateUpdateProduct(CreateUpdateProductApi $request){
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
                        $image_name = preg_replace('/\s+/', '', $image_name);
                        $val->move(public_path('images/products'), $image_name);
                        $image[] = $image_name;
                    }
                    $dd_image = json_encode($image);
                    $data['images'] = isset($dd_image) ? $dd_image : '';
                }
                $product = Product::updateOrCreate(['id' => $request->id],$data);
                $images = json_decode($product->images);
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                    $product['images'] = $img;
                return $this->sendSuccess('PRODUCT STORE SUCCESSFULLY', $product);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetVendorProducts(GetVendorProductsApi $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $products = Product::where('user_id',$request->user()->id)->orderBy('id','DESC')->get();
                foreach($products as $key=>$val){
                    $images = json_decode($val['images']);
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                    $val['images'] = $img;
                }
                return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetAdminProducts(Request $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $products = Product::select('products.name','products.detail','products.images','products.status')->join('users','users.id','=','products.user_id')->join('roles','roles.id','=','users.role_id')->where(['users.role_id'=> Role::$admin,'products.status' => 'Active'])->orderBy('products.id','DESC')->get();
                foreach($products as $key=>$val){
                    $images = json_decode($val['images']);
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                    $val['images'] = $img;
                }
                return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetAllCategories(Request $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $categories = Category::where('status','Active')->get();
                return $this->sendSuccess('CATEGORIES FETCH SUCCESSFULLY', $categories);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }
}
