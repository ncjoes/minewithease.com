<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Debugger;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Route;

$app_domain         = config('app.domain');
$admin_panel_prefix = config('app.admin_url_prefix');

Route::group(['domain' => $app_domain], function () use ($admin_panel_prefix) {
    Debugger::routes();

    Route::group(['namespace' => 'Auth', 'as' => 'auth.', 'prefix' => 'auth'], function () {
        require_once __DIR__ . '/web/auth.php';
    });

    Route::group(['namespace' => 'Auth', 'as' => 'verification.', 'prefix' => 'auth'], function () {
        require_once __DIR__ . '/web/verification.php';
    });

    Route::group([
        'namespace'  => 'Core\Client',
        'middleware' => [
            'auth'   => 'auth',
            'role'   => 'role:' . Role::CLIENT,
            'verify' => 'verify-email',
            '2fa'    => '2FAuth',
            'status' => 'status:' . User::S_ACTIVATED . '|' . User::S_ON_TRIAL . '|' . User::S_DEACTIVATED,
        ],
        'as'         => 'core.client.',
        'prefix' => 'wallet',
    ], function () {
        require_once __DIR__ . '/web/core.client.php';
    });

    Route::group([
        'namespace'  => 'CMS',
        'middleware' => ['auth', 'role:' . Role::EDITOR, 'verify-email', '2FAuth'],
        'as'         => 'cms.admin.',
        'prefix'     => $admin_panel_prefix . '/cms',
    ], function () {
        require_once __DIR__ . '/web/cms.admin.php';
    });

    Route::group([
        'namespace'  => 'Core\Admin',
        'middleware' => ['web', 'auth', 'role:' . Role::ADMIN, 'verify-email', '2FAuth'],
        'as'         => 'core.admin.',
        'prefix'     => $admin_panel_prefix . '/core',
    ], function () {
        require_once __DIR__ . '/web/core.admin.php';
    });

    Route::group(['namespace' => 'CMS', 'as' => 'cms.'], function () {
        require_once __DIR__ . '/web/cms.public.php';
    });
});
