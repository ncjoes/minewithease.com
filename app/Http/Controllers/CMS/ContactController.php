<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ContactController
 * @package App\Http\Controllers\CMS
 */
class ContactController extends Controller
{
    /**
     * @return Factory|View
     */
    public function show()
    {
        return view('cms-public.contact', [
            //'google_maps_api_key' => Setting::get(Setting::KEY_CONTACT_GMAPS_API_KEY),
            //'google_maps_latitude' => Setting::get(Setting::KEY_CONTACT_GMAPS_LATITUDE),
            //'google_maps_longitude' => Setting::get(Setting::KEY_CONTACT_GMAPS_LONGITUDE),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse | array
     */
    public function send(Request $request)
    {
        $input         = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email:rfc,dns|max:180',
            'subject' => 'required|string|max:70',
            'message' => 'required|string|min:80|max:1000',
        ]);
        $input['time'] = date_time_for_humans(Carbon::now());

        $response = NotificationManager::sendContactFormMessage($input);
        if ($response['status']) {
            NotificationManager::sendMessageReceivedNotice($input);
        }
        $message = ($response['status'] ? 'Message sent successfully' : 'An error occurred. Try again shortly.');

        if ($request->wantsJson()) {
            return [
                'status'   => $response['status'],
                'message'  => $message,
                'redirect' => ($response['status'] ? route('cms.contact') : null),
                'debug'    => $response,
            ];
        }
        session()->flash('status', $response['status']);
        session()->flash('message', $message);
        if (!$response['status']) session()->flashInput($input);

        return redirect()->back();
    }
}
