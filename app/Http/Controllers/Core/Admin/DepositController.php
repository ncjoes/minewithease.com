<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\Core\Deposit;
use App\Models\ETC\Currency;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class DepositController
 * @package App\Http\Controllers\Core\Admin
 */
class DepositController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        $status   = $request->query('status', '*');
        $per_page = $request->query('per_page', 30);
        $channel  = Channel::find($request->get('channel'));

        $channels = Channel::forDeposits()->get();
        $results  = $status == '*' ? Deposit::where('id', '<>', null) : Deposit::findByStatus($status);
        $results  = is_object($channel) ? $results->where('channel_id', $channel->id) : $results;
        $results  = strlen((string)$search) ? $results->where('uuid', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Deposit::count();
        $result_count = $results->count();
        $results      = $results->orderByDesc('created_at')->paginate($per_page);

        return view('core-admin.deposit.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'channels'     => $channels,
            'statuses'     => [
                '*'                 => '',
                Deposit::S_PENDING  => 'Pending',
                Deposit::S_PAID_IN  => 'Payment Claimed',
                Deposit::S_VERIFIED => 'Verified',
                Deposit::S_CANCELED => 'Canceled',
                Deposit::S_REVERSED => 'Reversed'
            ],
            'filter'       => [
                'search'   => $search,
                'status'   => $status,
                'channel'  => is_object($channel) ? $channel->id : null,
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $this->validate($request, [
            'action' => 'required|in:verify,reverse,hard_reverse,cancel',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_deposits,id',
        ], [
            'id.required' => 'No request selected.',
            'id.*.exists' => 'Invalid Invoice ID',
        ]);

        $input = $request->input();

        $data    = [];
        $status  = true;
        $mode    = 'success';
        $message = 'Undefined!';
        DB::beginTransaction();
        switch ($input['action']) {
            case 'verify':
                {
                    $request->validate([
                        'trans_hash'   => 'required|array|min:1',
                        'trans_hash.*' => 'required|string|min:16',
                    ], [
                        'trans_hash.*.required' => 'The Transaction Hash is Required'
                    ]);
                    $affected = $this->markAsVerified($input['ids'], $input['trans_hash']);
                    $message  = $affected . ' deposits verified and credited.';
                }
                break;
            case 'reverse':
                {
                    $R       = $this->reverseVerified($input['ids']);
                    $mode    = $R['mode'];
                    $status  = $R['status'];
                    $message = $R['message'];
                }
                break;
            case 'hard_reverse':
                {
                    $affected = $this->hardReverseVerified($input['ids']);
                    $message  = $affected . ' deposits hard-reversed.';
                }
                break;
            case 'cancel':
                $affected = $this->cancelUnverified($input['ids']);
                $message  = $affected . ' deposits canceled.';
                break;
        }
        DB::commit();

        return [
            'mode'     => $mode,
            'message'  => $message,
            'redirect' => $status ? redirect()->back()->getTargetUrl() : null,
            'data'     => $data,
        ];
    }

    /**
     * @param array $ids
     * @param array $trans_refs
     * @return int
     */
    protected function markAsVerified(array $ids, array $trans_refs): int
    {
        $now          = Carbon::now();
        $deposits     = Deposit::findByStatus([Deposit::S_PENDING, Deposit::S_PAID_IN, Deposit::S_CANCELED, Deposit::S_REVERSED,])->whereIn('id', $ids)->get();
        $transactions = [];
        /**
         * @var Deposit $deposit
         */
        foreach ($deposits as $deposit) {
            $deposit->update(['verified_at' => $now, 'trans_ref' => $trans_refs[$deposit->id], 'status' => Deposit::S_VERIFIED]);
            $user = $deposit->user;
            if ($user->isOnTrial()) {
                $user->portfolios()->delete();
                $user->transactions()->delete();
                $user->update(['status' => User::S_ACTIVATED]);
            }
            $transactions += TransactionManager::logDeposit($deposit, $deposit->user);
        }
        NotificationManager::sendTransactionNotices($transactions);

        return count($transactions);
    }

    /**
     * @param array $ids
     * @return array
     */
    protected function reverseVerified(array $ids): array
    {
        $mode     = 'success';
        $success  = 0;
        $status   = true;
        $failures = [];
        $message  = [];

        $now          = Carbon::now();
        $deposits     = Deposit::findByStatus([Deposit::S_VERIFIED])->whereIn('id', $ids)->get();
        $transactions = [];
        /**
         * @var Deposit $deposit
         */
        foreach ($deposits as $deposit) {
            /**
             * @var User $depositor
             */
            $depositor = $deposit->user;
            if ($deposit->amount <= $depositor->getBalance()) {
                $deposit->update(['verified_at' => $now, 'status' => Deposit::S_REVERSED]);
                $transactions += TransactionManager::logDepositReversal($deposit, $depositor);
                $success++;
            } else {
                $failures[] = $deposit->uuid;
            }
        }
        NotificationManager::sendTransactionNotices($transactions);

        if ($success > 0) {
            $message[] = $success . " deposits reversed and debited!";
        }
        if (count($failures) > 0) {
            $message[] = "The following could not be reversed due to insufficient balance";
            $message   += $failures;
            $mode      = 'warning';
            $status    = false;
        }

        return ['message' => implode('<br/>', $message), 'mode' => $mode, 'status' => $status];
    }

    /**
     * @param array $ids
     * @return int
     */
    protected function hardReverseVerified(array $ids): int
    {
        $now          = Carbon::now();
        $deposits     = Deposit::findByStatus([Deposit::S_VERIFIED])->whereIn('id', $ids)->get();
        $transactions = [];
        /**
         * @var Deposit $deposit
         */
        foreach ($deposits as $deposit) {
            $deposit->update(['verified_at' => $now, 'status' => Deposit::S_REVERSED]);
            $transactions += TransactionManager::logDepositReversal($deposit, $deposit->user);
        }
        NotificationManager::sendTransactionNotices($transactions);

        return count($transactions);
    }

    protected function cancelUnverified(array $ids): int
    {
        return Deposit::findByStatus([Deposit::S_PENDING, Deposit::S_PAID_IN])->whereIn('id', $ids)->update(['status' => Deposit::S_CANCELED]);
    }

    /**
     * @param Request $request
     * @param Deposit $deposit
     * @return Factory|View
     */
    public function view(Request $request, Deposit $deposit)
    {
        $default_currency_symbol = Currency::getDefault()->symbol;
        $local_currency_symbol   = $deposit->account->currency->symbol;

        return view('core-admin.deposit.view', [
            'deposit'    => $deposit,
            'channel'    => $deposit->channel,
            'LCS'        => $local_currency_symbol,
            'show_local' => ($default_currency_symbol != $local_currency_symbol)
        ]);
    }

}
