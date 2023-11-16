<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Role;
use App\Models\StoreLink;
use App\Models\Cart;
use App\Models\Order;
use App\Models\WishCart;
use App\Models\Product;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Notification;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::where('role_id',Role::$customer)->where('is_register','1')->get();
        foreach($customers as $key => $val){
            if(empty($val->image)){
                $val['image'] = 'no_image.png';
            }
        }
        return view('admin.customers.index',compact('customers'));
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
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $store = StoreLink::where('user_id',$customer->id)->where('status',StoreLink::$active)->get();
        return view('admin.customers.account.account',compact('customer','store'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $data['name'] = $request->name;
        $data['gender'] = $request->gender;
        $data['dob'] = $request->dob;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['address'] = $request->address;
        $data['pin'] = $request->pin;
        $data['store_name'] = $request->store_name;

        $data['active_store_code'] = isset($request->active_store_code) ? $request->active_store_code :'';
        
        if($request->hasFile('image')) {
            $image       = $request->file('image');
            $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $image->move(public_path('images/users'), $image_name); 
            $data['image'] = $image_name;
        }
        $customer->update($data);
        if($customer->role_id == 2){
            $user = "Vendor";
        }elseif($customer->role_id == 3){
            $user = "Customer";
        }else{
            $user = "Admin";
        }
        return redirect()->back()->with('success',$user.' Update Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function stores($id){
        $customer = Customer::where('id',$id)->first();
        $stores = StoreLink::where('user_id',$id)->orderBy('id','DESC')->get();
        foreach($stores as $key=>$val){
            $customer_data = Customer::where('id',$val->vendor_id)->first();
            $val['status'] = $this->GetLinkStatus($val->status);
            $val['vendor_name'] = isset($customer_data->store_name) ? $customer_data->store_name :'';
            $val['store_code'] = isset($customer_data->store_code) ? $customer_data->store_code :'';
            $val['vendor_mobile'] = isset($customer_data->mobile) ? $customer_data->mobile :'';
            $val['vendor_city'] = isset($customer_data->city) ? $customer_data->city :'';
            $val['vendor_city'] = isset($customer_data->city) ? $customer_data->city :'';
            $val['vendor_state'] = isset($customer_data->state) ? $customer_data->state :'';
        }
        return view('admin.customers.account.customers',compact('stores','customer'));
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

    public function orders($id){
        $customer = Customer::where('id',$id)->first();
        $orders = Order::where('user_id',$id)->orderBy('id','DESC')->get();

        foreach($orders as $key=>$val){
            $val->user_name = isset($this->getUserDetail($val->user_id)->name) ? $this->getUserDetail($val->user_id)->name :'';
            $val->vendor_name = isset($this->getUserDetail($val->vendor_id)->name) ? $this->getUserDetail($val->vendor_id)->name :'';
            $val->total_item = Cart::where('order_id',$val->id)->count();
        }
        return view('admin.customers.account.orders',compact('orders','customer'));
    }

    function getUserDetail($id){
        return Customer::where('id',$id)->first();
    }

    public function wishlist($id){
        $customer = $this->getUserDetail($id);
        $wish_items = WishCart::where('user_id',$id)->get();
        foreach($wish_items as $key=>$val){
            $val['vendor_name'] = isset($this->getUserDetail($val->vendor_id)->name) ? $this->getUserDetail($val->vendor_id)->name :'';
            $val['product_name'] = isset($this->getProductDetail($val->product_id)->name) ? $this->getProductDetail($val->product_id)->name :'';
            $image = json_decode($this->getProductDetail($val->product_id)->images);
            $val['product_image'] = isset($image[0]) ? $image[0] :'no_image.png';
        }
        return view('admin.customers.account.wishlist',compact('customer','wish_items'));
    }

    function getProductDetail($id){
        return Product::where('id',$id)->first();
    }

    public function carts($id){
        $customer = $this->getUserDetail($id);
        $cart_items = Cart::where('user_id',$id)->where('status','0')->get();
        foreach($cart_items as $key=>$val){
            $val['vendor_name'] = isset($this->getUserDetail($val->vendor_id)->name) ? $this->getUserDetail($val->vendor_id)->name :'';
            $val['product_name'] = isset($this->getProductDetail($val->product_id)->name) ? $this->getProductDetail($val->product_id)->name :'';
            $image = json_decode($this->getProductDetail($val->product_id)->images);
            $val['product_image'] = isset($image[0]) ? $image[0] :'no_image.png';
        }
        return view('admin.customers.account.cart',compact('customer','cart_items'));
    }

    public function notifications($id){
        $customer = $this->getUserDetail($id);
        $notifications = Notification::where('user_id',$customer->id)->orderBy('id','desc')->get();
        return view('admin.customers.account.notifications',compact('customer','notifications'));
    }

}
