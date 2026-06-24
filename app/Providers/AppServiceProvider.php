<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CMS\Faq;
use App\Models\CMS\Page;
use App\Models\CMS\Post;
use App\Models\Core\Account;
use App\Models\Core\Bonus;
use App\Models\Core\Card;
use App\Models\Core\Channel;
use App\Models\Core\Connection;
use App\Models\Core\Deposit;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use App\Models\Core\Swap;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Continent;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use App\Models\ETC\District;
use App\Models\ETC\Region;
use App\Models\ETC\Setting;
use App\Notifications\NewTransaction;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);
        Config::set('app.domain', request()->getHttpHost());

        Relation::morphMap([
            'role' => Role::class,
            'permission' => Permission::class,
            'user' => User::class,
            'channel' => Channel::class,
            'deposit' => Deposit::class,
            'package' => Package::class,
            'portfolio' => Portfolio::class,
            'bonus' => Bonus::class,
            'transaction' => Transaction::class,
            'withdrawal' => Withdrawal::class,
            'account' => Account::class,
            'swap' => Swap::class,
            'card' => Card::class,
            'connection' => Connection::class,
            'continent' => Continent::class,
            'country' => Country::class,
            'currency' => Currency::class,
            'district' => District::class,
            'region' => Region::class,
            'setting' => Setting::class,
            'page' => Page::class,
            'post' => Post::class,
            'faq' => Faq::class,
            'notification.transaction' => NewTransaction::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
