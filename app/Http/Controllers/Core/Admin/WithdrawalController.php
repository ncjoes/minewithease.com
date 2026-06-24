<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Currency;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class WithdrawalController
 * @package App\Http\Controllers\Core\Admin
 */
class WithdrawalController extends Controller
{
    /**
     * @param Request $request
     * @param Withdrawal $withdrawal
     * @return Factory|View
     */
    public function view(Request $request, Withdrawal $withdrawal)
    {
        $default_currency_symbol = Currency::getDefault()->alpha_code;
        $local_currency_symbol   = $withdrawal->account->currency->alpha_code;

        return view('core-admin.withdrawal.view', [
            'withdrawal' => $withdrawal,
            'show_local' => ($default_currency_symbol != $local_currency_symbol)
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        $status   = $request->get('status', '*');
        $per_page = $request->get('per_page', 30);
        $channel  = Channel::find($request->get('channel'));

        $channels = Channel::activeOnly()->get();
        $results  = $status == '*' ? Withdrawal::where('id', '<>', null) : Withdrawal::findByStatus($status);
        $results  = is_object($channel) ? $results->where('channel_id', $channel->id) : $results;
        $results  = strlen((string)$search) ? $results->where('uuid', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Withdrawal::count();
        $result_count = $results->count();
        $results      = $results->orderByDesc('created_at')->paginate($per_page);

        return view('core-admin.withdrawal.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'channels'     => $channels,
            'statuses'     => [
                '*'                    => '',
                Withdrawal::S_PENDING  => 'Pending',
                Withdrawal::S_APPROVED => 'Approved',
                Withdrawal::S_PAID_OUT => 'Paid',
                Withdrawal::S_CANCELED => 'Canceled',
                Withdrawal::S_DECLINED => 'Declined'
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
        $valid_actions = ['approve', 'update_progress', 'submit', 'mark_as_paid', 'mark_as_failed', 'disapprove', 'decline', 'retract'];
        $this->validate($request, [
            'action'                 => 'required|in:' . implode(',', $valid_actions),
            'ids'                    => 'required|array|min:1',
            'ids.*'                  => 'exists:core_withdrawals,id',
            'progress_value'         => 'required_if:action,update_progress|array',
            'progress_value.*'       => 'min:0|max:100',
            'progress_description'   => 'required_if:action,update_progress|array',
            'progress_description.*' => 'string',
        ], [
            'id.required' => 'No items selected.',
        ]);

        $input = $request->input();

        $data                          = [];
        $status                        = true;
        $message                       = 'Undefined!';
        $sendWithdrawalApprovalNotices = false;

        DB::beginTransaction();
        switch ($input['action']) {
            case 'approve':
                {
                    $affected = $this->approveRequests($input['ids']);
                    $message  = $affected . ' withdrawal requests approved';
                }
                break;
            case 'update_progress':
                {
                    $affected = $this->updateProgress($input);
                    $message  = $affected . ' withdrawal requests updated.';
                }
                break;
            case 'submit':
                {
                    //ToDo...
                }
                break;
            case 'mark_as_paid':
                {
                    $request->validate([
                        'trans_ref'        => 'required|array|min:1',
                        'payment_wallet'   => 'required|array|min:1',
                        'trans_ref.*'      => 'required|string',
                        'payment_wallet.*' => 'required|string',
                    ]);
                    $affected                      = $this->markAsPaid($input['ids'], $input['trans_ref'], $input['payment_wallet']);
                    $message                       = $affected . ' withdrawal requests settled.';
                    $sendWithdrawalApprovalNotices = true;
                }
                break;
            case 'disapprove':
                {
                    $affected = $this->disapproveRequests($input['ids']);
                    $message  = $affected . ' withdrawal requests disapproved';
                }
                break;
            case 'decline':
                {
                    $affected = $this->reverseRequests($input['ids'], Withdrawal::S_DECLINED);
                    $message  = $affected . ' withdrawal requests declined';
                }
                break;
            case 'mark_as_failed':
                {
                    $affected = $this->reverseRequests($input['ids'], Withdrawal::S_FAILED);
                    $message  = $affected . ' withdrawal reversed due to payment failure.';
                }
                break;
            case 'retract':
                {
                    $affected = $this->retractFunds($input['ids']);
                    $message  = $affected . ' withdrawal requests cancelled and retracted.';
                }
                break;
        }
        DB::commit();

        if ($sendWithdrawalApprovalNotices) {
            $withdrawals = Withdrawal::whereIn('id', $input['ids'])->where('status', Withdrawal::S_PAID_OUT)->get();
            foreach ($withdrawals as $withdrawal) {
                NotificationManager::sendWithdrawalPaidNotice($withdrawal);
            }
        }

        return [
            'status'   => $status,
            'message'  => $message,
            'redirect' => $status ? redirect()->back()->getTargetUrl() : null,
            'data'     => $data,
        ];
    }

    /**
     * @param array $ids
     * @return bool
     */
    protected function approveRequests(array $ids)
    {
        $now = Carbon::now();

        $withdrawals = Withdrawal::whereIn('id', $ids)->whereIn('status', [
            Withdrawal::S_PENDING,
            Withdrawal::S_FAILED,
        ]);

        return $withdrawals->update([
            'processed_at' => $now,
            'status'       => Withdrawal::S_APPROVED,
        ]);
    }

    /**
     * @param array $input
     * @return bool
     */
    protected function updateProgress(array $input)
    {
        $ids    = $input['ids'];
        $status = 0;

        $withdrawals = Withdrawal::whereIn('id', $ids)->whereIn('status', [Withdrawal::S_APPROVED,])->get();
        if (array_key_exists('progress_value', $input) and array_key_exists('progress_description', $input)) {
            foreach ($withdrawals as $withdrawal) {
                $status = $withdrawal->update([
                    'progress_value'       => $input['progress_value'][$withdrawal->id],
                    'progress_description' => $input['progress_description'][$withdrawal->id],
                ]);
            }
        }

        return $status;
    }

    /**
     * @param array $ids
     * @param array $trans_refs
     * @param array $payment_wallet
     * @return bool
     */
    protected function markAsPaid(array $ids, array $trans_refs, array $payment_wallet)
    {
        $now         = Carbon::now();
        $withdrawals = Withdrawal::whereIn('id', $ids)->where('status', Withdrawal::S_APPROVED);

        /**
         * @var Withdrawal $withdrawal
         */
        foreach ($withdrawals->get() as $withdrawal) {
            $withdrawal->update(['trans_ref' => $trans_refs[$withdrawal->id], 'payment_wallet' => $payment_wallet[$withdrawal->id]]);
        }
        $status = $withdrawals->update([
            'processed_at' => $now,
            'status'       => Withdrawal::S_PAID_OUT,
        ]);

        return $status;
    }

    /**
     * @param array $ids
     * @return mixed
     */
    protected function disapproveRequests(array $ids)
    {
        $status = Withdrawal::whereIn('id', $ids)->whereIn('status', [Withdrawal::S_APPROVED])->update([
            'status' => Withdrawal::S_PENDING,
        ]);

        return $status;
    }

    /**
     * @param array $ids
     * @param $reason
     * @return mixed
     */
    protected function reverseRequests(array $ids, $reason)
    {
        $now   = Carbon::now();
        $query = Withdrawal::whereIn('id', $ids)->whereIn('status', [Withdrawal::S_PENDING, Withdrawal::S_APPROVED]);

        $transactions = [];
        foreach ($query->get() as $withdrawal) {
            $message      = $reason == Withdrawal::S_DECLINED ? "Withdrawal Request Declined and Refunded" : "Payment Failed; Funds Reversed";
            $transactions += TransactionManager::logWithdrawalReversal($withdrawal, $message);
        }
        $affected = $query->update([
            'processed_at' => $now,
            'status'       => $reason,
        ]);
        NotificationManager::sendTransactionNotices($transactions);

        return $affected;
    }

    /**
     * @param array $ids
     * @return mixed
     */
    protected function retractFunds(array $ids)
    {
        $status = Withdrawal::whereIn('id', $ids)->whereIn('status', [
            Withdrawal::S_PENDING,
            Withdrawal::S_APPROVED,
        ])->update([
            'status' => Withdrawal::S_CANCELED,
        ]);

        return $status;
    }

    /**
     * @param Collection $withdrawals
     * @param User $user
     * @return int
     */
    protected function submitRequestsForSettlement(Collection $withdrawals, User $user)
    {
        $affected      = [];
        $failed        = [];
        $submitted_sum = 0.0;
        /**
         * @var Withdrawal $withdrawal
         */
        foreach ($withdrawals as $withdrawal) {
            /*
            $S = WithdrawalManager::submitCashoutForTransfer($withdrawal);
            if ($S['status']) {
                $affected[$withdrawal->id] = $S;
                $submittedSum += $withdrawal->amount;
            } else $failed[$withdrawal->id] = $S;
            */
            continue;
        }

        /*
        CashoutBatch::create([
            'submitted_by'         => $user->id,
            'net_approved_amount'  => $withdrawals->sum('amount'),
            'net_submitted_amount' => $submittedSum,
            'cashouts'             => [
                'success' => $affected,
                'failed'  => $failed,
            ],
        ]);
        */

        return count($affected);
    }
}
