<?php

namespace App\Managers;

use App\Models\Core\Swap;
use App\Models\ETC\Currency;
use App\Traits\Controllers\ResolveExchangeRate;

abstract class SwapManager
{
    use ResolveExchangeRate;

    /**
     * Create a new Swap instance.
     */
    public static function create($sourceAccount, $destinationAccount, $amount): Swap
    {
        $defaultCurrency = Currency::getDefault();

        $swap = Swap::create([
            'uuid' => Swap::generateUUID($sourceAccount->user->uuid),
            'user_id' => $sourceAccount->user_id,
            'currency_id' => $defaultCurrency->id,
            'source_account_id' => $sourceAccount->id,
            'destination_account_id' => $destinationAccount->id,
            'amount' => $amount,
            'source_local_amount' => self::getExchangeValue($defaultCurrency->alpha_code, $sourceAccount->currency->alpha_code, $amount),
            'destination_local_amount' => self::getExchangeValue($defaultCurrency->alpha_code, $destinationAccount->currency->alpha_code, $amount),
            'status' => Swap::S_PENDING,
        ]);

        return $swap;
    }
}
