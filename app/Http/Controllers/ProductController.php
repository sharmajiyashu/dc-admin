<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
        // $products = Product::join('categories','categories.id','=','products.category_id')->select('products.*','categories.title as category_name')->get();
        $products = Product::with('category')->orderBy('id','desc')->get();
        // dd($products[0]->category->title);
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
        if(!empty($request->image)){    
            $image = [];
            foreach($request->image as $key=>$val){ 
                $image_name = $val->getClientOriginalName().'.'.time().rand(1,100);
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
        $data['images'] = $request->unit;
        $data['user_id'] = Auth::user()->id;

        if(!empty($request->image)){    
            $image = json_decode($product->images);
            foreach($request->image as $key=>$val){ 
                $image_name = $val->getClientOriginalName().'.'.time().rand(1,100);
                $val->move(public_path('images/products'), $image_name);
                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
        }
        $data['images'] = isset($dd_image) ? $dd_image : '';


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
}
