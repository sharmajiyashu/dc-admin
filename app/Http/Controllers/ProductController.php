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
use Illuminate\Support\Facades\Redis;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
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
        $products = Product::where('is_admin','1')->with('category')->orderBy('id','desc');
        
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
        return view('admin.products.index',compact('products','products_2','categories','get','product_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('is_admin','1')->where('status',Category::$active)->get();
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

                $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);

                $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);

                $image[] = $image_name;
            }
            $dd_image = json_encode($image);
        }
        $data['images'] = isset($dd_image) ? $dd_image : '';
        Product::create($data);
        return redirect()->route('products.create')->with('success','Product Create Success');
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
        $categories = Category::where('is_admin','1')->where('status',Category::$active)->get();
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
            $path_1 = public_path('images/products/'.$request->image);
            $path_2 = public_path('images/products/thumb1/'.$request->image);
            $path_3 = public_path('images/products/thumb2/'.$request->image);
            if(File::exists($path_1)) {
                File::delete($path_1);
            }
            if(File::exists($path_2)) {
                File::delete($path_2);
            }
            if(File::exists($path_3)) {
                File::delete($path_3);
            }
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
        Product::where('id',$request->id)->update($data);
        return redirect()->route('vendors.account.products',$product->user_id)->with('success','Product Update Success');


    }


    public function addBulkProduct(Request $request){
        $validated = $request->validate([
            'csv_file' => 'required|mimes:csv',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filename = 'subscriptions'.time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $total = $this->insertBulkData3($filename);

            return redirect()->back()->with('success',$total.' Product Import SuccessFully');
        }

        return redirect()->back()->with('error',' Subscriber Import Failour');
    }

    private function insertBulkData($filename)
    {
        $file = public_path('uploads/' . $filename);
        $csvData = array_map('str_getcsv', file($file));
        $csvHeader = $csvData[0]; // Assuming the first row contains header names

        $total_subscriber_insert = 0;

        foreach ($csvData as $key => $row) {
            if ($key === 0) continue; // Skip the header row
            $length = count($row);
            if($length == 2){
                $product_name = Helper::createUpperString($row[0]);
                $category_name = Helper::createUpperString($row[1]);
                $check_product = Product::where('is_admin','1')->where('name',$product_name)->count();
                if($check_product < 1){
                    $check_category = Category::where(['is_admin' => '1' ,'title' => $category_name])->first();
                    if(!empty($check_category)){
                        $category_id = $check_category->id;
                    }else{
                        $category = Category::create(['title' => $category_name ,'is_admin' => '1' ,'user_id' => Auth::user()->id ]);
                        $category_id = $category->id;
                    }
                    Product::create(['name' => $product_name ,'category_id' => $category_id ,'user_id' => Auth::user()->id ,'sp' => 0 ,'is_admin' => '1']);
                    $total_subscriber_insert ++;
                }
                
            }
        }

        return $total_subscriber_insert;
    }

    private function insertBulkData2($filename) // upload dc csv products
    {
        $file = public_path('uploads/' . $filename);
        $csvData = array_map('str_getcsv', file($file));
        $csvHeader = $csvData[0]; // Assuming the first row contains header names

        $total_subscriber_insert = 0;

        foreach ($csvData as $key => $row) {
            if ($key === 0) continue; // Skip the header row
            $length = count($row);
            if($length == 19){


                $image = [];

                if(!empty($row[4])){
                    $image[] =  $row[4];
                }

                if(!empty($row[5])){
                    $image[] =  $row[5];
                }

                if(!empty($row[6])){
                    $image[] =  $row[6];
                }

                if(!empty($row[7])){
                    $image[] =  $row[7];
                }

                if(!empty($image)){
                    $image = json_encode($image);
                }else{
                    $image = "";
                }

                $product_name = Helper::createUpperString($row[1]);
                $check_product = Product::where('is_admin','1')->where('name',$product_name)->count();
                if($check_product < 1){
                    Product::create(['name' => $product_name ,'category_id' => $row[9] ,'user_id' => Auth::user()->id ,'sp' => 0 ,'is_admin' => '1' ,'images' => $image]);
                    $total_subscriber_insert ++;
                }
                
            }
        }

        return $total_subscriber_insert;
    }

    private function insertBulkData3($filename) // upload dc csv category
    {
        $file = public_path('uploads/' . $filename);
        $csvData = array_map('str_getcsv', file($file));
        $csvHeader = $csvData[0]; // Assuming the first row contains header names

        $total_subscriber_insert = 0;

        foreach ($csvData as $key => $row) {
            if ($key === 0) continue; // Skip the header row
            $length = count($row);

            
            if($length == 12){

                // print_r($row);die;

                $product_name = Helper::createUpperString($row[2]);
                $check_product = Category::where('is_admin','1')->where('title',$product_name)->count();
                if($check_product < 1){
                    Category::create([
                                        'id' => $row[0],
                                        'title' => $product_name ,
                                        'user_id' => Auth::user()->id ,
                                        'sp' => 0 ,
                                        'is_admin' => '1' ,
                                    ]);
                    $total_subscriber_insert ++;
                }
                
            }
        }

        return $total_subscriber_insert;
    }


    public function edit_multiple_product_image (Request $request){
        $product_2 = $request->products;
        $product = json_decode($request->products);
        $products = Product::select('id','name','category_id','images')->whereIn('id',$product)->get();

        foreach($products as $key=>$val){
            if(!empty($val->images)){
                $images = json_decode($val->images);
            }else{
                $images = [];
            }
            $val['images'] = $images;
            $val['image_1'] = isset($images[0]) ? $images[0] :'no_image.png';
            $val['image_2'] = isset($images[1]) ? $images[1] :'no_image.png';
            $val['image_3'] = isset($images[2]) ? $images[2] :'no_image.png';
            $val['image_4'] = isset($images[3]) ? $images[3] :'no_image.png';

            $val['category_name'] = isset(Category::where('id',$val->category_id)->first()->title) ? Category::where('id',$val->category_id)->first()->title : '';
        }

        return view('admin.products.edit_multiple_product_image',compact('products','product_2'));

    }

    public function update_multiple_products_image (Request $request){
        $products = json_decode($request->products);
        $total_images_update = 0;
        foreach($products as $val){
            $product = Product::where('id',$val)->first();
            if(!empty($product->images)){
                $images = json_decode($product->images);
            }else{
                $images = [];    
            }
            for($i = 1; $i<5; $i++){
                $name = 'image_'.$i.'_'.$val;
                if($request->hasFile($name)) {
                    $image_name = time().rand(1,100).'-'.$request->$name->getClientOriginalName();
                    $image_name = preg_replace('/\s+/', '', $image_name);
                    $request->$name->move(public_path('images/products'), $image_name);
                    $thumbnail = Image::make(public_path('images/products') . '/' . $image_name)->fit(100, 100);
                    $thumbnail->save(public_path('images/products/thumb1') . '/' . $image_name);
                    $thumbnail_1 = Image::make(public_path('images/products') . '/' . $image_name)->fit(200, 200);
                    $thumbnail_1->save(public_path('images/products/thumb2') . '/' . $image_name);
                    $kk = $i-1;
                    if(isset($images[$kk])){
                        $images[$kk] = $image_name;
                    }else{
                        $images[] = $image_name;
                    }
                    $total_images_update ++;
                }
            }
            $product->update(['images' => json_encode($images)]);
        }
        return redirect()->route('products.index')->with('success',$total_images_update.' Images Uplode');
    }

    public function delete_multiple_images(Request $request){
        $product = json_decode($request->products);
        $products = Product::whereIn('id',$product)->delete();
        return redirect()->back()->with('success','Delete products successfully');
    }


}
