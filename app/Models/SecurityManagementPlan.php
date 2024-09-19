<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDele$table->text('plan_details');  // Detailed plan information
        $table->integer('risk_score');  // Risk score associated with the plan
        $table->unsignedBigInteger('client_id');tes;

class SecurityManagementPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];



}
