<?php

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Models\Core\Card;
use App\Models\Core\Channel;
use App\Models\ETC\Currency;
use App\Traits\Controllers\ResolveExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class CardController extends Controller
{
    use ResolveExchangeRate;

    public function create(Request $request)
    {
        $user = $request->user();
        $channels   = Channel::forDeposits()->orderByDesc('rank')->get();

        return view('core-client.card.create',[
            'user' => $user,
            'channels' => $channels,
            'pageTitle' => 'Order Web3 Card',
            'min_amount' => $channels->pluck('min_amount')->min(),
            'max_amount' => $channels->pluck('max_amount')->max(),
        ]);
    }

    public function doCreate(Request $request)
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $input = $request->validate([
            'channel' => 'required|exists:core_channels,id',
            'amount' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $channel = Channel::forDeposits()->where('id', $input['channel'])->firstOrFail();

        if ($input['amount'] < $channel->min_amount || $input['amount'] > $channel->max_amount) {
            return [
                'status' => 'error',
                'message' => "Amount must be between {$channel->min_amount} and {$channel->max_amount}"
            ];
        }

        // Create a new card order
        $cardOrder = $user->cards()->create([
            'uuid' => Card::generateUUID($user->uuid),
            'channel_id' => $channel->id,
            'amount' => $input['amount'],
            'local_amount' => self::getExchangeValue('USD', $channel->currency->alpha_code, $input['amount']),
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'status' => Card::S_PENDING, // pending
        ]);

        return [
            'mode' => 'info',
            'message' => "Your card order has been added to cart. Proceed to make payment.",
            'redirect' => route('core.client.card.view-invoice', ['card' => $cardOrder])
        ];
    }

    public function viewInvoice(Request $request, Card $card)
    {
        $user = $request->user();
        abort_unless($card->user_id == $user->id, 403, Lang::get('http-errors.403'));

        $channel = $card->channel;
        $channel_currency = $channel->currency;

        $payment_link = $channel_currency->alpha_code . ':' . $channel->payment_wallet . '?amount=' . $card->local_amount . '&message=Funding+to+' . config('app.domain') . '+User+' . $user->uuid;

        return view('core-client.card.invoice', [
            'card' => $card,
            'channel' => $card->channel,
            'currency' => $card->channel->currency,
            'payment_link' => $payment_link,
            'pageTitle' => 'Card Order Invoice',
        ]);
    }

    public function manage(Request $request)
    {
        $user = $request->user();
        $cards = $user->cards()->orderByDesc('created_at')->paginate(10);

        return view('core-client.card.manage', [
            'cards' => $cards,
            'pageTitle' => 'Manage My Web3 Prepaid Cards',
        ]);
    }

    public function doManage(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $valid_actions = ['cancel', 'claim_pay'];
        $input         = $request->validate([
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_cards,id',
        ]);

        $items = Card::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'cancel':
                $affected = $items->where('status', Card::S_PENDING)->update(['status' => Card::S_CANCELLED]);
                break;

            case 'claim_pay':
                $input_2 = $request->validate([
                    'trans_hash'   => 'required|array|min:1',
                    'trans_hash.*' => 'required|string',
                ], [
                    'trans_hash.*.required' => 'The Transaction Hash is Required'
                ]);

                foreach ($items->whereIn('status', [Card::S_PENDING, Card::S_PAID_IN])->get() as $card) {
                    $card->update(['status' => Card::S_PAID_IN, 'payment_reference' => $input_2['trans_hash'][$card->id]]);
                    NotificationManager::sendPaymentClaimNotice($card);
                    $affected++;
                }
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => route('core.client.card.manage'),
        ];
    }
}
