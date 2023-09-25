<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $categories = Category::orderBy('title','asc')->where('is_admin','1');
        $total_category = $categories->count();
        $categories = $categories->get();
        foreach($categories as $key=>$val){
            $val['total_products'] = Product::where('category_id',$val->id)->count();
            if(empty($val->image)){
                $val['image'] = 'no_image.png';
            }
        }
        return view('admin.categories.index',compact('categories','total_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $check = Category::where('title',Helper::createUpperString($request->title))->count();
        if($request->hasFile('image')) {
            $image       = $request->file('image');
            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/categories'), $image_name);
        }
        $data = $request->validated();
        $data['image'] = isset($image_name) ? $image_name : '';
        $data['title'] = Helper::createUpperString($request->title);
        $data['user_id'] = Auth::user()->id;
        $data['is_admin'] = '1';
        $category = Category::create($data);

        $vendor = Vendor::where('role_id',Role::$vendor)->where('is_register','1')->get();
        foreach($vendor as $key=>$val){
            $data_2 = $request->validated();
            $data_2['title'] = Helper::createUpperString($request->title);
            $data_2['image'] = isset($image_name) ? $image_name : '';
            $data_2['user_id'] = $val->id;
            $data_2['admin_id'] = $category->id;
            Category::create($data_2);
        }
        return redirect()->route('categories.index')->with('success','Category Create Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category,Request $request)
    {
        $products = Product::where('is_admin','1')->with('category')->orderBy('id','desc')->where('category_id',$category->id);
        
        $page = isset($request->page) ? $request->page : 1;
        $keyword = request('keyword');
        $get = [];
        if(!empty($request->product_name)){
            $products = $products->where('name', 'LIKE', '%' . $request->product_name . '%');
            $get['product_name'] = $request->product_name;
        }elseif(!empty($keyword['product_name'])){
            $product_name = $keyword['product_name'];
            $products = $products->where('name', 'LIKE', '%' . $product_name . '%');
            $get['product_name'] = $product_name;
        }
        $total_products = $products->count();
        $products = $products->paginate(50, ['*'], 'page', $page);
        $products_2 = $products;
        foreach($products as $key=>$val){
            $images = json_decode($val->images);
            $val['image'] = isset($images[0]) ? $images[0] :'no_image.png';
            $category = Category::where('id',$val->category_id)->first();
            $val['category_name'] = isset($category->title) ? $category->title :'';
        }
        return view('admin.products.category_products',compact('products','category','total_products','products_2','get'));   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $check = Category::where('id','!=',$category->id)->where('title',$request->title)->where('is_admin','1')->count();
        if($check > 0){
            return back()->withErrors([
                'email' => 'The name has already been taken.',
            ])->onlyInput('email');
        }

        $data = $request->validated();
        $data['title'] = Helper::createUpperString($request->title);

        $data_2 = [
            'title' => Helper::createUpperString($request->title),
            'status' => $request->status,
        ];
        if($request->hasFile('image')) {

            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/categories'), $image_name);
            $data['image'] = $image_name;

            $data_2['image'] = $image_name;
        }
        
        $category->update($data);
        Category::where('admin_id',$category->id)->update($data_2);
        return redirect()->route('categories.index')->with('success','Category Update Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->update(['status' => Category::$inactive]);
        $category->delete();
        $categroy_id = $category->id;
        Category::where('admin_id',$categroy_id)->update(['status' => Category::$inactive]);
        Category::where('admin_id',$categroy_id)->delete();
        return redirect()->route('categories.index')->with('success','Category Delete Success');
    }
}
