<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }
}
