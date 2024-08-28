<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'label',
        'serial',
        'route',
        'parent_id',
        'permission_id',
        'icon',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function MainMenu()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function SubMenu()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }


}
