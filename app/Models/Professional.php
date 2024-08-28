<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Professional extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'phone_number',
        'gender',
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'profile_image',
        'wallet_money',
        'total_earnings',
        'current_latitude',
        'current_longitude',
        'last_location_update_time',
        'bearing',
        'accuracy',
        'device',
        'rating',
        'country_id',
        'country_area_id',
        'login_logout',
        'online_offline',
        'free_busy',
        'signup_from',
        'signup_step',
        'online_code',
        'referral_code',
        'admin_msg',
        'dob',
        'app_debug_mode',
        'segment_id',
        'reward_points',
        'is_suspended',
        'referred_by',
        'device_ip',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function Skill ()
    {
        return $this->belongsToMany ( Skill::class, 'professional_skills' );
    }

    public function getSkillNameAttribute ()
    {
        $skill = $this->Skill->first ();
        return $skill ? $skill->name : null;
    }

    public function ProfessionalSkill ()
    {
        return $this->hasMany ( ProfessionalSkill::class);
    }

    public function Country ()
    {
        return $this->belongsTo ( Country::class, 'country_id', 'id' );
    }

    public function Area ()
    {
        return $this->belongsTo ( CountryArea::class, 'country_area_id', 'id' );
    }

    public function getFullNameAttribute ()
    {
        return ( !empty ( $this->first_name ) && !empty ( $this->last_name ) )
            ? $this->first_name . ' ' . $this->last_name
            : ( !empty ( $this->first_name ) ? $this->first_name : ( !empty ( $this->last_name ) ? $this->last_name : '' ) );
    }

    public function insertSkill ( $skillId )
    {
        return DB::transaction ( function () use ($skillId) {
            $this->ProfessionalSkill ()->delete ();

            $array[] = [
                'professional_id' => $this->id,
                'skill_id'        => $skillId,
                'created_at'      => now (),
                'updated_at'      => now (),
            ];
            return $this->ProfessionalSkill ()->insert ( $array );
        } );
    }

    public function Subscription()
    {
        return $this->hasOne ( SubscriptionHistory::class, 'professional_id', 'id');
    }

}
