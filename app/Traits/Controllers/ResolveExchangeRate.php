<?php
declare(strict_types=1);

namespace App\Traits\Controllers;

use Exchanger\ExchangeRate;
use Swap\Laravel\Facades\Swap;

/**
 * Trait ResolveExchangeRate
 * @package App\Traits\Controllers
 */
trait ResolveExchangeRate
{
    static $rates = [];

    public static function getExchangeValue($inputCurrency, $outputCurrency, $inputAmount, $dps = 8): float
    {
        $exchange_rate = self::getExchangeRate($inputCurrency, $outputCurrency);

        return round($exchange_rate * $inputAmount, $dps);
    }

    /**
     * @param $baseCurrency
     * @param $channelCurrency
     *
     * @return float|int
     */
    public static function getExchangeRate($baseCurrency, $channelCurrency)
    {
        if (array_key_exists($baseCurrency, self::$rates) and is_array(self::$rates[$baseCurrency]) and array_key_exists($channelCurrency, self::$rates[$baseCurrency])) {
            return self::$rates[$baseCurrency][$channelCurrency];
        }
        Swap::clearResolvedInstances();
        /**
         * @var ExchangeRate $rate
         */
        $exchange_rate = Swap::latest($baseCurrency . '/' . $channelCurrency)->getValue();

        self::$rates[$baseCurrency][$channelCurrency] = $exchange_rate;

        return $exchange_rate;
    }
}
