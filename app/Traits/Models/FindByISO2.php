<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByISO2
 * @package App\Traits\Models
 */
trait FindByISO2
{
    /**
     * @param $code
     *
     * @return $this
     */
    public static function findByISO2($code)
    {
        return static::findByColumn('iso2', $code)->first();
    }
}
