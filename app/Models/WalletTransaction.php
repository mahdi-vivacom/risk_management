<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'professional_id',
        'narration',
        'transaction_type',
        'amount',
        'subscription_id',
        'booking_id',
        'description',
        'receipt_number',
        'transaction_id',
    ];

    public function Professional ()
    {
        return $this->belongsTo ( Professional::class, 'professional_id', 'id' );
    }

    public function Subscription ()
    {
        return $this->belongsTo ( Subscription::class, 'package_id', 'id' );
    }

    public function Transaction ()
    {
        return $this->belongsTo ( Transaction::class, 'transaction_id', 'id' );
    }

}
