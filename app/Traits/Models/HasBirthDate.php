<?php
declare(strict_types=1);

namespace App\Traits\Models;

use Carbon\Carbon;

/**
 * Trait HasBirthDate
 * @package App\Traits\Models
 */
trait HasBirthDate
{
    /**
     * @return int|null
     */
    public function getBirthMonthAttribute()
    {
        return is_object($this->getBirthDate()) ? $this->getBirthDate()->month : null;
    }

    /**
     * @return Carbon
     */
    public abstract function getBirthDate();

    /**
     * @return int|null
     */
    public function getBirthDayAttribute()
    {
        return is_object($this->getBirthDate()) ? $this->getBirthDate()->day : null;
    }

    /**
     * @return int|null
     */
    public function getBirthYearAttribute()
    {
        return is_object($this->getBirthDate()) ? $this->getBirthDate()->year : null;
    }
}
