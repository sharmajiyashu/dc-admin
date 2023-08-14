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
use App\Http\Requests\GetSlabCustomerApi;
use App\Models\SlabLink;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ApiResponse;
    public function CreateUpdateProduct(CreateUpdateProductApi $request){
        try{            
            if($request->user()->role_id == Role::$vendor){
                $data = $request->validated();
                $data['name'] = Str::upper($request->name);
                $data['detail'] = $request->detail;
                $data['unit'] = $request->unit;
                $data['user_id'] = $request->user()->id;
                $data['fetch_product_id'] = $request->fetch_product_id;

                if($request->stock == 0 && $request->stock != null){
                    $data['is_limited'] = '1';
                   
                }elseif(!empty($request->stock)){
                    $data['is_limited'] = '1';
                }else{
                    $data['is_limited'] = '0';
                }

                if(!empty($request->id)){
                    $check = Product::where('user_id',$request->user()->id)->where('id','!=',$request->id)->where('name',$data['name'])->count();
                }else{
                    $check = Product::where('user_id',$request->user()->id)->where('name',$data['name'])->count();
                }

                if($check > 0){
                    return $this->sendFailed('The name has already been taken.',200);
                }

                if(!empty($request->packing_quantity)){
                    $data['packing_quantity'] = $request->packing_quantity;
                }else{
                    $data['packing_quantity'] = '1';
                }

                    $old_product = Product::where('id',$request->id)->first();
                    if(!empty($old_product->images)){
                        $images = json_decode($old_product->images);
                    }elseif($request->fetch_product_id){
                        $fetch_product_data = Product::where('id',$request->fetch_product_id)->first();
                        if(!empty($fetch_product_data->images)){
                            $images = json_decode($fetch_product_data->images);
                        }else{
                            $images = [];    
                        }
                    }else{
                        $images = [];
                    }
               
                    if($request->hasFile('image_1')) {
                        $image_name = time().rand(1,100).'-'.$request->image_1->getClientOriginalName();
                        $image_name = preg_replace('/\s+/', '', $image_name);
                        $request->image_1->move(public_path('images/products'), $image_name);
                        if(isset($images[0])){
                            $images[0] = $image_name;
                        }else{
                            $images[] = $image_name;
                        }
                    }

                    if($request->hasFile('image_2')) {
                        $image_name = time().rand(1,100).'-'.$request->image_2->getClientOriginalName();
                        $image_name = preg_replace('/\s+/', '', $image_name);
                        $request->image_2->move(public_path('images/products'), $image_name);
                        if(isset($images[1])){
                            $images[1] = $image_name;
                        }else{
                            $images[] = $image_name;
                        }
                    }

                    if($request->hasFile('image_3')) {
                        $image_name = time().rand(1,100).'-'.$request->image_3->getClientOriginalName();
                        $image_name = preg_replace('/\s+/', '', $image_name);
                        $request->image_3->move(public_path('images/products'), $image_name);
                        if(isset($images[2])){
                            $images[2] = $image_name;
                        }else{
                            $images[] = $image_name;
                        }
                    }

                    if($request->hasFile('image_4')) {
                        $image_name = time().rand(1,100).'-'.$request->image_4->getClientOriginalName();
                        $image_name = preg_replace('/\s+/', '', $image_name);
                        $request->image_4->move(public_path('images/products'), $image_name);
                        if(isset($images[3])){
                            $images[3] = $image_name;
                        }else{
                            $images[] = $image_name;
                        }
                    }

                    if(!empty($images)){
                        $data['images'] = json_encode($images);
                    }
                    
                if(!empty($request->fetch_product_id)){
                    $fetch_prod = Product::where('id',$request->fetch_product_id)->first();
                    $category_admin_user = Category::where('admin_id',$fetch_prod->category_id)->where('user_id',$request->user()->id)->first();
                    $data['category_id'] = isset($category_admin_user->id) ? $category_admin_user->id :'';
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
            if($request->user()->role_id == Role::$customer){
                $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
                if(!empty($vendor)){
                    $categories = Category::where(['status' => Category::$active ,'is_delete' => '0' ,'user_id' => $vendor->id ,'is_admin' => '0'])->get();
                }else{
                    $categories = Category::where(['status' => Category::$active ,'is_delete' => '0' ,'user_id' => 00 ,'is_admin' => '0'])->get();
                }
            }elseif($request->user()->role_id == Role::$vendor){
                $categories = Category::where(['is_delete' => '0' ,'user_id' => $request->user()->id ,'is_admin' => '0'])->get();
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
            $products = Product::where(['user_id'=>$vendor->id,'status'=>'Active'])->where('is_admin','=','0')->where('is_delete','!=','1')->orderBy('id','DESC')->get();
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
            $products = Product::where(['user_id'=>$vendor->id,'status'=>'Active','is_delete' => '0' ,'is_admin' => '0'])->orderBy('id','DESC')->limit(10)->get();
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
                $data = [
                            'name' => $val['name'],
                            'category_id' => $val['category_id'],
                            'sp' => isset($val['sp']) ? $val['sp'] :'0',
                            'mrp' => isset($val['mrp']) ? $val['mrp'] :'0',
                            'stock' => isset($val['stock']) ? $val['stock'] :'0',
                            'order_limit' => isset($val['order_limit']) ? $val['order_limit'] :'0',
                            'quantity' => isset($val['quantity']) ? $val['quantity'] :'',
                            'packing_quantity' => isset($val['packing_quantity']) ? $val['packing_quantity'] :'',
                            'user_id' => $request->user()->id,
                            'unit' => isset($val['unit']) ? $val['unit'] :'',
                        ];

                        if(empty($data['sp'])){
                            $data['sp'] = $data['mrp'];
                        }

                        if(empty($data['packing_quantity'])){
                            $data['packing_quantity'] = 1;
                        }

                        if(empty($data['stock'])){
                            $data['is_limited'] = '0';
                        }else{
                            $data['is_limited'] = '1';
                        }

                        if(empty($data['quantity'])){
                            $data['quantity'] = '1';
                            
                        }

                $images = [];
                // $image = $val['image'];

                if(!empty($val['image'])){

                    $imageData = $val['image'];
                    $exploded = explode(",", $imageData);
                    $exploded = isset($exploded[1]) ? $exploded[1] :'';
                    $decoded = base64_decode($exploded);

                    if ($decoded === false) {
                        // base64 string is not valid
                        // handle the error or return a response indicating invalid format
                    } else {
                        // base64 string is valid
                        // $imageName = 'image_' . time() . '.png';
                        $imageName = 'image_'.time() .rand(1,100).'.png';
                        $imagePath = public_path('images/products/' . $imageName);
                        File::put($imagePath, $decoded);
                        $images[] = $imageName;
                        $data['images'] = json_encode($images);
                    }

                }


                if(!empty($val['id'])) {
                    $product = Product::where('is_admin','!=','1')->find($val['id']); // Retrieve the existing record
                    if(!empty($product)){
                        $images = json_decode($product->images);
                        if(isset($imageName)){
                            $images[] = $imageName;
                            // $product->update([
                            //     'images' => json_encode($images),
                            // ]);
                            
                            $data['images'] = json_encode($images);
                        }
                        $product->update($data);
                        $total_update ++;
                    }
                }elseif(!empty($val['fetch_product_id'])){
                    $product = Product::where('is_admin','1')->find($val['fetch_product_id']); // Retrieve the existing record
                    if(!empty($product)){
                        if(!empty($imageName)){
                            $images = json_decode($product->images);
                            $images[] = $imageName;
                            $data['images'] = json_encode($images);
                        }else{
                            $data['images'] = $product->images;
                        }
                        $category_admin_user = Category::where('admin_id',$product->category_id)->where('user_id',$request->user()->id)->first();
                        $data['category_id'] = isset($category_admin_user->id) ? $category_admin_user->id :'';
                        Product::create($data);
                        $total_create ++;
                    }
                }else{
                    Product::create($data);
                    $total_create ++;
                }

            }
            return $this->sendSuccess('PRODUCT '.$total_create.' CREATE ,'.$total_update.' UPDATE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetSlabProduct(GetSlabCustomerApi $request){
        try{
            
            // $products = Product::select('product.*')->join('')

            $products = SlabLink::select('products.*')->join('products','products.id','=','slab_links.product_id')->where(['slab_links.user_id' => $request->user()->id ,'slab_links.slab_id' => $request->slab_id])->get();
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

}
