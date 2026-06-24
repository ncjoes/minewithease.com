<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByStatus
 * @package App\Traits\Models
 */
trait FindByStatus
{
    /**
     * @param $status
     *
     * @return mixed
     */
    public static function findByStatus($status)
    {
        if (is_array($status)) {
            return static::whereIn('status', $status);
        }

        return static::findByColumn('status', $status);
    }
}
