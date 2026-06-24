<?php
declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Base;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class ResetPassword
 * @package App\Notifications
 */
class ResetPassword extends Base
{
    /**
     * @inheritDoc
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Password Reset Link')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', route('auth.password.link', ['token' => $this->token]))
            ->line('If you did not request a password reset, no further action is required.');
    }
}
