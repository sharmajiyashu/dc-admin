<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreLink extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','store_code' ,'vendor_id','status','in_add','slab_id'];

    static $active = '1';
    static $inactive = '2';

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    
}
