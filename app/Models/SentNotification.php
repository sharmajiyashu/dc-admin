<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SentNotification extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['title','body','to_vendors','image','to_customers','count'];
}
