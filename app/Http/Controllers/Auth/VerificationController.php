<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Models\Auth\User;
use App\Traits\Controllers\RedirectPath;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Class VerificationController
 * @package App\Http\Controllers\Auth
 */
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be resent if the user did not receive the original email message.
    |
    */

    use VerifiesEmails, RedirectPath {
        RedirectPath::redirectPath insteadof VerifiesEmails;
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return Response|Redirector|RedirectResponse
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        /**
         * @var User $user
         */
        if (!is_object($user = User::find($request->route('id')))) {
            throw new AuthorizationException;
        }

        /*
        if (!hash_equals((string)$request->get('signature'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }
        */

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('core.client.dashboard');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('core.client.dashboard')->with('verified', true);
    }

    /**
     * @inheritDoc
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resend(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $this->validate($request, [
            'email' => 'required|max:255|email|unique:auth_users,email,' . $user->id,
        ]);
        $user->update(['email' => $request->input('email')]);

        $response = NotificationManager::sendEmailVerificationRequest($user);
        session()->flash('resent', $response['status']);
        session()->flash('message', $response['message']);

        return redirect()->back();
    }
}
