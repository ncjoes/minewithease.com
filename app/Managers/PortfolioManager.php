<?php
declare(strict_types=1);

namespace App\Managers;

use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Traits\Controllers\ResolveExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class PortfolioManager
 * @package App\Managers
 */
abstract class PortfolioManager
{
    use ResolveExchangeRate;
    
    /**
     * @return array
     */
    public static function assignInterests(): array
    {
        $now = Carbon::now();
        $portfolios = Portfolio::activeOnly()->get();
        $transactions = [];

        /**
         * @var Portfolio $portfolio
         */
        foreach ($portfolios as $portfolio) {

            /**
             * @var Carbon $last_rewarded
             */
            $last_rewarded = is_null($portfolio_last_rewarded = $portfolio->last_rewarded_at) ? $portfolio->created_at : $portfolio_last_rewarded;
            while ($now->diffInDays($last_rewarded) >= $portfolio->interest_interval) {

                $rate = array_rand(range($portfolio->min_interest_rate, $portfolio->max_interest_rate, 0.1));
                $interest_amount = round($portfolio->amount() * $rate / 100, $portfolio->currency->minor_unit);
                $interest_date = $last_rewarded->copy()->addDays($portfolio->interest_interval);
                $description = "Interest on Stake-{$portfolio->uuid} for " . date_for_humans($last_rewarded) . " to " . date_for_humans($interest_date) . " Trading Period";

                if ($interest_date->lessThanOrEqualTo($portfolio->expires_at)) {
                    DB::beginTransaction();
                    Carbon::setTestNow($interest_date);
                    $trans = TransactionManager::logPortfolioCredit($portfolio, $interest_amount, $description);
                    $portfolio->update(['last_rewarded_at' => $interest_date]);
                    DB::commit();
                    Carbon::setTestNow($now);

                    $transactions = array_merge($transactions, $trans);
                    $last_rewarded = $interest_date;

                } else {
                    break;
                }
            }
        }

        return $transactions;
    }

    /**
     * @param Portfolio $portfolio
     * @return array
     */
    public static function closeManually(Portfolio $portfolio): array
    {
        $transactions = [];
        $now = Carbon::now();

        if ($portfolio->isCancellable()) {
            DB::beginTransaction();
            self::closePortfolio($portfolio, $transactions, $now);
            $portfolio->update(['status' => Portfolio::S_CLOSED]);
            $m = "Stake ({$portfolio->uuid}) closed";
            $transactions[] = TransactionManager::logPortfolioCredit($portfolio, $portfolio->amount, $m);
            DB::commit();
        }

        return $transactions;
    }

    /**
     * @return array
     */
    public static function updateStates(): array
    {
        $affected = 0;
        $transactions = [];
        $now = Carbon::now();
        /**
         * @var Builder $portfolios
         */
        $portfolios = Portfolio::activeOnly()->where('expires_at', '<', $now)->get();
        /**
         * @var Portfolio $portfolio
         */
        foreach ($portfolios as $portfolio) {
            $account = $portfolio->account;
            $user = $account->user;
            Carbon::setTestNow($portfolio->expires_at);

            self::closePortfolio($portfolio, $transactions, $now);
            if ($user->getSetting(Setting::KEY_ALLOW_INVESTING) and $user->getSetting(Setting::KEY_AUTO_REINVEST)) {
                self::create($portfolio->package, $account, $portfolio->amount, $portfolio->expires_at);
            }

            Carbon::setTestNow($now);
        }

        return $transactions;
    }

    protected static function closePortfolio(Portfolio $portfolio, array &$transactions, Carbon $now): void
    {
        $user = $portfolio->user;
        $message = "Principal on Completed Stake #" . $portfolio->uuid;
        $package = $portfolio->package;
        $account = $portfolio->account;

        Carbon::setTestNow($portfolio->expires_at);
        $transactions = array_merge($transactions, TransactionManager::logPortfolioCredit($portfolio, $user, $portfolio->amount(), $message));
        $portfolio->update(['status' => Portfolio::S_COMPLETED]);

        if ($package->service_charge_rate != 0) {
            $message = "Service Charge on Completed Stake #" . $portfolio->uuid;
            $amount = round($package->service_charge_rate / 100 * $portfolio->amount(), $portfolio->currency->minor_unit);
            $local_amount   = self::getExchangeValue($portfolio->currency->alpha_code, $account->currency->alpha_code, $amount, $account->currency->minor_unit);

            $transactions = array_merge($transactions, TransactionManager::logPortfolioDebit($portfolio, $amount, $local_amount, $message));
        }
        Carbon::setTestNow($now);
    }

    /**
     * @param Package $package
     * @param User $user
     * @param $amount
     * @param null $now
     * @return Portfolio
     */
    public static function create(Package $package, Account $account, $amount, $now = null): Portfolio
    {
        $real_now = Carbon::now();
        $now = is_null($now) ? $real_now : $now;
        Carbon::setTestNow($now);

        $account_currency = $account->currency;
        $base_currency    = Currency::getDefault();
        $local_amount   = self::getExchangeValue($base_currency->alpha_code, $account_currency->alpha_code, $amount, $account_currency->minor_unit);

        $portfolio = Portfolio::create([
            'uuid' => Portfolio::generateUUID($account->user->uuid),
            'currency_id' => $base_currency->id,
            'account_id' => $account->id,
            'package_id' => $package->id,
            'amount' => $amount,
            'local_amount' => $local_amount,
            'min_interest_rate' => $package->min_interest_rate,
            'max_interest_rate' => $package->max_interest_rate,
            'interest_interval' => $package->interest_interval,
            'min_duration' => $package->min_duration,
            'max_duration' => $package->max_duration,
            'created_at' => $now,
            'updated_at' => $now,
            'expires_at' => ($now->copy()->addDays($package->max_duration)),
            'status' => Portfolio::S_ACTIVE,
        ]);
        $transactions = TransactionManager::logPortfolioDebit($portfolio, $amount, $local_amount, "New Stake (#{$portfolio->uuid}) of ".to_currency($amount, $base_currency->symbol));
        NotificationManager::sendTransactionNotices($transactions);
        Carbon::setTestNow($real_now);

        return $portfolio;
    }
}
