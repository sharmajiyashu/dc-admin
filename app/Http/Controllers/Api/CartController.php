<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AddProductInCartApi;
use App\Http\Requests\RemoveCartItemApi;
use App\Http\Requests\DecrementCartQuantityApi;
use App\Http\Requests\GetCartItemApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\WishCart;
use App\Traits\ApiResponse;

class CartController extends Controller
{
    use ApiResponse;
    public function AddProductInCart(AddProductInCartApi $request){
        try{
            $product = Product::where('id',$request->product_id)->first();
            $vendor = Vendor::where('id',$product->user_id)->first();

            if($request->quantity % $product->packing_quantity) {
                return $this->sendFailed('PLEASE ENTER QUANTITY BY PACKING',200);
            }
            if($request->quantity < $product->packing_quantity){
                return $this->sendFailed('PLEASE ENTER MINIMUM QUANTITY '.$product->packing_quantity,200);
            }

            $cart = $request->validated();
            $cart['user_id'] = $request->user()->id;
            $cart['product_id'] = $product->id;
            $cart['p_price'] = $product->sp;
            $cart['p_mrp'] = $product->mrp;
            $cart['status'] = '0';
            $cart['store_code'] = $vendor->store_code;
            $cart['vendor_id'] =$vendor->id;

            $last_cart = Cart::where(['user_id' => $request->user()->id ,'product_id' => $request->product_id ,'status' => '0'])->first();
            if(empty($last_cart)){
                $quantity = $request->quantity;
                $cart_total = $quantity * $product->sp;
                $cart['quantity'] = $quantity;
                $cart['total'] = $cart_total;
                $cart = Cart::create($cart);
                $cart_id = $cart->id;
            }else{
                $quantity = $request->quantity + $last_cart->quantity;
                $cart_total = $quantity * $product->sp;
                $cart['quantity'] = $quantity;
                $cart['total'] = $cart_total;
                $cart = Cart::where('id',$last_cart->id)->update($cart);
                $cart_id = $last_cart->id;
            }
            return $this->sendSuccess('ADD PRODUCT IN CART SUCCESSFULLY');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }


    public function CartItem(GetCartItemApi $request){
        try{
            $carts = Cart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code,'status' => '0'] )->get();
            $cart_total = Cart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code,'status' => '0'])->sum('total');
            foreach($carts as $key=>$val){
                $product = $this->getProductData($val->product_id);
                $val->product_name = isset($product->name) ? $product->name :'';
                $val->product_image = isset($product->images) ? $product->images :'';
                $val->packing_quantity = isset($product->packing_quantity) ? $product->packing_quantity :'';
                $val->category_name = isset($product->category_name) ? $product->category_name :'';
                $val->category_image = isset($product->category_image) ? $product->category_image :'';
            }

            $with_cart = WishCart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code,'status' => '0'] )->get();
            foreach($with_cart as $key=>$val){
                $product = $this->getProductData($val->product_id);
                $val->product_name = isset($product->name) ? $product->name :'';
                $val->product_image = isset($product->images) ? $product->images :'';
                $val->product_detail = isset($product->detail) ? $product->detail :'';
                $val->packing_quantity = isset($product->packing_quantity) ? $product->packing_quantity :'';
                $val->category_name = isset($product->category_name) ? $product->category_name :'';
                $val->category_image = isset($product->category_image) ? $product->category_image :'';
            }
            return $this->sendSuccess('CART ITEM FETCH SUCCESSFULLY',['item' => $carts ,'wish_cart_item' => $with_cart , 'detail' => ['total' => $cart_total ,'delivery' => 'FREE' ,'grant_total' =>$cart_total]]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function wishListItems(Request $request){
        $with_cart = WishCart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code,'status' => '0'] )->get();
            foreach($with_cart as $key=>$val){
                $product = $this->getProductData($val->product_id);
                $val->product_name = isset($product->name) ? $product->name :'';
                $val->product_image = isset($product->images) ? $product->images :'';
                $val->product_detail = isset($product->detail) ? $product->detail :'';
                $val->packing_quantity = isset($product->packing_quantity) ? $product->packing_quantity :'';
                $val->category_name = isset($product->category_name) ? $product->category_name :'';
                $val->category_image = isset($product->category_image) ? $product->category_image :'';
            }
            return $this->sendSuccess('WISHLIST ITEM FETCH SUCCESSFULLY',$with_cart);
    }

    function getProductData($id){
        $product = Product::where('id',$id)->first();
        $category = Category::where('id',$product->category_id)->first();
        $images = json_decode($product->images);
        if(!empty($images)){
            $new_img = [];
            foreach($images as $val){
                $new_img[] = asset('public/images/products/'.$val);
            }
        }
        $product->images = isset($new_img)  ? $new_img :'';
        if(!empty($category)){
            $product->category_name = isset($category->title)  ? $category->title :'';
            $product->category_image = asset('public/images/categories/'.$category->image);
        }else{
            $product->category_name = '';
            $product->category_image = "";
        }
        return $product;
    }

    public function RemoveCartItem(RemoveCartItemApi $request){
        try{
            Cart::where(['id'=>$request->cart_id,'user_id' => $request->user()->id ,'status' => '0'])->delete();
            return $this->sendSuccess('CART ITEM REMOVE SUCCESSFULLY','');
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function DecrementCartQuantity(DecrementCartQuantityApi $request){
        try{
            $cart = Cart::where(['id'=>$request->cart_id,'user_id' => $request->user()->id])->first();
            $product = Product::where('id',$cart->product_id)->first();
            if($request->quantity % $product->packing_quantity) {
                return $this->sendFailed('PLEASE ENTER QUANTITY BY PACKING',200);
            }
            if($request->quantity < $product->packing_quantity){
                return $this->sendFailed('PLEASE ENTER MINIMUM QUANTITY '.$product->packing_quantity,200);
            }
            if($cart->quantity <= $product->packing_quantity){
                return $this->sendFailed('MINIMUM QUANTITY IS '.$product->packing_quantity,200);
            }

            $sum_cart = Cart::where(['id'=>$request->cart_id,'user_id' => $request->user()->id])->sum('quantity');
            $sum_wist_cart = WishCart::where('user_id',$request->user()->id)->where('product_id',$cart->product_id)->sum('quantity');
            $total_sum = $sum_cart + $sum_wist_cart;

            if($request->quantity <= ($total_sum - $product->packing_quantity)){
                if($sum_wist_cart > 0){
                    if($request->quantity > $sum_wist_cart){
                        WishCart::where('user_id',$request->user()->id)->where('product_id',$cart->product_id)->delete();
                        $this->AddStockInProduct($product->id,$sum_wist_cart);
                    }else{
                        $WishCart = WishCart::where('user_id',$request->user()->id)->where('product_id',$cart->product_id)->first();
                        $quantity = $WishCart->quantity - $request->quantity;
                        if($quantity > 0){
                            $cart_total = $quantity * $product->sp;
                            $cart_update = [];
                            $cart_update['quantity'] = $quantity;
                            $cart_update['total'] = $cart_total;
                            $cart_update['p_price'] = $product->sp;
                            $cart_update['p_mrp'] = $product->mrp;
                            WishCart::where('user_id',$request->user()->id)->where('product_id',$cart->product_id)->update($cart_update);
                        }else{
                            WishCart::where('user_id',$request->user()->id)->where('product_id',$cart->product_id)->delete();
                        }

                        return $this->sendSuccess('DECREMENT CART QUANTITY SUCCESSFULLY','');
                    }
                }

                if($request->quantity > 0){
                    $this->AddStockInProduct($product->id,$request->quantity);
                    $quantity = $cart->quantity - $request->quantity;
                    $cart_total = $quantity * $product->sp;
                    $cart_update = [];
                    $cart_update['quantity'] = $quantity;
                    $cart_update['total'] = $cart_total;
                    $cart_update['p_price'] = $product->sp;
                    $cart_update['p_mrp'] = $product->mrp;
                    Cart::where('id',$cart->id)->update($cart_update);
                    $cart = Cart::where('id',$cart->id)->first();
                    return $this->sendSuccess('DECREMENT CART QUANTITY SUCCESSFULLY','');
                }else{
                    return $this->sendFailed('THE QUANTITY IS ZERO ',200);
                }
            }else{
                return $this->sendFailed('THE QUANTITY IS MORE THEN CART VALUE ',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function AddStockInProduct($id,$stock){
        $product  = Product::where('id',$id)->first();
        Product::where('id',$product->id)->update(['stock' => $product->stock + $stock]);
    }

    public function RemoveAllItem(Request $request){
        try{
            $cart = Cart::where('user_id',$request->user()->id)->where('status','0');
            if($cart->count() > 0){
                $cart->delete();
                return $this->sendSuccess('DELETE CART ITEM SUCCESSFULLY','');
            }else{
                return $this->sendFailed('CART ITEM IS EMPITY ',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }




}
