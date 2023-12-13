<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class DemoProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','category_id','status','detail','images','sp','mrp','stock','stock','order_limit','quantity','unit','packing_quantity','is_limited'];

    static $limited = '1';
    static $unlimited = '0';

    static $active = 1;
    static $inactive = 0;

    function category(){
        return $this->belongsTo(Category::class,'id');
    }

    protected static function boot() {
        parent::boot();
    
        static::deleting(function($product) {
            $image = json_decode($product->images);
            if(is_array($image)){
                foreach($image as $key => $val){
                    $path_1 = public_path('images/products/'.$val);
                    $path_2 = public_path('images/products/thumb1/'.$val);
                    $path_3 = public_path('images/products/thumb2/'.$val);
                    if(File::exists($path_1)) {
                        File::delete($path_1);
                    }
                }
            }
        });

        self::updating(function($product){
            $update_products = json_decode($product->images);
            $old_product = DemoProduct::find($product->id);
            $old_prodduct_image = json_decode($old_product->images);
            foreach($old_prodduct_image as $val){
                if (in_array($val, $update_products)) {
                    
                } else {
                    $path_1 = public_path('images/products/'.$val);
                    $path_2 = public_path('images/products/thumb1/'.$val);
                    $path_3 = public_path('images/products/thumb2/'.$val);
                    if(File::exists($path_1)) {
                        File::delete($path_1);
                    }
                    if(File::exists($path_2)) {
                        File::delete($path_2);
                    }
                    if(File::exists($path_3)) {
                        File::delete($path_3);
                    }
                }
            }
        });
    }

}