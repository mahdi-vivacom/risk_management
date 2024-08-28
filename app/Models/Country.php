<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'iso_code_2',
        'iso_code_3',
        'phone_code',
        'status',
        'distance_unit'
    ];
    
    public function CountryArea ()
    {
        return $this->hasMany ( CountryArea::class, 'country_id', 'id' );
    }
    
}
