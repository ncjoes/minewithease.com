<?php

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\SwapManager;
use App\Managers\TransactionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class SwapController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $accounts = $user->accounts()->orderByDesc('balance')->get();

        return view('core-client.swap.create', [
            'accounts' => $accounts,
        ]);
    }

    public function doCreate(Request $request)
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $input = $request->validate([
            'source_account' => 'required|exists:core_accounts,id',
            'destination_account' => 'required|exists:core_accounts,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = $request->user();
        $sourceAccount = $user->accounts()->where('id', $input['source_account'])->firstOrFail();
        $destinationAccount = $user->accounts()->where('id', $input['destination_account'])->firstOrFail();

        if ($sourceAccount->balance < $input['amount']) {
            return [
                'status' => false,
                'message' => 'Insufficient balance in source account'
            ];
        }

        DB::beginTransaction();
        $swap = SwapManager::create($sourceAccount, $destinationAccount, $input['amount']);
        $transactions = TransactionManager::logSwap($swap);
        DB::commit();
        
        //ToDo: Add notification
        //NotificationManager::sendSwapNotification($swap);

        return [
            'status' => true,
            'message' => 'Swap executed successfully',
            'redirect' => route('core.client.swap.history'),
        ];

    }

    public function history(Request $request)
    {
        $user = $request->user();
        $swaps = $user->swaps()->orderByDesc('created_at')->paginate(15);

        return view('core-client.swap.history', [
            'swaps' => $swaps,
        ]);
    }
}
