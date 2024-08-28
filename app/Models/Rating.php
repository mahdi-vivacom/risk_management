<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'booking_id',
        'client_rating_points',
        'client_comment',
        'professional_rating_points',
        'professional_comment',
    ];

}
