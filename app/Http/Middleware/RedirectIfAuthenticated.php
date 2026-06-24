<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\Controllers\RedirectPath;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RedirectIfAuthenticated
 * @package App\Http\Middleware
 */
class RedirectIfAuthenticated
{
    use RedirectPath;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return $request->wantsJson()
                ? response(['mode' => 'info', 'message' => 'Redirecting', 'redirect' => $this->redirectPath()])
                : redirect($this->redirectPath());
        }

        return $next($request);
    }
}
