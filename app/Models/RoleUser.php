<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'model_has_roles';
    public $timestamps = false;
    
    protected $fillable = ['role_id', 'model_type', 'model_id'];

	public function roles()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id', 'model_id');
    }

}
