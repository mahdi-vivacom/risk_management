<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityRiskAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'risk_score',
        'mitigation_measures',
        'client_id',
        'area_id',
        'status',
    ];


}
