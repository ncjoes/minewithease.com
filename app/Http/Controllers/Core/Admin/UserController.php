<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\Core\Package;
use App\Models\ETC\Country;
use App\Models\ETC\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class UserController
 * @package App\Http\Controllers\Core\Admin
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search     = (string)$request->get('search', '');
        $search_arr = explode(',', $search);
        $status     = $request->get('status', User::S_ACTIVATED);
        $per_page   = $request->get('per_page', 40);
        $country    = Country::findByISO2($request->get('country'));

        $countries = Country::activeOnly()->get();
        $users     = User::findByStatus($status);
        $users     = is_object($country) ? $users->where('country_id', $country->id) : $users;
        $users     = (strlen((string)$search) and sizeof($search_arr)) ? $users->whereIn('uuid', $search_arr)->orWhereIn('email', $search_arr) : $users;

        $net_count    = User::count();
        $result_count = $users->count();
        //$users = $users->orderBy('first_name')->orderBy('last_name')->paginate($per_page);
        $users = $users->orderByDesc('id')->paginate($per_page);

        return view('core-admin.user.manage', [
            'users'        => $users,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'countries'    => $countries,
            'statuses'     => User::states(),
            'filter'       => [
                'search'   => $search,
                'status'   => $status,
                'country'  => is_object($country) ? $country->iso2 : '',
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Factory|View
     */
    public function view(Request $request, User $user)
    {
        $settings      = [];
        $user_settings = $user->getSettings();
        foreach (User::defaultSettings() as $key => $value) {
            if (is_object($object = Setting::where('key', '=', $key)->first())) {
                $object->value = $user_settings[$key];
                $settings[]    = $object;
            }
        }
        $channels = Channel::activeOnly()->get();
        $packages = Package::activeOnly()->orderBy('min_amount')->get();

        return view('core-admin.user.view', [
            '_user'        => $user,
            'settings'     => $settings,
            'channels'     => $channels,
            'packages'     => $packages,
            'transactions' => $user->transactions()->paginate(30),
            'portfolios'   => $user->portfolios()->paginate(20),
            'referrals'    => $user->referrals,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $valid_actions = ['activate', 'deactivate', 'disable', 'mark_as_verified', 'reject_documents'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:auth_users,id',
        ]);

        $input    = $request->input();
        $users    = User::whereIn('id', $input['ids']);
        $affected = 0;

        $now = Carbon::now();
        DB::beginTransaction();
        switch ($input['action']) {
            case 'activate':
                foreach ($users->whereIn('status', [User::S_DEACTIVATED, User::S_DISABLED])->get() as $user) {
                    $user->update(['status' => User::S_ACTIVATED]);
                    NotificationManager::sendStatusChangeNotice($user);
                    $affected++;
                }
                break;
            case 'deactivate':
                foreach ($users->where('status', User::S_ACTIVATED)->get() as $user) {
                    $user->update(['status' => User::S_DEACTIVATED]);
                    NotificationManager::sendStatusChangeNotice($user);
                    $affected++;
                }
                break;
            case 'disable':
                foreach ($users->whereIn('status', [User::S_DEACTIVATED, User::S_ACTIVATED, User::S_ON_TRIAL])->get() as $user) {
                    $user->update(['status' => User::S_DISABLED]);
                    NotificationManager::sendStatusChangeNotice($user);
                    $affected++;
                }
                break;
            case 'reject_documents':
                /**
                 * @var User $user
                 */
                foreach ($users->get() as $user) {
                    $user->deleteImage('photo');
                    $user->deleteImage('identification');
                    $user->update(['profile_verified_at' => null]);
                    $affected++;
                }
                break;
            case 'mark_as_verified':
                /**
                 * @var User $user
                 */
                foreach ($users->get() as $user) {
                    if ($user->needsVerification()) {
                        NotificationManager::sendProfileVerificationNotice($user);
                        $affected += $user->update(['profile_verified_at' => $now]);
                    }
                }
                break;
        }
        DB::commit();

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array
     * @throws ValidationException
     */
    public function reconcileAccount(Request $request, User $user): array
    {
        $this->validate($request, [
            'account_id'  => 'required|in:'.implode(',', $user->accounts()->pluck('id')->toArray()),
            'action'      => 'required|in:credit,debit',
            'description' => 'required|string|min:10',
            'amount'      => 'required|numeric|min:0',
            'date-time'   => 'required|date|before_or_equal:' . Carbon::now()->toString(),
        ]);
        $account      = $user->accounts()->where('id', $request->input('account_id'))->firstOrFail(); 
        $action       = $request->input('action');
        $amount       = $request->input('amount');
        $description  = $request->input('description');
        $datetime     = Carbon::createFromTimeString($request->input('date-time'));
        $transactions = TransactionManager::reconcileAccount($account, ($action == 'debit' ? -$amount : $amount), $datetime, $description);

        NotificationManager::sendTransactionNotices($transactions);

        return [
            'status'   => true,
            'message'  => $user->name . ' account balance updated.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function changePassword(Request $request, User $user): array
    {
        $input = $request->validate([
            'new_password' => 'required|string|confirmed|min:8',
        ]);
        //$password = Hash::make($input['new_password']);
        $password = $input['new_password'];
        $user->update(['password' => $password]);

        return [
            'status'   => true,
            'message'  => $user->name . ' account password updated.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateSettings(Request $request, User $user): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $input    = $request->all();
        $settings = [];
        foreach ($input as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (is_object($setting)) {
                $this->validate($request, [$key => $setting->validation_rules]);
                $settings[$key] = Setting::parseValue($setting, $value, true);
            }
        }
        $status = $user->update(['account_settings' => array_merge($user->getSettings(), $settings)]);

        return [
            'status'  => $status,
            'message' => $status ? 'Changes saved successfully.' : 'An error occurred!',
        ];
    }

    public function updateAffiliateRate(Request $request, User $user): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $data   = $request->validate(['affiliate_rates' => 'nullable']);
        $rates  = implode(',', explode(',', trim((string)$data['affiliate_rates'])));
        $status = $user->setAffiliateRate($rates);

        return [
            'status'  => $status,
            'message' => $status ? 'Changes saved successfully.' : 'An error occurred!',
        ];
    }
}
