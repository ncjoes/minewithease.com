<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\ETC\Setting;
use Illuminate\Auth\Notifications\VerifyEmail as Base;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

/**
 * Class AuthorizeLogin
 * @package App\Notifications
 */
class AuthorizeLogin extends Base
{
    protected $code;

    /**
     * AuthorizeLogin constructor.
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        return (new MailMessage)
            ->subject(Lang::get('Authorize Login Attempt'))
            ->greeting("Hello " . $notifiable->first_name)
            ->line(Lang::get('Your one time passcode is ' . $this->code))
            ->line(Lang::get('If you did not attempt to sign into your account recently, kindly change your password to secure your account.'));
    }
}
