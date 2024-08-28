<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessionalTime extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'professional_id',
        'time_intervals',
    ];


}
