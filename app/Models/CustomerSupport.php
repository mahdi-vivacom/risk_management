<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'email',
        'name',
        'phone',
        'message',
        'application',
        'status',
    ];
}
