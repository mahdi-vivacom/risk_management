<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [ 
        'country_id',
        'country_area_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'type',
        'gender',
        'status',
        'current_latitude',
        'current_longitude',
        'otp',
        'referral_code',
        'average_rating',
        'email_verified',
        'phone_verified',
        'profile_image',
        'reward_points',
    ];

    protected $hidden = [ 
        'password',
    ];

    public function getFullNameAttribute ()
    {
        return ( !empty ( $this->first_name ) && !empty ( $this->last_name ) )
            ? $this->first_name . ' ' . $this->last_name
            : ( !empty ( $this->first_name ) ? $this->first_name : ( !empty ( $this->last_name ) ? $this->last_name : '' ) );
    }

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'country_area_id', 'id' );
    }

    
}
