<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\WishCart;

class OrderController extends Controller
{
    public function index(Request $request){
        $keyword = request('keyword');
        $page = isset($request->page) ? $request->page : 1;
        $get = [];
        $orders = Order::orderBy('id','DESC');


        if(!empty($request->customer_id)){
            $products = $orders->where('user_id',$request->customer_id);
            $get['customer_id'] = $request->customer_id;
        }elseif(!empty($keyword['customer_id'])){
            $customer_id = $keyword['customer_id'];
            $products = $orders->where('user_id',$customer_id);
            $get['customer_id'] = $customer_id;
        }

        if(!empty($request->order_id)){
            $products = $orders->where('order_id', 'LIKE', '%' . $request->order_id . '%');
            $get['order_id'] = $request->order_id;
        }elseif(!empty($keyword['order_id'])){
            $order_id = $keyword['order_id'];
            $products = $orders->where('order_id', 'LIKE', '%' . $order_id . '%');
            $get['order_id'] = $order_id;
        }

        if(!empty($request->vendor_id)){
            $products = $orders->where('vendor_id',$request->vendor_id);
            $get['vendor_id'] = $request->vendor_id;
        }elseif(!empty($keyword['vendor_id'])){
            $vendor_id = $keyword['vendor_id'];
            $products = $orders->where('vendor_id',$vendor_id);
            $get['vendor_id'] = $vendor_id;
        }
        
        $orders = $orders->paginate(10, ['*'], 'page', $page);

        
        foreach($orders as $key=>$val){
            $val->user_name = isset($this->getUserDetail($val->user_id)->store_name) ? $this->getUserDetail($val->user_id)->store_name :'';
            $val->vendor_name = isset($this->getUserDetail($val->vendor_id)->store_name) ? $this->getUserDetail($val->vendor_id)->store_name :'';
            $val->total_item = Cart::where('order_id',$val->id)->count();
        }
        

        $buyer = Customer::where('role_id',Role::$customer)->where('is_register','1')->get();
        $seller = Customer::where('role_id',Role::$vendor)->where('is_register','1')->get();
        return view('admin.orders.index',compact('orders','get','buyer','seller'));
    }

    public function order_invoice ($id){
        $order = Order::where('order_id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$order->id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$order->id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$order->id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->withTrashed()->first();
        $vendor = User::where('id',$order->vendor_id)->withTrashed()->first();
        $carts = Cart::where('order_id',$order->id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->withTrashed()->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            if($product->images){
                $image = json_decode($product->images);
            }
            $val->image = isset($image[0]) ? $image[0] :'no_image.png';
            $wish_stock = WishCart::where(['order_id' => $order->id ,'product_id' => $val->product_id])->first();
            $val['out_of_stock'] = 0;
            if(!empty($wish_stock)){
                $val['out_of_stock'] = $wish_stock->quantity;
            }
        }
        return view('admin.orders.invoice',compact('carts','vendor','customer','order'));
    }

    public function show ($id){
        $order = Order::where('id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->withTrashed()->first();
        $vendor = User::where('id',$order->vendor_id)->withTrashed()->first();
        $carts = Cart::where('order_id',$id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->withTrashed()->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            if(!empty($product->images)){
                $image = json_decode($product->images);
            }
            $val->image = isset($image[0]) ? $image[0] :'no_image.png';
            $wish_stock = WishCart::where(['order_id' => $order->id ,'product_id' => $val->product_id])->first();
            $val['out_of_stock'] = 0;
            if(!empty($wish_stock)){
                
                
                $val['out_of_stock'] = $wish_stock->quantity;
            }
        }

        // $word = $this->numberToWord(120);
        return view('admin.orders.show',compact('carts','vendor','customer','order'));
    }

    function getUserDetail($id){
        return User::where('id',$id)->withTrashed()->first();
    }

    public function numberToWord($num = '')
    {
        $num    = ( string ) ( ( int ) $num );
        
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
             
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
             
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
             
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
             
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                 
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                $words  = str_replace( ',' , ' and' , $words );
            }
             
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    public function OrderHistory($id){
        $order = Order::where('order_id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$order->id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$order->id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$order->id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->withTrashed()->first();
        $vendor = User::where('id',$order->vendor_id)->withTrashed()->first();
        $carts = Cart::where('order_id',$order->id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->withTrashed()->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            if($product->images){
                $image = json_decode($product->images);
            }
            $val->image = isset($image[0]) ? $image[0] :'no_image.png';
            $wish_stock = WishCart::where(['order_id' => $order->id ,'product_id' => $val->product_id])->first();
            $val['out_of_stock'] = 0;
            if(!empty($wish_stock)){
                $val['out_of_stock'] = $wish_stock->quantity;
            }
        }
        return view('admin.orders.order-history',compact('carts','vendor','customer','order'));
    }

    public function changeOrderStatus($id,$status){
        $order = Order::where('id',$id)->first();
        $customer = User::find($order->user_id);
        if($customer){
            if($status == 'accept' && $order->status == 'pending'){
                $order->update(['status' => 'accepted']);
                Helper::sentOrderChange($order->id,'accepted');
                return redirect()->back()->with('success','Order Accepted Successfully');
            }
    
            if($status == 'reject' && $order->status == 'pending'){
                $order->update(['status' => 'rejected']);
                Helper::sentOrderChange($order->id,'rejected');
                return redirect()->back()->with('success','Order Rejected Successfully');
            }
    
            if($status == 'dispach' && $order->status == 'accepted'){
                $order->update(['status' => 'dispatched']);
                Helper::sentOrderChange($order->id,'dispatched');
                return redirect()->back()->with('success','Order Dispatched Successfully');
            }
    
            if($status == 'deliver' && $order->status == 'dispatched'){
                $order->update(['status' => 'delivered']);
                Helper::sentOrderChange($order->id,'delivered');
                return redirect()->back()->with('success','Order Delivered Successfully');
            }
        }else{
            return redirect()->back()->with('error','The customer account has been deleted, so it is not available to update the order status!');
        }
        

        
    }

    public function OrderInvoice($id){
        $order = Order::where('order_id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$order->id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$order->id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$order->id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->withTrashed()->first();
        $vendor = User::where('id',$order->vendor_id)->withTrashed()->first();
        $carts = Cart::where('order_id',$order->id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->withTrashed()->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            $image = json_decode($product->images);
            $val->image = isset($image[0]) ? $image[0] :'no_image.png';
            $wish_stock = WishCart::where(['order_id' => $order->id ,'product_id' => $val->product_id])->first();
            $val['out_of_stock'] = 0;
            if(!empty($wish_stock)){
                $val['out_of_stock'] = $wish_stock->quantity;
            }
        }
        return view('admin.orders.invoice-show',compact('carts','vendor','customer','order'));
    }
        
}
