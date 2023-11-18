<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\DemoProduct;
use App\Http\Requests\StoreDemoProductRequest;
use App\Http\Requests\UpdateDemoProductRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class DemoProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = request('keyword');
        $get = [];
        $page = isset($request->page) ? $request->page : 1;
        $products = DemoProduct::with('category')->orderByRaw('LENGTH(name) ASC, name ASC');
        
        if(!empty($request->category_id)){
            $products->where('category_id',$request->category_id);
            $get['category_id'] = $request->category_id;
        }elseif(!empty($keyword['category_id'])){
            $category_id = $keyword['category_id'];
            $products->where('category_id',$category_id);
            $get['category_id'] = $category_id;
        }

        if(!empty($request->product_name)){
            $products = $products->where('name', 'LIKE', '%' . $request->product_name . '%');
            $get['product_name'] = $request->product_name;
        }elseif(!empty($keyword['product_name'])){
            $product_name = $keyword['product_name'];
            $products = $products->where('name', 'LIKE', '%' . $product_name . '%');
            $get['product_name'] = $product_name;
        }

        $get['page_number'] = $page;
        $product_count = $products->count();
        $products = $products->paginate(50, ['*'], 'page', $page);
        
        $categories = Category::where('status',1)->where('is_admin',1)->orderBy('title','asc')->get();

        foreach($products as $key=>$val){
            $images = json_decode($val->images);
            $val['image'] = isset($images[0]) ? $images[0] :'no_image.png';
            $category = Category::where('id',$val->category_id)->first();
            $val['category_name'] = isset($category->title) ? $category->title :'';
        }

        $products_2 = $products;
        return view('admin.demo_products.index',compact('products','products_2','categories','get','product_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('is_admin','1')->where('status',Category::$active)->get();
        return view('admin.demo_products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDemoProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDemoProductRequest $request)
    {
        $data = $request->validated();
        $data['detail'] = $request->detail;

        if(!empty($request->image)){    
            $image = [];
            foreach($request->image as $key=>$val){ 
                $image_name = time().rand(1,100).'-'.$val->getClientOriginalName();
                $image_name = preg_replace('/\s+/', '', $image_name);
                $val->move(public_path('images/products'), $image_name);

                $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);

                $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);

                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
        }
        $data['images'] = isset($dd_image) ? $dd_image : '';
        DemoProduct::create($data);
        return redirect()->route('demo_products.create')->with('success','Product Create Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DemoProduct  $demoProduct
     * @return \Illuminate\Http\Response
     */
    public function show(DemoProduct $demoProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DemoProduct  $demoProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(DemoProduct $demoProduct ,Request $request)
    {
        // echo $demoProduct;die;
        $page_data = isset($request->data) ? $request->data :'';
        $categories = Category::where('is_admin','1')->where('status',Category::$active)->get();
        $images = json_decode($demoProduct->images);
        $demoProduct['images'] = $images;
        $demoProduct['image_1'] = isset($images[0]) ? $images[0] :'no_image.png';
        $demoProduct['image_2'] = isset($images[1]) ? $images[1] :'no_image.png';
        $demoProduct['image_3'] = isset($images[2]) ? $images[2] :'no_image.png';
        $demoProduct['image_4'] = isset($images[3]) ? $images[3] :'no_image.png';
        return view('admin.demo_products.edit',compact('demoProduct','categories','page_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDemoProductRequest  $request
     * @param  \App\Models\DemoProduct  $demoProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDemoProductRequest $request, DemoProduct $demoProduct)
    {
        $check = DemoProduct::where('id','!=',$demoProduct->id)->where('name',$request->name)->count();
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

        if(!empty($demoProduct->images)){
            $images = json_decode($demoProduct->images);
        }else{
            $images = [];    
        }

        if($request->hasFile('image_1')) {
            $image_name = time().rand(1,100).'-'.$request->image_1->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image_1->move(public_path('images/products'), $image_name);

            $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
            $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
            $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
            $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);

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
            $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
            $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
            $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
            $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
            $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
            $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
            $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
            $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
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
            $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
            $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
            $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
            $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
            if(isset($images[3])){
                $images[3] = $image_name;
            }else{
                $images[] = $image_name;
            }
        }

        $data['images'] = json_encode($images);

        $demoProduct->update($data);
        $page_data = json_decode($request->page_data);
        $get_data = [
            'page' => isset($page_data->page_number) ? $page_data->page_number :'',
            'category_id' => isset($page_data->category_id) ? $page_data->category_id :'',
            'product_name' => isset($page_data->product_name) ? $page_data->product_name :'',
        ];
        return redirect()->route('demo_products.index',$get_data)->with('success','Update Product Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DemoProduct  $demoProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(DemoProduct $demoProduct)
    {
        $demoProduct->delete();
        return redirect()->back()->with('success','Product delete successfully');
    }
}
