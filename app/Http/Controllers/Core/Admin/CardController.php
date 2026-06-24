<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Models\Auth\User;
use App\Models\Core\Card;
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
 * Class CardController
 * @package App\Http\Controllers\Core\Admin
 */
class CardController extends Controller
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
        $channel  = Channel::find($request->input('channel', null),'id');

        $channels = Channel::forDeposits()->get();
        $results  = $status == '*' ? Card::whereNotNull('id') : Card::findByStatus($status);
        $results  = is_object($channel) ? $results->where('channel_id', $channel->id) : $results;
        $results  = strlen((string)$search) ? $results->where('uuid', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Card::query()->count('id');
        $result_count = $results->count('id');
        $results      = $results->orderByDesc('created_at')->paginate($per_page);

        return view('core-admin.card.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'channels'     => $channels,
            'statuses'     => [
                '*'                 => '',
                Card::S_PENDING  => 'Pending',
                Card::S_PAID_IN  => 'Payment Claim Submitted',
                Card::S_VERIFIED => 'Verified/Activated',
                //Card::S_CANCELED => 'Canceled',
                //Card::S_REVERSED => 'Reversed'
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
            'action' => 'required|in:verify,cancel',
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
                    $message  = $affected . ' cards verified and activated.';
                }
                break;
            case 'cancel':
                $affected = $this->cancelUnverified($input['ids']);
                $message  = $affected . ' cards canceled.';
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
        $now = Carbon::now();
        $cards = Card::findByStatus([Card::S_PENDING, Card::S_PAID_IN, /*Card::S_CANCELED, Card::S_REVERSED,*/])->whereIn('id', $ids)->get();
        $transactions = [];
        /**
         * @var Card $card
         */
        foreach ($cards as $card) {
            $card->update(['verified_at' => $now, 'payment_reference' => $trans_refs[$card->id], 'status' => Card::S_VERIFIED]);
            NotificationManager::sendCardActivationNotice($card);
        }

        return count($cards);
    }


    protected function cancelUnverified(array $ids): int
    {
        return Card::findByStatus([Card::S_PENDING, Card::S_PAID_IN])->whereIn('id', $ids)->update(['status' => Card::S_CANCELLED]);
    }

    /**
     * @param Request $request
     * @param Card $card
     * @return Factory|View
     */
    public function view(Request $request, Card $card)
    {
        $default_currency_symbol = Currency::getDefault()->symbol;
        $local_currency_symbol   = $card->channel->currency->symbol;

        return view('core-admin.card.view', [
            'card'    => $card,
            'channel'    => $card->channel,
            'LCS'        => $local_currency_symbol,
            'show_local' => ($default_currency_symbol != $local_currency_symbol)
        ]);
    }

}
