<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

/**
 * Class Debugger
 * @package App\Http\Controllers
 */
class Debugger extends Controller
{
    public static function routes()
    {
        if (config('app.debug')) {
            Route::group(['as' => 'debug.', 'prefix' => 'debug'], function () {
                $class_name = class_basename(self::class);

                Route::get('routes', ['as' => 'routes', 'uses' => $class_name.'@showRoutes']);

                Route::get('phpinfo', ['as' => 'phpinfo', 'uses' => $class_name.'@phpInfo']);

                Route::get('xdebug-info', function () { xdebug_info();});
            });
        }
    }

    /**
     * @return Factory|View
     */
    public static function showRoutes()
    {
        $data['routes'] = Route::getRoutes();

        return view('debug.routes', $data);
    }

    public static function phpInfo()
    {
        phpinfo();
    }
}
