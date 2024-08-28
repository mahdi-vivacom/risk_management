<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sos extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'country_id',
        'number',
        'status',
        'application',
    ];

    public function Country ()
    {
        return $this->belongsTo ( Country::class );
    }

}
