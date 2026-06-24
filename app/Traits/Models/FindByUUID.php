<?php
declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Support\Arr;

/**
 * Trait FindByUUID
 * @package App\Traits\Models
 */
trait FindByUUID
{
    /**
     * @param $alphas
     * @param $numerals
     * @return string
     */
    protected static function makeUUID($alphas, $numerals)
    {
        $uuid = self::makeUID($alphas, $numerals);
        if (is_object(self::findByUUID($uuid))) {
            return self::makeUUID($alphas, $numerals);
        }

        return strtoupper($uuid);
    }

    /**
     * @param $alphas
     * @param $numerals
     * @param bool $ordered
     * @return string
     */
    protected static function makeUID($alphas, $numerals, $ordered = true)
    {
        $alphabets = range('A', 'H') + range('J', 'N') + range('P', 'Z');
        $numbers   = range(1, 9);
        if ($ordered) {
            shuffle($numbers);
            shuffle($alphabets);
            $uuid = implode('', Arr::random($alphabets, $alphas)) . implode('', Arr::random($numbers, $numerals));
        } else {
            $arr = $numerals + $alphas;
            shuffle($arr);
            $uuid = implode('', $arr);
        }

        return $uuid;
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public static function findByUUID($uuid)
    {
        //return static::withTrashed()->where('uuid', $uuid)->first();
        return static::where('uuid', $uuid)->first();
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        return parent::update(Arr::except($attributes, ['uuid']), $options);
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
