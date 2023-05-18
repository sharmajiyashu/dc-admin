<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','stock','category_id','mrp','sp','order_limit','quantity','unit','packing_quantity','status','detail','images'];
}
