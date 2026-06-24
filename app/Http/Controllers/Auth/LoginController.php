<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\ETC\Setting;
use App\Traits\Controllers\RedirectPath;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, RedirectPath {
        RedirectPath::redirectPath insteadof AuthenticatesUsers;
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->only($auth_methods = ['logout', 'send2FAuthCode', 'show2FAuthForm', 'authorizeLogin']);
        $this->middleware('guest')->except($auth_methods);
    }

    /**
     * Show the application's login form.
     *
     * @return Application|Factory|View
     */
    public function showLoginForm()
    {
        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        return view('auth.login', [
            '_pageTitle' => 'Login to Your Account',
        ]);
    }

    /**
     * @return Factory|View
     */
    public function show2FAuthForm()
    {
        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        return view('auth.authorize', [
            '_pageTitle' => 'Authorize Login |' . $app_name,
        ]);
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     * @throws ValidationException
     */
    public function authorizeLogin(Request $request)
    {
        $this->validate($request, [
            'authorization_code' => 'required|numeric',
        ]);

        /**
         * @var User $user
         */
        $user          = $request->user();
        $input         = $request->all();
        $authenticator = new GoogleAuthenticator();

        $status = $authenticator->checkCode($user->two_fa_secret, $input['authorization_code']);
        if ($status) {
            session()->forget('loginAuthCode');
        }
        $message = ($status ? 'Login Authorized.' : "Incorrect code. Try again.");
        $arr     = [
            'status'   => $status,
            'message'  => $message,
            'redirect' => ($status ? $this->redirectPath() : redirect()->back()->getTargetUrl()),
        ];

        if ($request->wantsJson()) {
            return response($arr);
        }

        return redirect($arr['redirect'])->with(Arr::except($arr, 'redirect'));
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array|Response
     */
    protected function sendLoginResponse(Request $request)
    {
        if ($request->wantsJson()) {
            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return $this->authenticated($request, $this->guard()->user())
                ?: ['status' => true, 'message' => Lang::get('auth.login_ok'), 'redirect' => $this->redirectPath()];
        }

        return parent::callAction('sendLoginResponse', [$request]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->wantsJson()) {
            $data['status']  = false;
            $data['message'] = Lang::get('auth.failed');

            return $data;
        }

        //return 'bad';
        return parent::callAction('sendFailedLoginResponse', [$request]);
    }

    /**
     * @inheritDoc
     */
    protected function attemptLogin(Request $request): bool
    {
        $credentials = $this->credentials($request);
        /**
         * @var User $user
         */
        $user = User::where([
            $this->username() => $credentials[$this->username()],
            'password'        => $credentials['password'],
        ])->first();

        if (is_object($user)) {
            Auth::login($user, $request->filled('remember'));
            $user->last_login = now();
            $user->save(['timestamps' => false]);

            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param User|Authenticatable $user
     * @return array
     */
    protected function authenticated(Request $request, $user): array
    {
        $redirect = redirect()->back()->getTargetUrl();
        if ($user->getSetting(Setting::KEY_ENABLE_2FA) && Setting::get(Setting::KEY_ENABLE_2FA)) {
            session()->put('loginAuthCode', $redirect);
            $redirect = route('auth.2fa.authorize');
        }

        return ['status' => true, 'message' => 'Login Successful', 'redirect' => $redirect];
    }
}
