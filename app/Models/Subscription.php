<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'title',
        'label',
        'area_id',
        'amount',
        'renewal',
        'details',
        'description',
        'status',
    ];

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'area_id', 'id' );
    }

}
