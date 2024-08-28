<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 
        'title',
        'country_id',
        'slug',
        'content',
        'application',
        'status',
    ];

    public function Country ()
    {
        return $this->belongsTo ( Country::class);
    }
}
