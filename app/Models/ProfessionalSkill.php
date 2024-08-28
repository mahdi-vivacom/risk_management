<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalSkill extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'professional_id',
        'skill_id',
    ];
    
}
