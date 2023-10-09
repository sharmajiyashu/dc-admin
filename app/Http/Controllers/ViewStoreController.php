<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Help;

class ViewStoreController extends Controller
{
    public function index($store_id){
        $user = User::where('store_code',$store_id)->first();
        if($user){
            $categories = Category::where('user_id',$user->id)->where('status',Category::$active)->get()->map(function($category){
                if(empty($category->image)){
                    $category->image = 'no_image.png';
                }
                return $category;
            });
            $slab_id = Helper::getDefaultSlab();
            $products = Product::where('user_id', $user->id)
			->where('status',1)
			->where('is_admin', '0')
			->latest()
			->get()->map(function ($product) use($slab_id){
				$image = $product->images;
				$product->images = Helper::transformImages($image);
                $product->image = isset($product->images[0]) ? $product->images[0] :'';
				$product->original_images = Helper::transformOrignilImages($image);
				$slab_check = SlabLink::where(['product_id' => $product->id ,'user_id' => $product->user_id,'slab_id' => $slab_id])->exists();
				$slab_data = Slab::find($slab_id);
				if($slab_check && $slab_data->status == Slab::$active){
					return $product ? $product :'';
				}
			})->filter()->values()->take(10);
            return view('frontend.index',compact('categories','user','products'));
        }else{

        }
    }

    public function productDetail($id){
        $product = Product::find($id);
        if($product){
            $product->original_images = Helper::transformOrignilImages($product->images);
            $user = User::find($product->user_id);
            return view('frontend.product-details',compact('product','user'));
        }else{

        }
    }

    public function categoriesDetail($id,$user_id){
        $category = Category::find($id);
        $user = User::find($user_id);
        $slab_id = Helper::getDefaultSlab();
        $products = Product::where('user_id', $user->id);
        if($category){
            $products->where('category_id',$category->id);
        }
        $products->where('status',1)
        ->where('is_admin', '0')
        ->latest();
        $products = $products->get()->map(function ($product) use($slab_id){
            $image = $product->images;
            $product->images = Helper::transformImages($image);
            $product->image = isset($product->images[0]) ? $product->images[0] :'';
            $product->original_images = Helper::transformOrignilImages($image);
            $slab_check = SlabLink::where(['product_id' => $product->id ,'user_id' => $product->user_id,'slab_id' => $slab_id])->exists();
            $slab_data = Slab::find($slab_id);
            if($slab_check && $slab_data->status == Slab::$active){
                return $product ? $product :'';
            }
        })->filter()->values();
        $categories = Category::where('user_id',$user->id)->where('status',Category::$active)->get();
        return view('frontend.category',compact('user','category','products','categories'));
        
    }


}
