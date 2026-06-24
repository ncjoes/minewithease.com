<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByEmail
 * @package App\Traits\Models
 */
trait FindByEmail
{
    /**
     * @param $email
     *
     * @return mixed
     */
    public static function findByEmail($email)
    {
        return self::findByColumn('email', $email)->first();
    }
}
