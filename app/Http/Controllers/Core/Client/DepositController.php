<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\DepositManager;
use App\Managers\NotificationManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\Core\Deposit;
use App\Models\ETC\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class DepositController
 * @package App\Http\Controllers\Core\Member
 */
class DepositController extends Controller
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
         * @var Deposit $deposit
         */
        $deposit = $user->deposits()->where('status', Deposit::S_PENDING)->first();
        if (is_object($deposit)) {
            session()->flash('message', [
                'status'  => 'info',
                'message' => "You have an unpaid deposit invoice."
                    . "<br/>To make a new deposit, please cancel this invoice",
            ]);

            return $this->view($request, $deposit);
        }

        $currency_ids = $user->currencies()->pluck('etc_currencies.id')->all();
        if (is_object($default_currency = Currency::getDefault())) {
            $currency_ids[] = $default_currency->id;
        }

        /**
         * @var Collection $channels
         */
        //$channels   = Channel::forDeposits()->whereIn('currency_id', $currency_ids)->orderByDesc('rank')->get();
        $channels   = Channel::forDeposits()->orderByDesc('rank')->get();
        $min_amount = $channels->pluck('min_amount')->min();
        $max_amount = $channels->pluck('max_amount')->max();

        return view('core-client.deposit.create', [
            'channels'   => $channels,
            'min_amount' => $min_amount,
            'max_amount' => $max_amount,
        ]);
    }

    /**
     * @param Request $request
     * @param Deposit $deposit
     * @return Factory|View
     */
    public function view(Request $request, Deposit $deposit)
    {
        $user = $request->user();
        abort_unless($user->is($deposit->account->user), 404);

        $default_currency_symbol = Currency::getDefault()->symbol;
        $local_currency_symbol   = $deposit->account->currency->symbol;

        $account                 = $deposit->account;
        $channel                 = $account->channel();
        $channel_currency        = $channel->currency;

        $payment_link = $channel_currency->alpha_code . ':' . $channel->payment_wallet . '?amount=' . $deposit->local_amount .
            '&message=Deposit+to+' . config('app.domain') . '+User+' . $user->uuid;

        return view('core-client.deposit.view', [
            'deposit'      => $deposit,
            'channel'      => $channel,
            'LCS'          => $local_currency_symbol,
            'currency'     => $channel_currency,
            'payment_link' => $payment_link,
            'show_local'   => ($default_currency_symbol != $local_currency_symbol)
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
        $user     = $request->user();
        $deposits = $user->deposits()->orderByDesc('created_at')->paginate();

        return view('core-client.deposit.manage', [
            'deposits' => $deposits,
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

        if (!is_object($deposit = $user->deposits()->where('status', Deposit::S_PENDING)->first())) {
            $input = $request->validate([
                'account_id' => 'required|in:' . $user->accounts()->pluck('id')->implode(','),
                'amount'     => 'required|numeric',
            ]);

            $amount = $input['amount'];
            $account = $user->accounts()->find($input['account_id']);

            DB::beginTransaction();
            $deposit = DepositManager::create($account, $amount);
            DB::commit();
        }

        return [
            'status'   => true,
            'mode'     => 'info',
            'method'   => 'other',
            'message'  => 'Invoice created. Proceed to make payment',
            'redirect' => $deposit->url,
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $valid_actions = ['cancel', 'claim_pay'];
        $input         = $request->validate([
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_deposits,id',
        ]);

        $items    = Deposit::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'cancel':
                $affected = $items->where('status', Deposit::S_PENDING)->update(['status' => Deposit::S_CANCELED]);
                break;

            case 'claim_pay':
                $input_2 = $request->validate([
                    'trans_hash'   => 'required|array|min:1',
                    'trans_hash.*' => 'required|string',
                ], [
                    'trans_hash.*.required' => 'The Transaction Hash is Required'
                ]);
                /**
                 * @var Deposit $deposit
                 */
                foreach ($items->whereIn('status', [Deposit::S_PENDING, Deposit::S_PAID_IN])->get() as $deposit) {
                    $deposit->update(['status' => Deposit::S_PAID_IN, 'trans_ref' => $input_2['trans_hash'][$deposit->id]]);
                    NotificationManager::sendPaymentClaimNotice($deposit);
                    $affected++;
                }
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => route('core.client.deposit.manage'),
        ];
    }
}
