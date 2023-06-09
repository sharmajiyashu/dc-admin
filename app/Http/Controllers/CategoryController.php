<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
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
        $categories = Category::orderBy('title','asc')->where('is_admin','1')->get();
        return view('admin.categories.index',compact('categories'));
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
        if($request->hasFile('image')) {
            $image       = $request->file('image');
            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/categories'), $image_name);
        }
        $data = $request->validated();
        $data['image'] = isset($image_name) ? $image_name : '';
        $data['user_id'] = Auth::user()->id;
        $data['is_admin'] = '1';
        $category = Category::create($data);

        $vendor = Vendor::where('role_id',Role::$vendor)->where('is_register','1')->get();
        foreach($vendor as $key=>$val){
            $data_2 = $request->validated();
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
    public function show(Category $category)
    {
        
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
        $data = $request->validated();

        $data_2 = [
            'title' => $request->title,
            // 'status' => $request->status,
        ];
        if($request->hasFile('image')) {
            // $image       = $request->file('image');
            // $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
            // $image_name = preg_replace('/\s+/', '', $image_name);
            // $image->move(public_path('images/categories/'.$image_name));

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
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category Delete Success');
    }
}
