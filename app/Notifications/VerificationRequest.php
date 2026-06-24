<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\Auth\User;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class VerificationRequest
 * @package App\Notifications
 */
class VerificationRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;
        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        $mail_message = (new MailMessage)
            ->subject("URGENT! New KYC-Verification Request by {$user->name}")
            ->greeting('Hi Admin')
            ->line('You have a new verification request on '.$app_name.'! Check and verify')
            ->action('View Profile', $user->admin_url);

        return $mail_message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
