<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    use HasFactory;

    protected $fillable = ['title','body','to_vendors','image','to_customers','count'];
}
