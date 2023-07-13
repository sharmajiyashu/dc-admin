<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title','image','status','user_id','admin_id','is_admin','is_delete'];

    static $active = 'Active';
    static $inactive = 'Inactive';
}
