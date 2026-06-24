<?php
declare(strict_types=1);

namespace App\Traits\Models;

/**
 * Trait HasName
 * @package App\Traits\Models
 */
trait HasName
{
    /**
     * @param $first_name
     *
     * @return string
     */
    public function getFirstNameAttribute($first_name)
    {
        return ucwords(strtolower($first_name));
    }

    /**
     * @param $str
     */
    public function setFirstNameAttribute($str)
    {
        $this->attributes['first_name'] = trim($str);
    }

    /**
     * @param $last_name
     *
     * @return string
     */
    public function getLastNameAttribute($last_name)
    {
        return ucwords(strtolower($last_name));
    }

    /**
     * @param $str
     */
    public function setLastNameAttribute($str)
    {
        $this->attributes['last_name'] = trim($str);
    }

    protected function getNameAttribute()
    {
        return $this->name();
    }

    public abstract function name();
}
