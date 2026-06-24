<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Auth\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthorizeByStatus
 * @package App\Http\Middleware
 */
class AuthorizeByStatus
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed $allowed_states
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $allowed_states = User::S_ACTIVATED)
    {
        /**
         * @var User $user
         */
        $user           = $request->user();
        $user_status    = strtolower($user->status);
        $allowed_states = explode('|', (string)$allowed_states);

        if (in_array($user_status, $allowed_states)) {
            return $next($request);
        }

        if (array_key_exists($user->status, User::states())) {
            $status_str = User::states()[$user->status];
            $message    = 'Account ' . $status_str . '! Action not allowed.';
        } else {
            $status_str = "Unknown-Error";
            $message    = "Account Error! Contact Support.";
            abort(500, $message);
        }
        return $this->respond($request, 'errors.' . strtolower($status_str), $message);
    }

    /**
     * @param Request $request
     * @param $view
     * @param $pageTitle
     * @return JsonResponse|Response
     */
    protected function respond(Request $request, $view, $pageTitle)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => false,
                'message' => $pageTitle,
            ], 403);
        }

        return response()->view($view, [
            'PAGE_TITLE' => $pageTitle,
        ]);
    }
}
