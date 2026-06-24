<?php
declare(strict_types=1);

namespace App\Http;

use App\Http\Middleware\AuthorizeByPermission;
use App\Http\Middleware\AuthorizeByRole;
use App\Http\Middleware\AuthorizeByStatus;
use App\Http\Middleware\CheckFor2FAuth;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureProfileIsVerified;
use App\Http\Middleware\NoHttpCache;
use App\Http\Middleware\OptimizeResponse;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Class Kernel
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            OptimizeResponse::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'auth'           => Authenticate::class,
        'auth.basic'     => AuthenticateWithBasicAuth::class,
        'bindings'       => SubstituteBindings::class,
        'can'            => Authorize::class,
        'guest'          => RedirectIfAuthenticated::class,
        'throttle'       => ThrottleRequests::class,
        'role'           => AuthorizeByRole::class,
        'permission'     => AuthorizeByPermission::class,
        'status'         => AuthorizeByStatus::class,
        'no-cache'       => NoHttpCache::class,
        'verify-email'   => EnsureEmailIsVerified::class,
        '2FAuth'         => CheckFor2FAuth::class,
        'verify-profile' => EnsureProfileIsVerified::class,
        'signed'         => ValidateSignature::class,
    ];
}
