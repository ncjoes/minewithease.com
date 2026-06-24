<?php
declare(strict_types=1);

namespace App\Managers;

use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\Core\Channel;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Currency;
use App\Traits\Controllers\ResolveExchangeRate;

/**
 * Class WithdrawalManager
 * @package App\Managers
 */
abstract class WithdrawalManager
{
    use ResolveExchangeRate;

    /**
     * @param Account $account
     * @param $base_amount
     * @param $payment_wallet
     * @return Withdrawal
     */
    public static function create(Account $account, $base_amount, $payment_wallet): Withdrawal
    {
        $base_currency = Currency::getDefault();


        $local_currency = $account->currency;
        $local_amount     = self::getExchangeValue($base_currency->alpha_code, $local_currency->alpha_code, $base_amount, $local_currency->minor_unit);

        /**
         * @var Withdrawal $withdrawal
         */
        $withdrawal   = Withdrawal::create([
            'uuid'           => Withdrawal::generateUUID($account->user->uuid),
            'currency_id'    => $base_currency->id,
            'account_id'     => $account->id,
            'amount'         => $base_amount,
            'local_amount'   => $local_amount,
            'payment_wallet' => $payment_wallet,
            'status'         => Withdrawal::S_PENDING,
        ]);

        return $withdrawal;
    }
}
