<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory,SoftDeletes,SoftDeletes;
    protected $table = 'users';
    protected $fillable = ['mobile','name','gender','dob','state','city','address','pin','image','role_id','image'];

    public function scopeVendor($query)
    {
        return $query->where('role_id',Role::$vendor);
    }

    public function scopeStatus($query, $args){
        return $query->where('status',$args);
    }

    static $active = '1';
    static $inactive = '0';
    
    
}
