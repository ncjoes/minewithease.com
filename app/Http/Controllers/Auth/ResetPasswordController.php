<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\Controllers\RedirectPath;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class ResetPasswordController
 * @package App\Http\Controllers\Auth
 */
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, RedirectPath {
        RedirectPath::redirectPath insteadof ResetsPasswords;
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @inheritDoc
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset')->with([
                'token'      => $token,
                'PAGE_TITLE' => 'Reset Password',
            ]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;//Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * @inheritDoc
     */
    protected function sendResetResponse($response)
    {
        if (request()->wantsJson()) {
            return ['status' => true, 'message' => ($response), 'redirect' => $this->redirectPath()];
        }

        return redirect($this->redirectPath())->with('status', ($response));
    }

    /**
     * @inheritDoc
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return ['status' => false, 'message' => ($response)];
        }

        return redirect()->back()
                         ->withInput($request->only('email'))
                         ->withErrors(['email' => ($response)]);
    }
}
