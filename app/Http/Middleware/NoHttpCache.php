<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class NoHttpCache
 * @package App\Http\Middleware
 */
class NoHttpCache
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // This step is only needed if you are returning
        // a view in your Controller or elsewhere, because
        // when returning a view `$next($request)` returns
        // a View object, not a Response object, so we need
        // to wrap the View back in a Response.
        if (!$response instanceof SymfonyResponse) {
            $response = new Response($response);
        }

        $response->header('Cache-Control', 'public, max-age=0');
        $now = Carbon::now()->toRfc822String();
        $response->header('Expires', $now);

        return $response;
    }
}
