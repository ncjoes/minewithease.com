<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Auth\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthorizeByPermission
 * @package App\Http\Middleware
 */
class AuthorizeByPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        /**
         * @var User $user
         */
        if (!Auth::guest() and is_object($user = Auth::user()) and $user->hasPermission($permission)) {
            return $next($request);
        }

        return response(view('errors.403'), 403);
    }
}
