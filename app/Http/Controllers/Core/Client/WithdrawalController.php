<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Managers\WithdrawalManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class WithdrawalController
 * @package App\Http\Controllers\Core\Member
 */
class WithdrawalController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|Application|View
     */
    public function create(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        /*
        $withdrawal = $user->withdrawals()->whereIn('status', [Withdrawal::S_PENDING, Withdrawal::S_APPROVED])->first();
        if (is_object($withdrawal)) {
            session()->flash('message', [
                'status'  => 'info',
                'message' => "You currently have a pending/processing withdrawal request.<br/>To make a new withdrawal, cancel this active request.",
            ]);

            return $this->view($request, $withdrawal);
        }
        */

        /**
         * @var Collection $channels
         */
        $channels             = Channel::forWithdrawals()->orderByDesc('rank')->get();
        $min_amount           = $channels->pluck('min_amount')->min();
        $max_amount           = $channels->pluck('max_amount')->max();
        $defaultCurrency      = Currency::getDefault();
        $withdrawals_allowed  = $user->getSetting(Setting::KEY_ALLOW_WITHDRAWALS);
        $withdrawal_interval  = (int)$user->getSetting(Setting::KEY_WITHDRAWAL_INTERVAL);
        $withdrawal_limit     = $user->getSetting(Setting::KEY_WITHDRAWAL_LIMIT);
        $last_paid_withdrawal = $user->withdrawals()->where('status', Withdrawal::S_PAID_OUT)->orderByDesc('created_at')->first();
        $next_withdrawal_date = is_object($last_paid_withdrawal) ? Carbon::parse($last_paid_withdrawal->created_at)->addDays($withdrawal_interval) : Carbon::today();

        return view('core-client.withdrawal.create', [
            'channels'             => $channels,
            'accounts'             => $user->accounts()->orderByDesc('balance')->get(),
            'min_amount'           => $min_amount,
            'max_amount'           => min($max_amount, $withdrawal_limit),
            'withdrawals_allowed'  => $withdrawals_allowed,
            'withdrawal_interval'  => $withdrawal_interval,
            'withdrawal_limit'     => to_currency($withdrawal_limit, $defaultCurrency->symbol, $defaultCurrency->minor_unit),
            'next_withdrawal_date' => $next_withdrawal_date,
            'can_withdraw_atm'     => ($withdrawals_allowed && (!is_object($next_withdrawal_date) || Carbon::now()->greaterThan($next_withdrawal_date))),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        /**
         * @var User $user
         */
        $user = $request->user();

        abort_unless($user->getSetting(Setting::KEY_ALLOW_WITHDRAWALS), '401', "Action Not Allowed");

        $input = $request->validate([
            'account_id' => 'required|in:'.$user->accounts()->pluck('id')->implode(','),
            'wallet_address' => 'required|string',
        ]);
        $account = $user->accounts()->where('id', $input['account_id'])->first();
        $input = array_merge($input, $request->validate([
            'amount'  => 'required|numeric|max:' . $account->balance,
        ], [
            'amount.max' => 'Insufficient Balance!'
        ]));

        $withdrawal_interval  = (int)$user->getSetting(Setting::KEY_WITHDRAWAL_INTERVAL);
        $withdrawal_limit     = (double)$user->getSetting(Setting::KEY_WITHDRAWAL_LIMIT);
        $last_paid_withdrawal = $user->withdrawals()->where('status', Withdrawal::S_PAID_OUT)->orderByDesc('created_at')->first();
        $next_withdrawal_date = is_object($last_paid_withdrawal) ? $last_paid_withdrawal->created_at->copy()->addDays($withdrawal_interval) : Carbon::today();

        abort_if($input['amount'] > $withdrawal_limit || Carbon::now()->lessThanOrEqualTo($next_withdrawal_date), 403, "Not Allowed!");

        $amount = $input['amount'];
        $wallet_address = $input['wallet_address'] ?? $account->wallet_address;

        DB::beginTransaction();
        $withdrawal = WithdrawalManager::create($account, $amount, $wallet_address);
        $transactions = TransactionManager::logWithdrawal($withdrawal);
        NotificationManager::sendTransactionNotices($transactions);
        NotificationManager::sendWithdrawalRequestNotice($withdrawal);
        DB::commit();

        return [
            'status'   => true,
            'message'  => 'New send request queued.',
            'redirect' => route('core.client.withdrawal.manage'),
        ];
    }


    /**
     * @param Request $request
     * @param Withdrawal $withdrawal
     * @return Factory|View
     */
    public function view(Request $request, Withdrawal $withdrawal)
    {
        $user = $request->user();
        abort_unless($user->is($withdrawal->account->user), 404);
        $default_currency_symbol = Currency::getDefault()->alpha_code;
        $local_currency_symbol   = $withdrawal->account->currency->alpha_code;

        return view('core-client.withdrawal.view', [
            'withdrawal' => $withdrawal,
            'channel'    => $withdrawal->channel,
            'show_local' => ($default_currency_symbol != $local_currency_symbol)
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
        $user        = $request->user();
        $withdrawals = $user->withdrawals()->orderByDesc('created_at')->paginate();

        return view('core-client.withdrawal.manage', [
            'withdrawals' => $withdrawals,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $valid_actions = ['cancel'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_withdrawals,id',
        ]);

        $input        = $request->input();
        $items        = Withdrawal::whereIn('id', $input['ids']);
        $affected     = 0;
        $transactions = [];

        switch ($input['action']) {
            case 'cancel':
                /**
                 * @var Withdrawal $withdrawal
                 */
                foreach ($items->get() as $withdrawal) {
                    if ($withdrawal->isCancellable()) {
                        $withdrawal->update(['status' => Withdrawal::S_CANCELED]);
                        $message      = "Withdrawal Request #{$withdrawal->uuid} Cancelled";
                        $transactions += TransactionManager::logWithdrawalReversal($withdrawal, $message);
                        $affected++;
                    }
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
