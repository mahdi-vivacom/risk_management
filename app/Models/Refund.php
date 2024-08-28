<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'refund_no',
        'professional_id',
        'booking_id',
        'client_id',
        'amount',
        'charge',
        'details',
        'status',
    ];

    public function Professional ()
    {
        return $this->belongsTo ( Professional::class, 'professional_id', 'id' );
    }

    public function Client ()
    {
        return $this->belongsTo ( Client::class, 'client_id', 'id' );
    }

    public function Booking ()
    {
        return $this->belongsTo ( Booking::class, 'booking_id', 'id' );
    }

}
