<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 
        'name',
        'email',
        'password',
        'profile_image',
        'password_set_by_user_id',
        'phone_number',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [ 
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ 
        'email_verified_at' => 'datetime',
    ];

    // public function roles ()
    // {
    //     return $this->belongsToMany ( Role::class, 'model_has_roles', 'model_id', 'role_id' );
    // }

    public function getRoleDisplayNames ()
    {
        return $this->roles->pluck ( 'display_name' )->first ();
    }

    public function role_user ()
    {
        return $this->hasOne ( RoleUser::class, 'model_id', 'id' );
    }

}
