<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByName
 * @package App\Traits\Models
 */
trait FindByName
{
    /**
     * @param $name
     * @return self
     */
    public static function findByName($name)
    {
        return static::findByColumn('name', $name)->first();
    }
}
