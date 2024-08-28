<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'package_id',
        'professional_id',
        'area_id',
        'auto_renewal',
        'validate_till',
        'status',
    ];

    public function Subscription()
    {
        return $this->belongsTo ( Subscription::class, 'package_id', 'id');
    }

    public function Professional ()
    {
        return $this->belongsTo ( Professional::class, 'professional_id', 'id' );
    }

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'area_id', 'id' );
    }


}
