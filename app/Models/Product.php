<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','category_id','status','detail','images'];

    function category(){
        return $this->belongsTo(Category::class,'id');
    }
}
