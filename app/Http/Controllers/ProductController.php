<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Role;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
            $val['image'] = isset($images[0]) ? $images[0] :'download.png';
            $category = Category::where('id',$val->category_id)->first();
            $val['image'] = isset($images[0]) ? $images[0] :'download.png';
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
        $categories = Category::get();
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
        $categories = Category::get();
        $product->images = json_decode($product->images);
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
        $data = $request->validated();
        $data['detail'] = $request->detail;
        $data['unit'] = $request->unit;
        $data['user_id'] = Auth::user()->id;

        if(!empty($request->image)){    
            $image = json_decode($product->images);
            foreach($request->image as $key=>$val){ 
                $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $val->move(public_path('images/products'), $image_name);
                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
            $data['images'] = isset($dd_image) ? $dd_image : '';
        }

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
        $categories = Category::get();
        $product->images = json_decode($product->images);
        return view('admin.products.edit_2',compact('product','categories'));
    }

    public function update_2(Request $request){
        $product = Product::where('id',$request->id)->first();
        $data = [];
        $data['name'] = $request->name;
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

        if(!empty($request->image)){    
            $image = json_decode($product->images);
            foreach($request->image as $key=>$val){ 
                $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $val->move(public_path('images/products'), $image_name);
                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
            $data['images'] = isset($dd_image) ? $dd_image : '';
        }

        Product::where('id',$request->id)->update($data);
        return redirect()->route('vendors.account.products',$product->user_id)->with('success','Product Update Success');


    }
}
