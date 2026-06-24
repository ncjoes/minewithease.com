<?php

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(10);

        return view('core-client.account.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function manage(Request $request)
    {
        $user = $request->user();
        $input = $request->validate([
            'action' => 'required|in:read,unread,delete',
            'notification' => 'required|array|min:1|exists:notifications,id',
        ]);

        $selected = $user->notifications()->whereIn('id', $input['notification']);

        switch ($input['action']) {
            case 'read':
                $selected->update(['read_at' => now()]);
                break;
            case 'unread':
                $selected->update(['read_at' => null]);
                break;
            case 'delete':
                $selected->delete();
                break;
        }

        return [
            'status' => true,
            'message' => "Action applied successfully.",
            'redirect' => back()->getTargetUrl(),
        ];
    }
}