<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
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
        $categories = Category::orderBy('title','asc')->get();
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
            $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $image->move(public_path('images/categories/'.$image_name));
        }
        $data = $request->validated();
        $data['image'] = isset($image_name) ? $image_name : '';
        Category::create($data);
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
        if($request->hasFile('image')) {
            // $image       = $request->file('image');
            // $image_name = time().rand(1,100).'-'.$image->getClientOriginalName();
            // $image_name = preg_replace('/\s+/', '', $image_name);
            // $image->move(public_path('images/categories/'.$image_name));

            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/categories'), $image_name);
            $data['image'] = $image_name;
        }
        
        $category->update($data);
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
