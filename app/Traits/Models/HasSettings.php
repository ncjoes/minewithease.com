<?php
declare(strict_types=1);

namespace App\Traits\Models;

use App\Models\ETC\Setting;

/**
 * Trait HasSettings
 * @package App\Traits\Models
 */
trait HasSettings
{
    /**
     * @param $key
     * @param null $default
     * @return array|bool|false|mixed|string|null
     */
    public function getSetting($key, $default = null)
    {
        $settings = $this->getSettings();

        return array_key_exists($key, $settings) ? $settings[$key] : Setting::get($key, $default);
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return array_merge(static::defaultSettings(), $this->account_settings);
    }

    /**
     * @return array
     */
    public static abstract function defaultSettings();
}
