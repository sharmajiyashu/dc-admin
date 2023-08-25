<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    // use HasFactory, HasApiTokens,SoftDeletes; 
    use HasFactory, HasApiTokens; 

    protected $table = 'users';
    protected $fillable = ['mobile','name','gender','dob','state','city','address','pin','image','role_id','image','active_store_code','remember_token','store_name'];

    function index(){
        Customer::create(['role_id',Role::$customer]);
    }
    


}
