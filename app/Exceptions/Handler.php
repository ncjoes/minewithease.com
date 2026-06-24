<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception|\Throwable
     */
    public function report(Throwable $exception)
    {
      parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     *
     * @return \Illuminate\Http\JsonResponse|object|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'   => false,
                    'message'  => "Expired Token! Refresh page to continue.",
                    'redirect' => redirect()->back()->getTargetUrl(),
                ])->setStatusCode(419);
            }

            redirect()->back();
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'   => false,
                    'message'  => Lang::get('http-errors.400'),
                    'redirect' => redirect()->back()->getTargetUrl(),
                ])->setStatusCode(400);
            }
            abort(400);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse | JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('auth.login'));
    }
}
