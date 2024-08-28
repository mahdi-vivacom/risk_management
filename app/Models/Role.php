<?php

namespace App\Models;

use Spatie\Permission\Models\Role AS BaseRole;
use Illuminate\Database\Eloquent\Model;


class Role extends BaseRole
{
	protected $fillable = ['name', 'display_name', 'guard_name'];

    public function user_role()
    {
        return $this->belongsToMany(RoleUser::class, 'role_id', 'id');
        // return $this->belongsToMany('App\Model\RoleUser', 'role_id', 'id');
    }

}
