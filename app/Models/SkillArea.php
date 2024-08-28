<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillArea extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'country_area_id',
        'skill_id',
    ];
}
