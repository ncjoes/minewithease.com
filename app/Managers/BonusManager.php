<?php
declare(strict_types=1);

namespace App\Managers;

use App\Models\Auth\User;
use App\Models\Core\Bonus;
use App\Models\Core\Portfolio;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Traits\Controllers\ResolveExchangeRate;
use Carbon\Carbon;

/**
 * Class BonusManager
 * @package App\Managers
 */
abstract class BonusManager
{
    use ResolveExchangeRate;
    
    /**
     * @param Portfolio $portfolio
     */
    public static function assignBonusOnPortfolio(Portfolio $portfolio): array
    {
        $base_currency = $portfolio->currency;
        $package       = $portfolio->package;
        $account       = $portfolio->account;
        $user          = $account->user;
        $level            = 0;
        $bonuses = [];

        while (is_object($referrer = $user->referrer)) {
            $user_bonuses_count = $referrer->bonuses()->where(['item_id' => $account->user->id, 'item_type' => User::morphKey()])->count();
            if ($package->referral_bonus_count == -1 or $package->referral_bonus_count > $user_bonuses_count) {

                $referrals_allowed = $referrer->getSetting(Setting::KEY_ALLOW_REFERRALS);
                $rate              = ($referrer->isSpecialAffiliate() ? $referrer->getAffiliateRate($level) : $package->getReferralBonusRate($level)) / 100;

                if ($referrals_allowed && $rate > 0) {
                    $amount           = round($portfolio->amount * $rate, $base_currency->minor_unit);
                    $due_from         = Carbon::now()->addHours($package->referral_bonus_release_time);
                    $require_approval = $referrer->getSetting(Setting::KEY_REQUIRE_BONUS_APPROVAL, false);

                    $referrers_account = $referrer->accounts()->where('currency_id', $account->currency_id)->first();
                    $local_amount = self::getExchangeValue($base_currency->alpha_code, $account->currency->alpha_code, $amount, $account->currency->minor_unit);

                    $bonuses[] = Bonus::create([
                        'currency_id' => $base_currency->id,
                        'account_id'  => $referrers_account->id,
                        'amount'      => $amount,
                        'local_amount'=> $local_amount,
                        'description' => "Level-" . ($level + 1) . " Referral Bonus on Portfolio (#{$portfolio->uuid}) of {$portfolio->amount()} by {$account->user->name}",
                        'item_id'     => $account->user->id,
                        'item_type'   => User::morphKey(),
                        'due_from'    => $due_from,
                        'status'      => $require_approval ? Bonus::S_PENDING : Bonus::S_APPROVED,
                    ]);
                }
            }

            $level++;
            $user = $referrer;
        }

        return $bonuses;
    }

    /**
     * @return array
     */
    public static function releaseDueBonuses(): array
    {
        $transactions = [];
        $now          = Carbon::now();
        $bonuses      = Bonus::findByStatus([Bonus::S_APPROVED])->where('due_from', '<', $now)->get();
        /**
         * @var Bonus $bonus
         */
        foreach ($bonuses as $bonus) {
            $bonus->update(['status' => Bonus::S_RELEASED]);
            $transactions[] = TransactionManager::logBonus($bonus);
        }

        return $transactions;
    }
}
