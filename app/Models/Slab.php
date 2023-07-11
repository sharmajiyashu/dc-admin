<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slab extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','status','is_default','days'];


    static $active = '1';
    static $inactive = '2';

}
