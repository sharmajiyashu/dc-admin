<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['status','order_id','user_id','vendor_id','store_code','amount','note'];


    static $pending = "pending";
    static $accepted = "accepted";
    static $rejected = "rejected";
    static $delivered = "delivered";
    static $dispatched = "dispatched";
    
}
