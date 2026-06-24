<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Core\Bonus;
use App\Models\Core\Channel;
use App\Models\Core\Deposit;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Core\Admin
 */
class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function show(Request $request)
    {
        $stats = [
            'user'       => [
                'grand_total'   => User::count(),
                'active_total'  => ($active_users = User::findByStatus(User::S_ACTIVATED))->count(),
                'total_balance' => $active_users->get()->sum(function (User $user) {
                    return $user->getTotalBalance();
                }),
            ],
            'deposit'    => [
                'verified_count'  => ($verified_deposits = Deposit::findByStatus(Deposit::S_VERIFIED))->count(),
                'verified_amount' => $verified_deposits->sum('amount'),
                'pending_count'   => ($pending_deposits = Deposit::findByStatus([Deposit::S_PENDING, Deposit::S_PAID_IN]))->count(),
                'pending_amount'  => $pending_deposits->sum('amount'),
            ],
            'portfolio'  => [
                'net_count'     => ($all_portfolios = Portfolio::all())->count(),
                'net_amount'    => $all_portfolios->sum('amount'),
                'active_count'  => ($active_portfolio = Portfolio::findByStatus(Portfolio::S_ACTIVE))->count(),
                'active_amount' => $active_portfolio->sum('amount'),
            ],
            'bonus'      => [
                'net_count'       => ($all_bonuses = Bonus::all())->count(),
                'net_amount'      => $all_bonuses->sum('amount'),
                'released_count'  => ($released_bonuses = Bonus::findByStatus(Bonus::S_RELEASED))->count(),
                'released_amount' => $released_bonuses->sum('amount'),
            ],
            'withdrawal' => [
                'paid_count'     => ($paid_withdrawals = Withdrawal::findByStatus(Withdrawal::S_PAID_OUT))->count(),
                'paid_amount'    => $paid_withdrawals->sum('amount'),
                'pending_count'  => ($pending_withdrawals = Withdrawal::findByStatus([Withdrawal::S_PENDING, Withdrawal::S_APPROVED,]))->count(),
                'pending_amount' => $pending_withdrawals->sum('amount'),
            ],
            'currencies' => Currency::activeOnly()->count(),
            'countries'  => Country::activeOnly()->count(),
            'channels'   => Channel::activeOnly()->count(),
            'packages'   => Package::activeOnly()->count(),
        ];

        return view('core-admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}
