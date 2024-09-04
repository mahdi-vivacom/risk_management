<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CountryArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country_id',
        'coordinates',
        'color',
        'timezone',
        'status',
    ];

    public function Country ()
    {
        return $this->belongsTo ( Country::class);
    }

    public function Skill ()
    {
        return $this->belongsToMany ( Skill::class, 'skill_areas', 'country_area_id', 'skill_id' );
    }

    public function insertSkillAreas ( array $skillIds )
    {
        return DB::transaction ( function () use ($skillIds) {
            $this->skillAreas ()->delete ();

            $skillAreas = [];
            foreach ( $skillIds as $skillId ) {
                $skillAreas[] = [
                    'country_area_id' => $this->id,
                    'skill_id'        => $skillId,
                    'created_at'      => now (),
                    'updated_at'      => now (),
                ];
            }

            return $this->skillAreas ()->insert ( $skillAreas );
        } );
    }

    public function skillAreas ()
    {
        return $this->hasMany ( SkillArea::class);
    }

}
