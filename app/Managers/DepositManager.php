<?php
declare(strict_types=1);

namespace App\Managers;

use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\Core\Channel;
use App\Models\Core\Deposit;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Traits\Controllers\ResolveExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DepositManager
 * @package App\Managers
 */
abstract class DepositManager
{
    use ResolveExchangeRate;

    /**
     * @param Account $account
     * @param $base_amount
     * @return Deposit
     */
    public static function create(Account $account, $base_amount): Deposit
    {
        $account_currency = $account->currency;
        $base_currency    = Currency::getDefault();
        $local_amount   = self::getExchangeValue($base_currency->alpha_code, $account_currency->alpha_code, $base_amount, $account_currency->minor_unit);

        /**
         * @var Deposit $deposit
         */
        $deposit = Deposit::create([
            'currency_id'  => $base_currency->id,
            'account_id'   => $account->id,
            'uuid'         => Deposit::generateUUID($account->user->uuid),
            'amount'       => $base_amount,
            'local_amount' => $local_amount,
            'status'       => Deposit::S_PENDING,
        ]);

        return $deposit;
    }

    /**
     * @return string|null
     */
    public static function remindPendingDepositors(): ?string
    {
        $counter           = 0;
        $now               = Carbon::now();
        $reminder_interval = Setting::get(Setting::KEY_INVOICE_REMINDER_INTERVAL, 6);
        $updated_at        = $now->subHours($reminder_interval);
        /**
         * @var Collection $deposits
         */
        $deposits = Deposit::findByStatus(Deposit::S_PENDING)->whereDate('updated_at', '<=', $updated_at)->get();

        /**
         * @var Deposit $deposit
         */
        foreach ($deposits as $deposit) {
            if ($deposit->updated_at->lessThan($updated_at)) {
                //cancel transaction
                NotificationManager::sendPendingDepositNotice($deposit);
                $deposit->touch();
                $counter++;
            }
        }

        return $counter ? $now . " => " . $counter . " deposit invoice reminders sent." : null;
    }

    /**
     * @return string|null
     */
    public static function cancelOverdueDeposits(): ?string
    {
        $counter             = 0;
        $now                 = Carbon::now();
        $max_validity_period = Setting::get(Setting::KEY_INVOICE_MAX_VALIDITY, 24);
        $expire_at           = $now->copy()->subMinutes($max_validity_period);
        /**
         * @var Collection $deposits
         */
        $deposits = Deposit::findByStatus([Deposit::S_PENDING])->whereDate('created_at', '<=', $expire_at)->get();

        /**
         * @var Deposit $deposit
         */
        foreach ($deposits as $deposit) {
            if ($deposit->created_at->lessThan($expire_at)) {
                //cancel invoice
                $deposit->update(['status' => Deposit::S_CANCELED]);
                $counter++;
            }
        }

        return $counter ? $now . " => " . $counter . " deposit invoices cancelled." : null;
    }
}
