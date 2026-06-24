<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait FindByPhone
 * @package App\Traits\Models
 */
trait FindByPhone
{
    /**
     * @param $phone
     *
     * @return mixed
     */
    public static function findByPhone($phone)
    {
        return self::findByColumn('phone', $phone)->first();
    }

    public function getDialCodeAttribute()
    {
        //return $this->country->dial_code; ToDo
        return '+234';
    }
}
