<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','category_id','status','detail','images','sp','mrp','stock','stock','order_limit','quantity','unit','packing_quantity','is_limited'];

    function category(){
        return $this->belongsTo(Category::class,'id');
    }

    static $limited = '1';
    static $unlimited = '0';

    static $active = 'Active';
    static $inactive = 'Inactive';

    
}
