<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SosRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'booking_id',
        'country_area_id',
        'number',
        'latitude',
        'longitude',
        'status',
        'application',
    ];

    public function Booking ()
    {
        return $this->belongsTo ( Booking::class );
    }

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class);
    }

}
