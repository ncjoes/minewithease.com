<?php
declare(strict_types=1);

namespace App\Traits\Controllers;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Trait RedirectPath
 * @package App\Traits\Controllers
 */
trait RedirectPath
{
    /**
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function redirectPath(): string
    {
        if (session()->has('redirectPath')) {
            $redirect = session()->get('redirectPath');
            session()->forget('redirectPath');

            return $redirect;
        }

        $redirect = route('cms.home');
        /**
         * @var User $user
         */
        if (is_object($user = Auth::user())) {
            if ($user->hasRole(Role::ADMIN)) {
                $redirect = route('core.admin.dashboard');
            } elseif ($user->hasRole(Role::CLIENT)) {
                $redirect = route('core.client.dashboard');
            } elseif ($user->hasRole(Role::EDITOR)) {
                $redirect = route('cms.admin.home');
            }
        }

        return redirect()->intended($redirect)->getTargetUrl();
    }
}
