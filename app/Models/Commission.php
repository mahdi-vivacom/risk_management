<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'amount',
        'area_id',
        'status',
    ];

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'area_id', 'id' );
    }

    public function status ( $status )
    {
        if ( $status == 1 ) {
            return DB::transaction ( function () {
                Commission::where ( 'status', 1 )->update ( [ 'status' => 0 ] );
            } );
        }
    }

}
