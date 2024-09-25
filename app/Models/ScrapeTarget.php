<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScrapeTarget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['url', 'keywords', 'area_id', 'status'];

    // Define the relationship with CountryArea model
    public function area()
    {
        return $this->belongsTo(CountryArea::class, 'area_id', 'id');
    }

    // Add a method to get the area_name
    public function getAreaNameAttribute()
    {
        return $this->area ? $this->area->name : null; // Fetch the area name from CountryArea model
    }
}
