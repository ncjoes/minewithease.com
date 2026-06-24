<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByUsername
 * @package App\Traits\Models
 */
trait FindByUsername
{
    /**
     * @param $username
     *
     * @return mixed
     */
    public static function findByUsername($username)
    {
        return self::findByColumn('username', $username)->first();
    }
}
