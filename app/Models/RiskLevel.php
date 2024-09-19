<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level',
        'score_min',
        'score_max',
        'color',
        'action',
    ];
}
