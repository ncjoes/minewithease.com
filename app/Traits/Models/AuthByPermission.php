<?php
declare(strict_types=1);

namespace App\Traits\Models;

use App\Models\Auth\Permission;

/**
 * Trait AuthByPermission
 * @package App\Traits\Models
 */
trait AuthByPermission
{
    /**
     * @param $permission_name
     * @return mixed
     */
    public function hasPermission($permission_name)
    {
        $permission_name = explode('|', $permission_name);
        $permission_name += ['*'];

        return ($this->permissions()->whereIn('name', $permission_name)->count() > 0);
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'auth_permission_user');
    }
}
