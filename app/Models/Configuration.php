<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'support_mail',
        'support_phone',
        'google_key',
        'distance_unit',
        'splash_screen',
        'status',
    ];

}
