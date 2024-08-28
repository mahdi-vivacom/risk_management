<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [ 
        'name',
        'description',
        'image',
        'status',
    ];

    public function Area ()
    {
        return $this->belongsToMany ( CountryArea::class, 'skill_areas', 'country_area_id', 'skill_id' );
    }

    public function getImageAttribute ( $value )
    {
        return $value ? asset ( $value ) : asset ( 'static-images/no-skill.png' );
    }


}
