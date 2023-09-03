<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUpdateProductApi;
use App\Http\Requests\CreateOrderApi;
use App\Http\Requests\GetVendorProductsApi;
use App\Http\Requests\GetSlabCustomerApi;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\Vendor;
use App\Traits\ApiResponse;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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

                if($request->id){
                    $type = 1;
                    $check = Product::where('user_id',auth()->user()->id)->whereNot('id',$request->id)->where('name',$data['name'])->exists();
                }else{
                    $check = Product::where('user_id',auth()->user()->id)->where('name',$data['name'])->exists();
                    $type = 0;
                }

                if($check){
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
                        $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                        $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
                        $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                        $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
                        $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                        $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
                        $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                        $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
                        $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                        $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
                        $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                        $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
                        $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                        $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
                        $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                        $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
                    $type = 0;
                }
                $product = Product::updateOrCreate(['id' => $request->id],$data);
                $images = json_decode($product->images);
                if(!empty($images)){
                    foreach($images as $k){
                        $img[] = asset('public/images/products/'.$k);
                    }
                }
                $product['images'] = isset($img) ? $img :'';

                

                $check = SlabLink::where(['user_id' => $request->user()->id ,'product_id'=>$product->id])->count();
                if($check == 0){
                    $get_default_slab = Helper::getDefaultSlab();
                    SlabLink::create(['user_id' => $request->user()->id ,'product_id'=>$product->id ,'slab_id' => $get_default_slab]);
                }
                // Helper::sentMessageToCreateUpdateProduct($product->id,$type);
                if($type == 1){
                    return $this->sendSuccess('Product is updated successfully', $product);
                }else{
                    return $this->sendSuccess('Product is created successfully', $product);
                }
                
            }else{
                return $this->sendFailed('This For Only Vendors',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function sentNotificationToCustomers($product_id,$message){
        $product_data = Product::where('id',$product_id)->first();
        
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
                            $img[] = asset('public/images/products/thumb2/'.$k);
                        }
                    }
                    $slabs = SlabLink::where(['user_id' => $request->user()->id ,'product_id' => $val->id])->get()->map(function($slabs){
                        $slabs->slab_name = isset(Slab::where('id',$slabs->slab_id)->first()->name) ? Slab::where('id',$slabs->slab_id)->first()->name :'';
                        return $slabs->slab_name;
                    });
                    $val['slabs'] = $slabs;
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
                $categories = Helper::getCustomerCategories($request->user()->id);
            }elseif($request->user()->role_id == Role::$vendor){
                $categories = Category::where('is_delete','!=','1')->where(['user_id' => $request->user()->id ,'is_admin' => '0'])->get()->map(function($category){
                    $category->total_product = Product::where('category_id',$category->id)->count();
                    $category->image = asset('public/images/categories/'.$category->image);
                    return $category;
                });
            }
            if(!empty($categories)){
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
            if(!empty($request->user()->active_store_code)){
                $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
                $StoreLink = StoreLink::where('user_id',$request->user()->id)->where('vendor_id',$vendor->id)->first();
                if(!empty($StoreLink)){
                    if($StoreLink->status == StoreLink::$active){
                        $products = [];
                        $slab_link = SlabLink::where(['user_id' => $vendor->id, 'slab_id' => $StoreLink->slab_id])->get();
                        foreach($slab_link as $key => $val){
                            $check_slab = Slab::where('id',$val->slab_id)->first();
                            if($check_slab->status == Slab::$active){
                                 $product = Product::where('id',$val->product_id)->where(['user_id'=>$vendor->id,'status'=>'Active'])->where('is_admin','=','0')->where('is_delete','!=','1')->orderBy('id','DESC')->first();
                                if(!empty($product)){
                                    $products[] = $product;    
                                }
                            }
                        }
                        foreach($products as $key=>$val){
                            if(!empty($val['images'])){
                                $images = json_decode($val['images']);
                                if(!empty($images)){
                                    $img = [];
                                    foreach($images as $k){
                                        $img[] = asset('public/images/products/thumb2/'.$k);
                                    }
                                    $val['images'] = $img;
                                }else{
                                    $val['images'] = '';
                                }
                            }
                            $val['cart_count'] = Cart::where('user_id',$request->user()->id)->where('product_id',$val['id'])->where('status','0')->count();
                            
                        }
                        return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
                    }else{
                        Customer::where('id',$request->user()->id)->update(['active_store_code' => '']);
                        return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', []);
                    }
                }else{
                    return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', []);
                }
            }else{
                return $this->sendFailed('you do not have any active store plese select an active store',200);
            }
        }catch(\Throwable $e){
            // \Log::error($e->getMessage(). ' On Line '. $e->getLine());
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function NewArrivalProducts(CreateOrderApi $request){
        try{
            if(!empty($request->user()->active_store_code)){
                $products = Helper::getCustomerArrivalsProducts($request->user()->id);
                return $this->sendSuccess('PRODUCT FETCH SUCCESSFULLY', $products);
            }else{
                return $this->sendFailed('you do not have any active store plese select an active store',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function GetProductByName(Request $request){
        try{
            $products = Product::where(['is_admin' => '1' ,'status' => 'Active' ,'is_delete' => '0'])->where('products.name',$request->name)->orderBy('products.id','DESC')->first();
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
            $total_create = 0;
            $total_update = 0;
            $error_s = [];
            foreach($data as $key=>$val){
                $data = [
                            'name' => Str::upper($val['name']),
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

                if(!empty($val['image'])){

                    $imageData = $val['image'];
                    $exploded = explode(",", $imageData);
                    $exploded = isset($exploded[1]) ? $exploded[1] :'';
                    $decoded = base64_decode($exploded);

                    if ($decoded === false) {
                        $data['error'] = 'Image in invalid';
                        $error_s[] = $data;
                        continue;
                    } else {
                        $imageName = 'image_'.time() .rand(1,100).'.png';
                        $imagePath = public_path('images/products/' . $imageName);
                        File::put($imagePath, $decoded);

                        $thumbnail = Image::make(public_path('images/products') . '/' . $imageName)->fit(100, 100);
                        $thumbnail->save(public_path('images/products/thumb1') . '/' . $imageName);
                        $thumbnail_1 = Image::make(public_path('images/products') . '/' . $imageName)->fit(200, 200);
                        $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $imageName);
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
                            $data['images'] = json_encode($images);
                        }
                        $product->update($data);
                        $check = SlabLink::where(['user_id' => $request->user()->id ,'product_id'=>$product->id])->count();
                        if($check == 0){
                            $get_default_slab = Helper::getDefaultSlab();
                            SlabLink::create(['user_id' => $request->user()->id ,'product_id'=>$product->id ,'slab_id' => $get_default_slab]);
                        }
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
                                    $product = Product::create($data);
                                    $check = SlabLink::where(['user_id' => $request->user()->id ,'product_id'=>$product->id])->count();
                                    if($check == 0){
                                        $get_default_slab = Helper::getDefaultSlab();
                                        SlabLink::create(['user_id' => $request->user()->id ,'product_id'=>$product->id ,'slab_id' => $get_default_slab]);
                                    }
                        $total_create ++;
                    }
                }else{
                    $check_duplicate = Product::where('name',$data['name'])->where('user_id',$request->user()->id)->where('is_delete','0')->count();
                    if($check_duplicate > 0){
                        $data['error'] = 'Product name is already taken';
                        $error_s[] = $data;
                        continue;;
                    }
                    $product = Product::create($data);
                    $check = SlabLink::where(['user_id' => $request->user()->id ,'product_id'=>$product->id])->count();
                    if($check == 0){
                        $get_default_slab = Helper::getDefaultSlab();
                        SlabLink::create(['user_id' => $request->user()->id ,'product_id'=>$product->id ,'slab_id' => $get_default_slab]);
                    }
                    $total_create ++;
                }

            }
            return $this->sendSuccess('PRODUCT '.$total_create.' CREATE ,'.$total_update.' UPDATE SUCCESSFULLY',$error_s);
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
                        $img[] = asset('public/images/products/thumb2/'.$k);
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
