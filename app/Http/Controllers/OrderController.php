<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;


class OrderController extends Controller
{
    public function index(){
        $orders = Order::orderBy('id','DESC')->get();
        
        foreach($orders as $key=>$val){
            $val->user_name = isset($this->getUserDetail($val->user_id)->name) ? $this->getUserDetail($val->user_id)->name :'';
            $val->vendor_name = isset($this->getUserDetail($val->vendor_id)->name) ? $this->getUserDetail($val->vendor_id)->name :'';
            $val->total_item = Cart::where('order_id',$val->id)->count();
        }
        return view('admin.orders.index',compact('orders'));
    }

    public function order_invoice ($id){
        $order = Order::where('order_id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->first();
        $vendor = User::where('id',$order->vendor_id)->first();
        $carts = Cart::where('order_id',$order->id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            $image = json_decode($product->images);
            $val->image = isset($image[0]) ? $image[0] :'download.png';
        }
        return view('admin.orders.invoice',compact('carts','vendor','customer','order'));
    }

    public function show ($id){
        $order = Order::where('id',$id)->first();
        $order->sum_mrp = Cart::where('order_id',$id)->sum('p_mrp');
        $order->sum_quantity = Cart::where('order_id',$id)->sum('quantity');
        $order->sum_price = Cart::where('order_id',$id)->sum('p_price');
        $order->in_word = $this->numberToWord($order->amount);
        $customer = User::where('id',$order->user_id)->first();
        $vendor = User::where('id',$order->vendor_id)->first();
        $carts = Cart::where('order_id',$id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            $image = json_decode($product->images);
            $val->image = isset($image[0]) ? $image[0] :'download.png';
        }

        // $word = $this->numberToWord(120);
        return view('admin.orders.show',compact('carts','vendor','customer','order'));
    }

    function getUserDetail($id){
        return User::where('id',$id)->first();
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
        $customer = User::where('id',$order->user_id)->first();
        $vendor = User::where('id',$order->vendor_id)->first();
        $carts = Cart::where('order_id',$order->id)->get();
        foreach($carts as $key=>$val){
            $product = Product::where('id',$val->product_id)->first();
            $val->product_name = isset($product->name) ? $product->name :'';
            $image = json_decode($product->images);
            $val->image = isset($image[0]) ? $image[0] :'download.png';
        }
        return view('admin.orders.order-history',compact('carts','vendor','customer','order'));
    }

    public function changeOrderStatus($id,$status){
        $order = Order::where('id',$id)->first();

        if($status == 'accept' && $order->status == 'pending'){
            $order->update(['status' => 'accepted']);
            return redirect()->back()->with('success','Order Accepted Successfully');
        }

        if($status == 'reject' && $order->status == 'pending'){
            $order->update(['status' => 'rejected']);
            return redirect()->back()->with('success','Order Rejected Successfully');
        }

        if($status == 'dispach' && $order->status == 'accepted'){
            $order->update(['status' => 'dispatched']);
            return redirect()->back()->with('success','Order Dispatched Successfully');
        }

        if($status == 'deliver' && $order->status == 'dispatched'){
            $order->update(['status' => 'delivered']);
            return redirect()->back()->with('success','Order Delivered Successfully');
        }

        
    }
        
}
