<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishCart extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','p_price','user_id','p_mrp','quantity','total','status','order_id','store_code','vendor_id'];
}
