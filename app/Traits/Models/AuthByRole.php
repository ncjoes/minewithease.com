<?php
declare(strict_types=1);

namespace App\Traits\Models;

use App\Models\Auth\Role;

/**
 * Trait AuthByRole
 * @package App\Traits\Models
 */
trait AuthByRole
{
    /**
     * @return mixed
     */
    public function isRoot()
    {
        return $this->hasRole(Role::ROOT);
    }

    /**
     * @param $role_name
     * @return mixed
     */
    public function hasRole($role_name)
    {
        $role_name = explode('|', $role_name);

        return ($this->roles()->whereIn('name', $role_name)->count());
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'auth_role_user', 'user_id', 'role_id');
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole(Role::ADMIN);
    }

    /**
     * @return mixed
     */
    public function isEditor()
    {
        return $this->hasRole(Role::EDITOR);
    }

    /**
     * @return mixed
     */
    public function isMember()
    {
        return $this->hasRole(Role::CLIENT);
    }
}
