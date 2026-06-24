<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckFor2FAuth
 * @package App\Http\Middleware
 */
class CheckFor2FAuth
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
        if (session()->has('loginAuthCode')) {
            if ($request->expectsJson()) {
                return response(['status' => false, 'message' => 'Authorization Required.', 'redirect' => route('auth.2fa.authorize')]);
            }

            return redirect()->route('auth.2fa.authorize');
        }

        return $next($request);
    }
}
