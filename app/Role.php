<?php

namespace App;

use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\Models\Permission;

class Role extends BaseRole
{
    protected $guard_name ='api';
    
    /**
     * Get the permissions of a specific role.
     */
    public function getPermissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
