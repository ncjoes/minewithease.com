<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\ETC\Setting;
use Illuminate\Auth\Notifications\VerifyEmail as Base;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

/**
 * Class VerifyEmail
 * @package App\Notifications
 */
class VerifyEmail extends Base
{
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
            ->subject(Lang::get('Verify Email Address'))
            ->greeting("Hello " . $notifiable->first_name)
            ->line(Lang::get('Thanks for being on ' . $app_name . '! Please confirm your email address ' .
                'by clicking on the link below. We\'ll communicate with you from time to time via email so ' .
                'it\'s important that we have an up-to-date email address on file.'))
            ->action(Lang::get('Verify My Email Address'), $this->verificationUrl($notifiable))
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }
}
