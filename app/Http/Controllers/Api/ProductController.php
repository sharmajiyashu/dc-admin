<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUpdateProductApi;
use App\Http\Requests\CreateOrderApi;
use App\Http\Requests\GetVendorProductsApi;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                $data['fetch_product_id'] = $request->fetch_product_id;

                if(!empty($request->stock)){
                    $data['is_limited'] = '1';
                }else{
                    $data['is_limited'] = '0';
                }

                if(!empty($request->image)){ 
                    $image = [];
                    foreach($request->image as $key=>$val){ 
                        if(!empty($val)){
                            $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                            $image_name = preg_replace('/\s+/', '', $image_name);
                            $val->move(public_path('images/products'), $image_name);
                            $image[] = $image_name;
                        }
                    }
                    $dd_image = json_encode($image);
                    $data['images'] = isset($dd_image) ? $dd_image : '';
                }
                if(!empty($request->fetch_product_id)){
                    $fetch_prod = Product::where('id',$request->fetch_product_id)->first();
                    if(!empty($image)){
                        $fetch_prod_image = json_decode($fetch_prod->images);
                        foreach($fetch_prod_image as $key=>$val){
                            $image[] = $val;
                        }
                        $dd_image = json_encode($image);
                        $data['images'] = isset($dd_image) ? $dd_image : '';
                    }else{
                        $data['images'] = isset($fetch_prod->images) ? $fetch_prod->images :''; 
                    }
                    $data['category_id'] = isset($fetch_prod->category_id) ? $fetch_prod->category_id :''; 
                }
                $product = Product::updateOrCreate(['id' => $request->id],$data);
                $images = json_decode($product->images);
                if(!empty($images)){
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                }
                $product['images'] = isset($img) ? $img :''; 
                
                return $this->sendSuccess('PRODUCT CREATE SUCCESSFULLY', $product);
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function DeleteProduct(Request $request){
        try{
            Product::where('id',$request->product_id)->update(['is_delete' => '1']);
            return $this->sendSuccess('PRODUCT DELETE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetVendorProducts(GetVendorProductsApi $request){
        try{
            if($request->user()->role_id == Role::$vendor){
                $products = Product::where('user_id',$request->user()->id)->where('is_delete','=','0')->orderBy('id','DESC')->get();
                foreach($products as $key=>$val){
                    $images = json_decode($val['images']);
                    $img = [];
                    if(!empty($images)){
                        foreach($images as $k){
                            $img[] = asset('public/images/products/'.$k);
                        }
                    }
                    $val['images'] = $img;
                    $val['category_name'] = isset($this->GetCategoryDetail($val->category_id)->title) ? $this->GetCategoryDetail($val->category_id)->title : '';
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
                // $products = Product::select('products.name','products.detail','products.images','products.status')->join('users','users.id','=','products.user_id')->join('roles','roles.id','=','users.role_id')->where(['users.role_id'=> Role::$admin,'products.status' => 'Active'])->orderBy('products.id','DESC')->get();
                $products = Product::where(['is_admin' => '1' ,'status' => 'Active'])->orderBy('products.id','DESC')->get();
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
            if($request->role_id == Role::$customer){
                $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
                if(!empty($vendor)){
                    $categories = Category::where(['status' => Category::$active ,'is_delete' => '0' ,'user_id' => $vendor->id])->get();
                }else{
                    $categories = Category::where(['status' => Category::$active ,'is_delete' => '0' ,'user_id' => 00])->get();
                }
            }elseif($request->role_id == Role::$vendor){
                $categories = Category::where(['is_delete' => '0' ,'user_id' => $request->user()->id])->get();
            }   

            if(!empty($categories)){
                foreach($categories as $key=>$val){
                    if(!empty($val->image)){
                        $val['image'] = asset('public/images/categories/'.$val->image);
                    }
                }
                return $this->sendSuccess('CATEGORIES FETCH SUCCESSFULLY', $categories);
            }else{
                return $this->sendFailed('CATEGORIES NOT FOUND ',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetProduct(CreateOrderApi $request){
        try{
            $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
            $products = Product::where(['user_id'=>$vendor->id,'status'=>'Active'])->where('is_delete','!=','1')->orderBy('id','DESC')->get();
            foreach($products as $key=>$val){
                $images = json_decode($val['images']);
                if(!empty($images)){
                    $img = [];
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                    $val['images'] = $img;
                }else{
                    $val['images'] = '';
                }
            }
            return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function NewArrivalProducts(CreateOrderApi $request){
        try{
            $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
            $products = Product::where(['user_id'=>$vendor->id,'status'=>'Active','is_delete' => '0'])->orderBy('id','DESC')->limit(10)->get();
            foreach($products as $key=>$val){
                $images = json_decode($val['images']);
                if(!empty($images)){
                    $img = [];
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                    $val['images'] = $img;
                }else{
                    $val['images'] = '';
                }
            }
            return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetProductByName(Request $request){
        try{
            
            $products = Product::where(['is_admin' => '1' ,'status' => 'Active'])->where('products.name',$request->name)->orderBy('products.id','DESC')->first();
            if(!empty($products)){
                $images = json_decode($products->images);
                if(!empty($images)){
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                }
                $products->images = isset($img) ? $img :'';
                $products->category_name = isset($this->GetCategoryDetail($products->category_id)->title) ? $this->GetCategoryDetail($products->category_id)->title :'';
                return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
            }else{
                return $this->sendFailed('PRODUCT NOT FOUND',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetCategoryDetail($id){
        return Category::where('id',$id)->first();
    }

    public function AddBulkProduct(Request $request){
        try{
            $data = $request->all();

            // print_r($data);die;
            $total_create = 0;
            $total_update = 0;
            foreach($data as $key=>$val){
                $product = new Product();
                $product->name = $val['name'];
                $product->category_id = $val['category_id'];
                $product->sp = $val['sp'];
                $product->mrp = $val['mrp'];
                $product->stock = $val['stock'];
                $product->order_limit = $val['order_limit'];
                $product->quantity = $val['quantity'];
                $product->packing_quantity = $val['packing_quantity'];
                $product->user_id = $request->user()->id;
                $product->unit = $val['unit'];

                $images = [];
                // $image = $val['image'];
                
                if(!empty($val['image'])){
                    $imageData = $val['image'];
                    $exploded = explode(",", $imageData);
                    $decoded = base64_decode($exploded[1]);
                    $imageName = 'image_' . time() . '.png';
                    $imagePath = public_path('images/products/' . $imageName);

                    File::put($imagePath,$decoded);
                    $images[] = $imageName;
                    $product->images = json_encode($images);
                }
                

                if (!empty($val['id'])) {
                    $product = Product::find($val['id']); // Retrieve the existing record
                    $images = json_decode($product->images);
                    if(!empty($imageName)){
                        $images[] = $imageName;
                        $product->update([
                            'images' => json_encode($images),
                        ]);
                    }
                    $product->update([
                        'name' => $val['name'],
                        'category_id' => $val['category_id'],
                        'sp' => $val['sp'],
                        'mrp' => $val['mrp'],
                        'stock' => $val['stock'],
                        'order_limit' => $val['order_limit'],
                        'quantity' => $val['quantity'],
                        'packing_quantity' => $val['packing_quantity'],
                        'user_id' => $request->user()->id,
                        'unit' => $val['unit'],
                    ]);
                    $total_update ++;
                } else {
                    $product->save();
                    $total_create ++;
                }




            }
            return $this->sendSuccess('PRODUCT '.$total_create.' CREATE ,'.$total_update.' UPDATE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }
    
}
