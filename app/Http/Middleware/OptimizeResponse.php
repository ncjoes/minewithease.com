<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class OptimizeResponse
 * @package App\Http\Middleware
 */
class OptimizeResponse
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
        if (app()->environment('production')) {
            $route_action = is_object($route = $request->route()) ? class_basename($route->getActionName()) : null;
            $exceptions   = ['CaptchaController@index', 'CaptchaController@html'];
            if (!is_object($response) || $response instanceof BinaryFileResponse || $response->getStatusCode() === 500 || in_array($route_action, $exceptions)) {
                return $response;
            } else {
                $buffer = $response->getContent();
                if (stripos($buffer, '<pre>') !== false || stripos($buffer, '<textarea') !== false) {
                    $replace = [
                        '/<!--[^\[](.*?)[^\]]-->/s' => '',
                        "/<\?php/"                  => '<?php ',
                        "/\r/"                      => '',
                        "/>\n</"                    => '><',
                        "/>\s+\n</"                 => '><',
                        "/>\n\s+</"                 => '><',
                    ];
                } else {
                    $replace = [
                        '/<!--[^\[](.*?)[^\]]-->/s' => '',
                        "/<\?php/"                  => '<?php ',
                        "/\n([\S])/"                => '$1',
                        "/\r/"                      => '',
                        "/\n/"                      => '',
                        "/\t/"                      => '',
                        "/ +/"                      => ' ',
                    ];
                }
                $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
                $response->setContent($buffer);
                ini_set('zlib.output_compression', 'On'); //enable GZip, too!

            }
        }

        return $response;
    }
}
