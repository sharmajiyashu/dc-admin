<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Models\StoreLink;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\Customer;
use App\Models\WishCart;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Support\Facades\Redis;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::where('role_id',Role::$vendor)->where('is_register','1')->get();
        return view('admin.vendor.index',compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVendorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return view('admin.vendor.account.account',compact('vendor'));
        // echo $vendor;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVendorRequest  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }

    public function vendors_products($id){
        $vendor = Vendor::where('id',$id)->first();
        $products = Product::where('user_id',$id)->orderBy('id','desc')->get();
        foreach($products as $key=>$val){
            $val['images'] = json_decode($val->images);
            $category = Category::where('id',$val->category_id)->first();
            $val['category_name'] = isset($category->title) ? $category->title :'';
        }
        return view('admin.vendor.account.products',compact('products','vendor'));
    }

    public function vendors_customers ($id){
        $vendor = Vendor::where('id',$id)->first();
        $customers = StoreLink::where('vendor_id',$id)->orderBy('id','DESC')->get();
        foreach($customers as $key=>$val){
            $customer = Vendor::where('id',$val->user_id)->first();
            $val['status'] = $this->GetLinkStatus($val->status);
            $val['customer_name'] = isset($customer->name) ? $customer->name :'';
            $val['customer_mobile'] = isset($customer->mobile) ? $customer->mobile :'';
            $val['customer_city'] = isset($customer->city) ? $customer->city :'';
            $val['customer_city'] = isset($customer->city) ? $customer->city :'';
            $val['customer_state'] = isset($customer->state) ? $customer->state :'';
        }
        return view('admin.vendor.account.customers',compact('vendor','customers'));
    }

    function GetLinkStatus($status){
        if($status == 0){
            return 'Pending';
        }
        if($status == 1){
            return 'Active';
        }
        if($status == 2){
            return 'Inactive';
        }
    }

    public function vendors_orders($id){
        $vendor = Vendor::where('id',$id)->first();
        $orders = Order::where('vendor_id',$id)->orderBy('id','DESC')->get();

        foreach($orders as $key=>$val){
            $val->user_name = isset($this->getUserDetail($val->user_id)->name) ? $this->getUserDetail($val->user_id)->name :'';
            $val->vendor_name = isset($this->getUserDetail($val->vendor_id)->name) ? $this->getUserDetail($val->vendor_id)->name :'';
            $val->total_item = Cart::where('order_id',$val->id)->count();
        }
        return view('admin.vendor.account.orders',compact('orders','vendor'));
    }

    function getUserDetail($id){
        return User::where('id',$id)->first();
    }

    public function vendors_customers_detele(Request $request){

        StoreLink::where('id',$request->id)->delete();
        return redirect()->back()->with('success','Customers Delete Success');
    }

    public function add_customers(Request $request){
        $validated = $request->validate([
            'mobile' => 'required|digits:10',
            'vendor_id' => 'required',
        ]);

        $customer = Customer::where(['mobile'=>$request->mobile ,'role_id' => Role::$customer])->first();
        $vendor = Vendor::where('id',$request->vendor_id)->first();
        if(!empty($customer)){
            $StoreLink = StoreLink::where(['user_id'=> $customer->id ,'vendor_id' => $request->vendor_id])->count();
            if($StoreLink > 0){
                return redirect()->back()->with('error','Customer Is Already Exist');
            }else{
                StoreLink::create(['vendor_id' => $request->vendor_id,'user_id' => $customer->id,'store_code' => $vendor->store_code ,'status' => '1']);
                return redirect()->back()->with('success','Customer Add Successfully');
            }
        }else{
            $customer = Customer::create(['mobile'=>$request->mobile ,'role_id' => Role::$customer ,'active_store_code' => $vendor->store_code]);
            StoreLink::create(['vendor_id' => $request->vendor_id,'user_id' => $customer->id,'store_code' => $vendor->store_code ,'status' => '1']);
            return redirect()->back()->with('success','New Customer Add Successfully');
        }
        
    }

    public function customers_change_status(Request $request){
        $validated = $request->validate([
            'status' => 'required|in:0,1,2',
            'id' => 'required',
        ]);

        StoreLink::where('id',$request->id)->update(['status' => $request->status]);
        return  redirect()->back()->with('success','Status Change Successfully');
    }

    public function customers_add_stores(Request $request){
        $validated = $request->validate([
            'mobile' => 'nullable|numeric|digits:10|exists:users,mobile',
            'customer_id' => 'required|exists:users,id',
            'store_code' => 'nullable|exists:users,store_code'
        ]);

        if(empty($request->store_code) && empty($request->mobile)){
            return redirect()->back()->with('error','Enter Any Store code Or Vendor Mobile');
        }

        $vendor = Vendor::where('mobile',$request->mobile)->orWhere('store_code',$request->store_code)->where('role_id',Role::$vendor)->first();
        

        $StoreLink = StoreLink::where(['user_id'=> $request->customer_id ,'vendor_id' => $vendor->id])->count();
        if($StoreLink > 0){
            return redirect()->back()->with('error','Store Is Already Linked');
        }else{
            StoreLink::create(['vendor_id' => $vendor->id,'user_id' => $request->customer_id,'store_code' => $vendor->store_code ,'status' => '1']);
            return redirect()->back()->with('success','Customer Add Successfully');
        }
    }

    public function wishlist($id){
        $vendor = $this->getUserDetail($id);
        $wish_items = WishCart::where('vendor_id',$id)->get();
        foreach($wish_items as $key=>$val){
            $val['customer_name'] = isset($this->getUserDetail($val->user_id)->name) ? $this->getUserDetail($val->user_id)->name :'';
            $val['product_name'] = isset($this->getProductDetail($val->product_id)->name) ? $this->getProductDetail($val->product_id)->name :'';
            $image = json_decode($this->getProductDetail($val->product_id)->images);
            $val['product_image'] = isset($image[0]) ? $image[0] :'download.png';
        }
        return view('admin.vendor.account.wishlist',compact('vendor','wish_items'));
    }

    function getProductDetail($id){
        return Product::where('id',$id)->first();
    }


}
