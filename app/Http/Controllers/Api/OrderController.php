<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderApi;
use App\Http\Requests\CustomerOrderDetailApi;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Cart;
use App\Traits\ApiResponse;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Requests\DeleteOrderApi;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use ApiResponse;
    public function CreateOrder( CreateOrderApi $request){
        try{
            if(Cart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code ,'status' => '0'])->first()){
                $cart_total = Cart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code ,'status' => '0'])->sum('total');
                $vendor = Vendor::where('store_code',$request->user()->active_store_code)->first();
                $order = new Order();
                $order->order_id = $this->GenerateOrderID($vendor->store_name);
                $order->user_id = $request->user()->id;
                $order->vendor_id = $vendor->id;
                $order->store_code = $vendor->store_code;
                $order->amount = $cart_total;
                $order->note = $request->note;
                $order->save();
                Cart::where(['user_id'=>$request->user()->id,'store_code' => $request->user()->active_store_code ,'status' => '0'])->update(['order_id' => $order->id ,'status' => '1']);
                return $this->sendSuccess('ORDER CREATE SUCCESSFULLY', $order);
            }else{
                return $this->sendFailed('YOUR CART IS EMPTY',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    function GenerateOrderID($store_name){
        $substring = substr($store_name, 0, 3);
        $uppercasedString = Str::upper($substring);
        $store_code = $uppercasedString.mt_rand(1000000000, 9999999999);
        if(Vendor::where('store_code',$store_code)->first()){
            $this->generate_coupon();
        }else{
            return $store_code;
        }
    }

    public function CustomerOrderHistory(CreateOrderApi $request){
        try{
            $orders = Order::where(['user_id' => $request->user()->id,'store_code' => $request->user()->active_store_code])->orderBy('id','DESC')->get();
            foreach($orders as $key=>$val){
                $user = Vendor::where('id',$val->vendor_id)->first();
                $val['total_item'] = Cart::where('order_id',$val->id)->count();
                $val['product_image'] = $this->GetOneImage($val->id);
                $val['vendor_image'] = asset('public/images/users/'.$user->image);
                $val['vendor_name'] = isset($user->name) ? $user->name :'';
                $val['vendor_mobile'] = isset($user->mobile) ? $user->mobile :'';
                $val['vendor_city'] = isset($user->city) ? $user->city :'';
                $val['store_name'] = isset($user->store_name) ? $user->store_name :'';
            }
            return $this->sendSuccess('ORDER HISTORY FETCH SUCCESSFULLY', $orders);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
    }

    public function CustomerOrderDetail(CustomerOrderDetailApi $request){
        try{
            $order = Order::where('id',$request->order_id)->first();
            $carts = Cart::where('order_id',$request->order_id)->get();
            foreach($carts as $key=>$val){
                $product = $this->getProductData($val->product_id);
                $val->product_name = isset($product->name) ? $product->name :'';
                $val->product_image = isset($product->images) ? $product->images :'';
                $val->category_name = isset($product->category_name) ? $product->category_name :'';
                $val->category_image = isset($product->category_image) ? $product->category_image :'';
            }
            $vendor = Vendor::where('id',$order->vendor_id)->first();
            $vendor->image = asset('public/images/users/'.$vendor->image);
            return $this->sendSuccess('ORDER DETAIL FETCH SUCCESSFULLY', ['items' => $carts ,'detail' => $order,'vendor' => $vendor]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }
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
        $product->category_name = isset($category->title)  ? $category->title :'';
        $cat_image = isset($category->image) ? $category->image :'';
        $product->category_image = asset('public/images/categories/'.$cat_image);
        return $product;
    }
    
    public function VendorOrderHistory(Request $request){
        try{
            $orders = Order::where(['vendor_id' => $request->user()->id])->where('is_delete','0')->orderBy('id','DESC')->get();
            foreach($orders as $key=>$val){
                $user = Vendor::where('id',$val->user_id)->first();
                $val['total_item'] = Cart::where('order_id',$val->id)->count();
                $val['customer_name'] = isset($user->name) ? $user->name :'';
                $val['product_image'] = $this->GetOneImage($val->id);
                $val['order_history'] = route('order-history',$val['order_id']);
            }
            return $this->sendSuccess('ORDER HISTORY FETCH SUCCESSFULLY', $orders);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }        
    }

    function GetOneImage($id){
       $carts =  Cart::where('order_id',$id)->get();
        $image = '';
       foreach($carts as $key=>$val){
            $product = $this->getProductData($val->product_id);
            if(!empty($product->images)){
                $image = $product->images;
            }
       }

       if(!empty($image)){
            return isset($image[0]) ? $image[0] :'';
       }else{
            return '';
       }
       
    }

    public function VendorOrderDetail(CustomerOrderDetailApi $request){
        try{
            $order = Order::where('id',$request->order_id)->first();
            $carts = Cart::where('order_id',$request->order_id)->get();
            foreach($carts as $key=>$val){
                $product = $this->getProductData($val->product_id);
                $val->product_name = isset($product->name) ? $product->name :'';
                $val->product_image = isset($product->images) ? $product->images :'';
                $val->category_name = isset($product->category_name) ? $product->category_name :'';
                $val->category_image = isset($product->category_image) ? $product->category_image :'';
            }

            $vendor = Vendor::where('id',$order->vendor_id)->first();
            $order->vendor_name = $vendor->name;
            $customer = Vendor::where('id',$order->user_id)->first();
            return $this->sendSuccess('ORDER DETAIL FETCH SUCCESSFULLY', ['items' => $carts ,'detail' => $order,'customer' => $customer]);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }        
    }

    public function ChangeOrderStatus(CustomerOrderDetailApi $request){
        try{
            if(!empty($request->status)){
                Order::where('id',$request->order_id)->update(['status' => $request->status]);
                return $this->sendSuccess('ORDER STATUS CHANGE SUCCESSFULLY');
            }else{
                return $this->sendFailed('ORDER STATUS MUST BE VALID',200);
            }
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }        
    }

    public function DeleteByDate(DeleteOrderApi $request){
        try{
            if(!empty($request->order_id)){
                Order::where('id',$request->order_id)->update(['is_delete' => '1']);
                return $this->sendSuccess('ORDER DELETED SUCCESSFULLY');
            }
            if(!empty($request->from_date) && !empty($request->to_date)){
                $from_date = date('Y-m-d',strtotime($request->from_date));
                $to_date = date('Y-m-d',strtotime($request->to_date));
                Order::whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->update(['is_delete' => '1']);
                return $this->sendSuccess('ORDER DELETED SUCCESSFULLY');
            }
            return $this->sendFailed('PLEASE ENTER VALID INPUT ',200);
        }catch(\Throwable $e){
            return $this->sendFailed($e->getMessage(). ' On Line '. $e->getLine(),200);
        }        
    }



}
