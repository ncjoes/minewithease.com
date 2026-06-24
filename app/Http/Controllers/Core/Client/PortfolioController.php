<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\BonusManager;
use App\Managers\NotificationManager;
use App\Managers\PortfolioManager;
use App\Models\Auth\User;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use App\Models\ETC\Setting;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PortfolioController
 * @package App\Http\Controllers\Core\Member
 */
class PortfolioController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        /**
         * @var Collection $packages
         */
        $packages        = Package::activeOnly()->get();
        $min_amount      = $packages->pluck('min_amount')->min();
        $max_amount      = $packages->pluck('max_amount')->min();
        $allow_investing = $user->getSetting(Setting::KEY_ALLOW_INVESTING);
        $accounts         = $user->accounts()->orderBy('balance', 'desc')->get();

        return view('core-client.portfolio.create', [
            'packages'        => $packages,
            'min_amount'      => $min_amount,
            'max_amount'      => $max_amount,
            'allow_investing' => $allow_investing,
            'accounts'        => $accounts,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        /**
         * @var User $user
         */
        $user       = $request->user();
        $portfolios = $user->portfolios()->paginate();

        return view('core-client.portfolio.manage', [
            'portfolios' => $portfolios,
        ]);
    }

    /**
     * @param Request $request
     * @param Portfolio $portfolio
     * @return Factory|View
     */
    public function view(Request $request, Portfolio $portfolio)
    {
        $user = $request->user();
        abort_unless($user->is($portfolio->user), 404);

        return view('core-client.portfolio.view', [
            'portfolio'    => $portfolio,
            'package'      => $portfolio->package,
            'transactions' => $portfolio->transactions,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request)
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        /**
         * @var User $user
         */
        $user = $request->user();

        abort_unless($user->getSetting(Setting::KEY_ALLOW_INVESTING), '401', "Action Not Allowed");

        $packages = Package::activeOnly();

        $input = $request->validate([
            'package' => 'required|in:' . implode(',', $packages->pluck('id')->all()),
            'amount'  => 'required|numeric',
            'account' => 'required|in:' . implode(',', $user->accounts()->pluck('id')->all()),
        ]);

        $package = Package::find($input['package']);
        $account  = $user->accounts()->find($input['account']);

        if ($input['amount'] < $package->min_amount || $input['amount'] > $package->max_amount) {
            return ['status' => false, 'message' => 'Invalid amount for the selected package'];
        } elseif ($input['amount'] > $account->balance) {
            return ['status' => false, 'message' => 'Insufficient balance in the selected account!'];
        }

        DB::beginTransaction();
        $portfolio = PortfolioManager::create($package, $account, $input['amount']);
        BonusManager::assignBonusOnPortfolio($portfolio);
        DB::commit();

        return [
            'status'   => true,
            'message' => 'New Stake activated successfully.',
            'redirect' => route('core.client.portfolio.manage'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request)
    {
        $valid_actions = ['close'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_portfolios,id',
        ]);

        $input        = $request->input();
        $items        = Portfolio::whereIn('id', $input['ids']);
        $affected     = 0;
        $transactions = [];

        switch ($input['action']) {
            case 'close':
                /**
                 * @var Portfolio $portfolio
                 */
                foreach ($items->get() as $portfolio) {
                    $transactions = array_merge($transactions, PortfolioManager::closeManually($portfolio));
                    $affected++;
                }
                NotificationManager::sendTransactionNotices($transactions);
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
