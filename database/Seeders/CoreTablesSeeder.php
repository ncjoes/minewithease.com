<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Managers\BonusManager;
use App\Managers\DepositManager;
use App\Managers\NotificationManager;
use App\Managers\PortfolioManager;
use App\Managers\TransactionManager;
use App\Managers\WithdrawalManager;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\Core\Channel;
use App\Models\Core\Connection;
use App\Models\Core\Deposit;
use App\Models\Core\Package;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class CoreTablesSeeder
 */
class CoreTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed_period = 10;
        $real_now    = Carbon::now();

        if (app()->isLocal()) {
            Carbon::setTestNow($real_now->copy()->subDays($seed_period));
        }

        DB::beginTransaction();

        /**
         * @var Role $member_role
         */
        $member_role = Role::create([
            'name'         => Role::CLIENT,
            'display_name' => 'Client',
            'description'  => "Client",
        ]);
        $admin_role  = Role::findByName(Role::ADMIN);
        $member_role->users()->attach($admin_role->users);


        $crypto_currencies = Currency::activeOnly()->where('is_crypto', true)->get();

        foreach($crypto_currencies as $currency) {
            Channel::factory()->create(['is_active' => true, 'name' => $currency->name, 'currency_id' => $currency->id]);
        }

        self::setupNewPackages();

        DB::commit();

        if (app()->isLocal()) {
            $now = Carbon::now();
            /**
             * @var Collection $countries
             */
            $countries   = Country::activeOnly()->get();
            $client_role = Role::findByName(Role::CLIENT);
            $daily_user_count = (int)(100 / $seed_period);

            while ($real_now->greaterThan($now)) {

                //==================USERS & Accounts=======================================//
                DB::transaction(function()use($daily_user_count, $countries, $client_role, $crypto_currencies) {

                    $earlier_users = User::all();

                    $users = User::factory($daily_user_count)->create([
                                'country_id'       => $countries->random()->id,
                                'account_settings' => User::defaultSettings(),
                            ])->each(function (User $user) use ($countries, $earlier_users, $crypto_currencies) {
                                $referrer = ($earlier_users->count() and is_object($r = $earlier_users->random())) ? $r->id : null;
                                $user->update([
                                    'country_id' => $countries->random()->id,
                                    'referrer_id' => $referrer,
                                ]);
                                $earlier_users->add($user);

                                foreach($crypto_currencies as $currency) {
                                    Account::factory()->create([
                                        'user_id'     => $user->id,
                                        'currency_id' => $currency->id,
                                        'is_active'   => true,
                                    ]);
                                }
                            });

                    $client_role->users()->attach($users);

                });


                //==================Connect Wallets=======================================//
                DB::transaction(function() use ($countries) {
                    $connections = Connection::factory(100)->create();
                });


                //==================DEPOSITS=======================================//
                DB::transaction(function()use($countries, $now){

                    $users = User::all();

                    foreach ($users as $user) {
                        $account = $user->accounts->random();

                        $currency = $account->currency;
                        $channels = $currency->depositChannels();

                        $channel = $channels->get()->random();
                        $amount  = Arr::random(range($channel->min_amount, $channel->max_amount, $channel->split_amount));

                        $deposit = DepositManager::create($account, $amount);
                        $deposit->update(['status' => Deposit::S_VERIFIED, 'verified_at' => $now]);
                        $transactions = TransactionManager::logDeposit($deposit);
                        NotificationManager::sendTransactionNotices($transactions);
                    }


                });

                //==================PORTFOLIOS=======================================//
                DB::transaction(function () use ($now) {
                    $packages = Package::orderByDesc('min_amount')->get();
                    $mpa      = min($packages->pluck('min_amount')->all());
                    $users    = User::whereIn('id', function($query) use ($mpa) {
                        $query->select('user_id')->from('core_accounts')->where('balance', '>', $mpa);
                    })->get();

                    foreach ($users->random((int)(count($users) * 0.50)) as $user) {
                        $package = $packages->random();
                        $viable_accounts = $user->accounts()->where('balance', '>=', ($package->min_amount + $package->split_amount))->get();
                        if($viable_accounts->count()==0) {
                            continue;
                        }
                        $account = $viable_accounts->random();
                        $amount    = Arr::random(range($package->min_amount, $account->balance, $package->split_amount));

                        $portfolio = PortfolioManager::create($package, $account, $amount, $now);
                        if (is_object($referrer = $user->referrer)) {
                            BonusManager::assignBonusOnPortfolio($portfolio, $referrer);
                        }
                    }

                    $transactions = [];
                    $transactions += PortfolioManager::updateStates();
                    $transactions += PortfolioManager::assignInterests();
                    $transactions += BonusManager::releaseDueBonuses();
                    NotificationManager::sendTransactionNotices($transactions);
                });

                //==================WITHDRAWALS=======================================//
                DB::transaction(function () use ($now) {
                    $transactions = [];

                    $mwa      = min(Channel::pluck('min_amount')->all());
                    $users    = User::whereIn('id', function($query) use ($mwa) {
                        $query->select('user_id')->from('core_accounts')->where('balance', '>', $mwa);
                    })->get();

                    foreach ($users->random((int)(count($users) * 0.25)) as $user) {

                        $account = $user->accounts()->where('balance', '>', $mwa)->get()->random(); 
                        $channel     = $account->currency->withdrawalChannels()->get()->random();
                        $base_amount = Arr::random(range($channel->min_amount, $account->balance, $channel->split_amount));
                        $withdrawal  = WithdrawalManager::create($account, $base_amount, $account->wallet_address);
                        $transactions[] = TransactionManager::logWithdrawal($withdrawal);
                        $withdrawal->update(['status' => Withdrawal::S_PAID_OUT, 'processed_at' => $now]);
                    }

                    NotificationManager::sendTransactionNotices($transactions);
                });

                $now = $now->addDay();
                Carbon::setTestNow($now);
            }
        }
    }

    public static function setupNewPackages(): string
    {
        /*
        (1, 'Basic plan', '1% daily profit', '1_ax.png', 1, 1, 30, 30, 3000, 10999, 1, '5', 1, 0, 3, 1, '2022-04-14 20:11:52', '2025-05-01 15:14:46'),
        (2, 'Gold  Plan', '2.5% Daily Profit', NULL, 2.5, 1, 30, 30, 50000, 149999, 1, '15', 1, 0, 10, 1, '2022-04-14 20:11:52', '2024-06-17 23:47:29'),
        (3, 'Platinum Plan', '3% Daily Profit', '3_zg.png', 3, 1, 30, 30, 150000, 2000000, 1, '20', 1, 0, 15, 1, '2022-04-14 20:11:52', '2025-05-06 07:49:53'),
        (4, 'Bronze Plan', '1.5% Daily Profit', '4_gt.png', 1.5, 1, 30, 30, 11100, 49999, 1, '10', 1, 0, 5, 1, '2022-04-14 20:11:52', '2024-06-17 23:47:41'),
        (5, 'NFP-1', '200% interest after 90 days', NULL, 200, 90, 90, 90, 50000, 100000, 1, '5', 1, 0, 0, 1, '2023-07-04 12:15:55', '2025-05-31 05:48:44'),
        (6, 'NFP-2', '340% interest after 90 days', NULL, 340, 90, 90, 90, 110000, 200000, 1, '5', 1, 0, 0, 1, '2023-07-04 12:21:17', '2025-05-31 05:48:44'),
        (7, 'NFP-3', '420% interest after 90 days', NULL, 420, 90, 90, 90, 210000, 300000, 1, '5', 1, 0, 0, 1, '2023-07-04 12:26:09', '2025-05-31 05:48:44'),
        (8, 'NFP-4', '500% interest after 90 days', NULL, 500, 90, 90, 90, 310000, 1000000, 1, '5', 1, 0, 0, 1, '2023-07-04 12:28:12', '2025-05-31 05:48:44');

        */

        Package::factory()->create([
            'name' => 'Basic plan',
            'description' => '1% daily profit',//30% monthly
            'min_interest_rate' => 1, // 10% EOIP
            'max_interest_rate' => 1, // 10% EOIP
            'interest_interval' => 1, //days
            'min_duration' => 30, //days
            'max_duration' => 30, //days
            'min_amount' => 3000, //$
            'max_amount' => 49999, //$
            'split_amount' => 1, //$
            'referral_bonus_rate' => '5', //%, %
            'referral_bonus_count' => 1,
            'referral_bonus_release_time' => 0, //hours
            'service_charge_rate' => 0, //%
            'is_active' => true,
        ]);

        Package::factory()->create([
            'name' => 'Gold  Plan',
            'description' => '2.5% Daily Profit',//
            'min_interest_rate' => 2.5, //
            'max_interest_rate' => 2.5, //
            'interest_interval' => 1, //days
            'min_duration' => 30, //days
            'max_duration' => 30, //days
            'min_amount' => 50000, //$
            'max_amount' => 149999, //$
            'split_amount' => 1, //$
            'referral_bonus_rate' => '15', //%, %
            'referral_bonus_count' => 1,
            'referral_bonus_release_time' => 0, //hours
            'service_charge_rate' => 0, //%
            'is_active' => true,
        ]);

        Package::factory()->create([
            'name' => 'Platinum Plan',
            'description' => '3% Daily Profit',//
            'min_interest_rate' => 3, //
            'max_interest_rate' => 3, //
            'interest_interval' => 1, //days
            'min_duration' => 30, //days
            'max_duration' => 30, //days
            'min_amount' => 150000, //$
            'max_amount' => 2000000, //$
            'split_amount' => 1, //$
            'referral_bonus_rate' => '20', //%, %
            'referral_bonus_count' => 1,
            'referral_bonus_release_time' => 0, //hours
            'service_charge_rate' => 0, //%
            'is_active' => true,
        ]);


        return "Two new packages created";
    }
}
