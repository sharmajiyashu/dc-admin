<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';
    protected $fillable = ['mobile','name','gender','dob','state','city','address','pin','image','role_id'];
    function index(){
        Customer::create(['role_id',Role::$customer]);
    }
    


}
