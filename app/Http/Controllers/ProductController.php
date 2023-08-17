<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Vendor;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_admin','1')->with('category')->orderBy('id','desc')->get();
        foreach($products as $key=>$val){
            $images = json_decode($val->images);
            $val['image'] = isset($images[0]) ? $images[0] :'no_image.png';
            $category = Category::where('id',$val->category_id)->first();
            $val['category_name'] = isset($category->title) ? $category->title :'';
        }
        return view('admin.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('is_admin','1')->where('is_delete','!=','1')->get();
        return view('admin.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['detail'] = $request->detail;
        $data['user_id'] = Auth::user()->id;
        $data['is_admin'] = '1';
        
        if(!empty($request->image)){    
            $image = [];
            foreach($request->image as $key=>$val){ 
                $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $val->move(public_path('images/products'), $image_name);
                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
        }
        $data['images'] = isset($dd_image) ? $dd_image : '';
        Product::create($data);
        return redirect()->route('products.index')->with('success','Product Create Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {   
        $categories = Category::where('is_admin','1')->where('is_delete','!=','1')->get();
        $images = json_decode($product->images);
        $product['images'] = $images;
        $product['image_1'] = isset($images[0]) ? $images[0] :'no_image.png';
        $product['image_2'] = isset($images[1]) ? $images[1] :'no_image.png';
        $product['image_3'] = isset($images[2]) ? $images[2] :'no_image.png';
        $product['image_4'] = isset($images[3]) ? $images[3] :'no_image.png';
        return view('admin.products.edit',compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {   
        $check = Product::where('id','!=',$product->id)->where('name',$request->name)->where('is_admin','1')->count();
        if($check > 0){
            return back()->withErrors([
                'email' => 'The name has already been taken.',
            ])->onlyInput('email');
        }
        
        $data = $request->validated();
        $data['name'] = Helper::createUpperString($request->name);
        $data['detail'] = $request->detail;
        $data['unit'] = $request->unit;
        $data['user_id'] = Auth::user()->id;

        if(!empty($product->images)){
            $images = json_decode($product->images);
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

        $data['images'] = json_encode($images);

        $product->update($data);
        return redirect()->route('products.index')->with('success','Update Product Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success','Delete Product Success');
    }

    public function delete_product_image (Request $request){
        $product = Product::where('id',$request->product_id)->first();
        $product->images = json_decode($product->images);
        $dd_product = [];
        if(!empty($product->images)){
            foreach($product->images as $key){
                if($key != $request->image){
                    $dd_product[] = $key;
                }
            }
            Product::where('id',$request->product_id)->update(['images'=> json_encode($dd_product)]);
        }
        return redirect()->back()->with('success','Image Delete Success');
    }

    public function edit_2($id){
        $product = Product::where('id',$id)->first();
        $categories = Category::where('user_id',$product->user_id)->get();
        $images = json_decode($product->images);
        $product['images'] = $images;
        $product['image_1'] = isset($images[0]) ? $images[0] :'no_image.png';
        $product['image_2'] = isset($images[1]) ? $images[1] :'no_image.png';
        $product['image_3'] = isset($images[2]) ? $images[2] :'no_image.png';
        $product['image_4'] = isset($images[3]) ? $images[3] :'no_image.png';
        $vendor = Vendor::where('id',$product->user_id)->first();
        return view('admin.products.edit_2',compact('product','categories','vendor'));
    }

    public function update_2(Request $request){
        $product = Product::where('id',$request->id)->first();

        $check = Product::where('id','!=',$product->id)->where('name',$request->name)->where('user_id',$product->user_id)->count();
        if($check > 0){
            return back()->withErrors([
                'email' => 'The name has already been taken.',
            ])->onlyInput('email');
        }

        $data = [];
        $data['name'] = Helper::createUpperString($request->name);
        $data['category_id'] = $request->category_id;
        $data['status'] = $request->status;
        $data['mrp'] = $request->mrp;
        $data['sp'] = $request->sp;
        $data['stock'] = $request->stock;
        $data['quantity'] = $request->quantity;
        $data['order_limit'] = $request->order_limit;
        $data['packing_quantity'] = $request->packing_quantity;
        $data['unit'] = $request->unit;
        $data['detail'] = $request->detail;

        if(!empty($product->images)){
            $images = json_decode($product->images);
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

        $data['images'] = json_encode($images);
        Product::where('id',$request->id)->update($data);
        return redirect()->route('vendors.account.products',$product->user_id)->with('success','Product Update Success');


    }
}
