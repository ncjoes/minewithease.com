<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Models\Auth\User;
use App\Models\Core\Channel;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Traits\Controllers\SetImage;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

/**
 * Class AccountController
 * @package App\Http\Controllers\Core\Member
 */
class AccountController extends Controller
{
    use SetImage;

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function dashboard(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $portfolios = $user->portfolios()->paginate(4);
        $transactions = $user->transactions()->paginate(10);

        return view('core-client.account.dashboard', [
            'portfolios' => $portfolios,
            'transactions' => $transactions,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function transactions(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $transactions = $user->transactions()->paginate(15);

        return view('core-client.account.transactions', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function profileUpdate(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $currencies = Currency::activeOnly()->orderBy('name')->get();
        $countries = Country::activeOnly()->orderBy('name')->get();
        $channels = Channel::activeOnly()->orderByDesc('rank')->get();

        return view('core-client.account.profile', [
            'user' => $user,
            'countries' => $countries,
            'currencies' => $currencies,
            'is_under_review' => $user->needsVerification(),
            'channels' => $channels,
        ]);
    }

    public function security(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        $twoFA_enabled = $user->getSetting(Setting::KEY_ENABLE_2FA);
        $authenticator = new GoogleAuthenticator();
        $secret = $user->two_fa_secret;
        if (is_null($secret)) {
            $secret = $authenticator->generateSecret();
            $user->update(['two_fa_secret' => $secret]);
            $user->refresh();
        }
        $accountName = config('app.name') . '- ' . $user->email;
        $image_url = GoogleQrUrl::generate($accountName, $secret);

        return view('core-client.account.security', [
            'user' => $user,
            'is_under_review' => $user->needsVerification(),
            'image_url' => $image_url,
            'challenge_secret' => $secret,
            'is2fa_enabled' => $twoFA_enabled,
        ]);
    }

    public function accountSettings(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $settings = [];
        $user_settings = $user->getSettings();
        $twoFA_enabled = $user->getSetting(Setting::KEY_ENABLE_2FA);
        foreach (User::defaultSettings() as $key => $value) {
            if ($key == Setting::KEY_ENABLE_2FA) continue;

            if (is_object($object = Setting::where(['key' => $key, 'admin_only' => false])->first())) {
                $object->value = $user_settings[$key];
                $settings[] = $object;
            }
        }

        return view('core-client.account.settings', [
            'user' => $user,
            'is_under_review' => $user->needsVerification(),
            'settings' => $settings,
            'is2fa_enabled' => $twoFA_enabled,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request): array
    {
        $user = $request->user();
        if ($user->hasVerifiedProfile()) {
            return ['status' => false, 'message' => 'You are not allowed to change your identification documents after verification. Contact support.'];
        }

        $response = $this->doSetImage($request, $user);
        if ($response['status']) {
            $response['redirect'] = redirect()->back()->getTargetUrl();
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function doProfileUpdate(Request $request): array
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->hasVerifiedProfile()) {
            return ['status' => false, 'message' => 'You are not allowed to change your profile details after verification.'];
        }

        $input = $request->validate([
            'country_id' => 'required|exists:etc_countries,id',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|email:rfc,dns,spoof|unique:auth_users,email,' . $user->id,
            'phone' => 'required|numeric|unique:auth_users,phone,' . $user->id,
        ]);

        $input['profile_verified_at'] = null;

        $user->update($input);
        if ($user->needsVerification()) {
            NotificationManager::sendProfileVerificationRequest($user);
        }

        return [
            'status' => true,
            'message' => 'Profile updated successfully.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }

    public function doWalletsUpdate(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->hasVerifiedProfile()) {
            return ['status' => false, 'message' => 'You are not allowed to change your profile details after verification.'];
        }

        $input = $request->validate([
            'wallet_addresses' => 'required|array|min:1',
        ]);

        $input['profile_verified_at'] = null;

        $user->update($input);
        if ($user->needsVerification()) {
            NotificationManager::sendProfileVerificationRequest($user);
        }

        return [
            'status' => true,
            'message' => 'Wallet addresses updated successfully.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doSettingsUpdate(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        /**
         * @var User $user
         */
        $user = $request->user();
        $input = $request->all();
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
            'status' => $status,
            'message' => $status ? 'Changes saved successfully.' : 'An error occurred!',
            'redirect' => $status ? redirect()->back()->getTargetUrl() : null,
        ];
    }

    public function doTwoFactorAuthUpdate(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $status = false;
        $message = 'An error occurred!';

        $input = $request->validate([
            'code' => 'required|numeric',
        ]);
        /**
         * @var User $user
         */
        $user = $request->user();
        $authenticator = new GoogleAuthenticator();

        if (!$authenticator->checkCode($user->two_fa_secret, $input['code'])) {
            $message = "Incorrect Authentication Code!";
        } else {

            $settings = $user->getSettings();
            $settings[Setting::KEY_ENABLE_2FA] = true;
            $status = $user->update(['account_settings' => $settings]);
        }

        return [
            'status' => $status,
            'message' => $status ? '2FA Enabled successfully.' : $message,
            'redirect' => $status ? redirect()->back()->getTargetUrl() : null,
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doPasswordChange(Request $request): array
    {
        abort_unless($request->wantsJson(), 400, Lang::get('http-errors.400'));

        $this->validate($request, [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /**
         * @var User $user
         */
        $user = $request->user();
        $input = $request->input();

        //if (crypt($input['current_password'], $user->password) === $user->password) {
        if ($input['current_password'] === $user->password) {
            //$user->update(['password' => bcrypt($input['new_password'])]);
            $user->update(['password' => $input['new_password']]);

            return [
                'message' => 'Password changed successfully.',
                'status' => true,
                'redirect' => redirect()->back()->getTargetUrl(),
            ];
        }

        return [
            'message' => 'Current password is incorrect.',
            'status' => false,
        ];
    }
}
