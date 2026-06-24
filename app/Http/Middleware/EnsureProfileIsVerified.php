<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Auth\User;
use App\Models\ETC\Setting;
use Closure;
use Illuminate\Http\Request;

/**
 * Class EnsureProfileIsVerified
 * @package App\Http\Middleware
 */
class EnsureProfileIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $exceptions = [
            'core.client.profile-update',
            'core.client.profile.photo',
            'core.client.password-change',
            'core.client.account-settings'
        ];
        if (!$user->hasVerifiedProfile() && !in_array($request->route()->getName(), $exceptions) && $user->getSetting(Setting::KEY_REQUIRE_PROFILE_VERIFICATION)) {
            if ($request->expectsJson()) {
                abort(403, 'Your profile is not verified.');
            }

            $message = $user->hasFilledProfileData()
                ? "KYC VERIFICATION: Your profile is under review. You will be able to proceed when your details have been verified."
                : "KYC REQUIRED: To proceed, kindly update your wallet addresses and submit your  and upload valid Passport photograph and ID.";
            session()->flash('message', ['message' => $message, 'status' => 'info']);

            return redirect()->route('core.client.profile-update');
        }

        return $next($request);
    }
}
