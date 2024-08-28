<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'professional_id',
        'country_area_id',
        'skill_id',
        'cancel_reason_id',
        'latitude',
        'longitude',
        'location',
        'company_cut',
        'unique_id',
        'status',
        'complete',
        'end_at',
    ];

    public function Skill ()
    {
        return $this->belongsTo ( Skill::class, 'skill_id', 'id' );
    }

    public function Professional ()
    {
        return $this->belongsTo ( Professional::class, 'professional_id', 'id' );
    }

    public function Client ()
    {
        return $this->belongsTo ( Client::class, 'client_id', 'id' );
    }

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'country_area_id', 'id' );
    }


}
