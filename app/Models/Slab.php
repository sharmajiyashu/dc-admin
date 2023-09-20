<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slab extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','name','status','is_default','days'];


    static $active = '1';
    static $inactive = '0';

    function slabLink(){
        return $this->hasOne(SlabLink::class);
    }

}
