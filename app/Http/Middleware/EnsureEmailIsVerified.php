<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ETC\Setting;
use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as Base;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Class EnsureEmailIsVerified
 * @package App\Http\Middleware
 */
class EnsureEmailIsVerified extends Base
{
    /**
     * @inheritDoc
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        /**
         * @var \App\Models\Auth\User $user
         */
        if (!($user = $request->user()) || (!$user->hasVerifiedEmail() && $user instanceof MustVerifyEmail && $user->getSetting(Setting::KEY_REQUIRE_EMAIL_VERIFICATION))) {

            return $request->expectsJson() ? abort(403, 'Your email address is not verified.') : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
