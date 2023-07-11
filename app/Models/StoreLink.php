<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreLink extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','store_code' ,'vendor_id','status','in_add'];

    
}
