<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\TransactionManager;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Traits\Controllers\RedirectPath;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers,
        RedirectPath {
        RedirectPath::redirectPath insteadof RegistersUsers;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param null $referrerAID
     * @return Factory|Application|View
     */
    public function showRegistrationForm(Request $request, $referrerAID = null)
    {
        $referrer          = User::findByUUID($referrerAID = $request->session()->get('referrerAID', $referrerAID));
        $referrals_allowed = Setting::get(Setting::KEY_ALLOW_REFERRALS);
        if ($referrals_allowed) $request->session()->put('referrerAID', $referrerAID);

        $countries = Country::activeOnly()->get();

        return view('auth.register', [
            'countries'         => $countries,
            'referrer'          => $referrer,
            'referrerAID'       => $referrerAID,
            'referrals_allowed' => $referrals_allowed,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Redirector|Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(Request $request)
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $input     = $request->input();
        $validator = $this->validator($input);
        $validator->validate();

        $referrerAID       = $request->session()->get('referrerAID', $input['referrer']);
        $referrer          = User::findByUUID($referrerAID);
        $referrals_allowed = is_object($referrer) && $referrer->getSetting(Setting::KEY_ALLOW_REFERRALS);
        $input['referrer'] = $referrals_allowed ? $referrerAID : null;

        DB::beginTransaction();
        $user = $this->create($input);  
        $crypto_currencies = Currency::activeOnly()->where('is_crypto', true)->get();
        foreach($crypto_currencies as $currency) {
            Account::factory()->create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'is_active' => true,
                'wallet_address' => null,
            ]);
        }
        DB::commit();

        $this->guard()->login($user);
        NotificationManager::sendWelcomeMessage($user);
        NotificationManager::sendEmailVerificationRequest($user);

        $data['status']   = true;
        $data['message']  = Lang::get('auth.registration_ok');
        $data['redirect'] = $this->redirectPath();

        if ($request->wantsJson()) {
            return response()->json($data)->setStatusCode(201);
        }

        return redirect($data['redirect']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $countries = Country::activeOnly()->pluck('id')->all();

        return Validator::make($data, [
            'country'              => 'required|in:' . implode(',', $countries),
            'first_name'           => 'required|alpha|max:32',
            'last_name'            => 'required|alpha|max:32',
            'phone'                => 'required|string|max:15|unique:auth_users,phone',
            'email'                => 'required|email:rfc,dns,spoof|unique:auth_users,email',
            'password'             => 'required|string|min:8',
            'referrer'             => 'nullable|exists:auth_users,uuid',
            'terms_and_conditions' => 'accepted',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data): User
    {
        $data['last_login'] = now();
        $data['uuid']       = User::generateUUID();
        $data['country_id'] = $data['country'];
        //$data['password'] = bcrypt($data['password']);
        $data['status']                                              = User::S_ACTIVATED;
        $data['wallet_addresses']                                    = json_encode([]);
        $data['account_settings']                                    = User::defaultSettings();
        $data['account_settings'][Setting::KEY_ENABLE_2FA]           = false;
        $data['account_settings'][Setting::KEY_ENABLE_NOTIFICATIONS] = true;
        $data['referrer_id']                                         = isset($data['referrer']) && is_object($R = User::findByUUID($data['referrer'])) ? $R->id : null;

        /**
         * @var User $user
         */
        $user = User::create(Arr::only($data, [
            'uuid', 'country_id', 'currency_id', 'first_name', 'last_name',
            'phone', 'email', 'password', 'referrer_id', 'status', 'last_login',
            'email_token', 'phone_token', 'account_settings', 'wallet_addresses',
        ]));

        $member_role = Role::findByName(Role::CLIENT);
        $user->roles()->attach($member_role);

        return $user;
    }
}
