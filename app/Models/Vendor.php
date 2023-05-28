<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = ['mobile','name','gender','dob','state','city','address','pin','image','role_id','image'];
    
}
