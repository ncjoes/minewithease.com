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
 * Class VerificationNotice
 * @package App\Notifications
 */
class VerificationNotice extends Notification implements ShouldQueue
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
        $app_name          = Setting::get(Setting::KEY_ORG_NAME);
        $app_domain        = config('app.domain');
        $user              = $this->user;
        $notifiable_name   = $user->first_name;
        $mail_from_address = config('mail.from.address');
        $dashboard_url     = route('core.client.dashboard');

        return (new MailMessage)
            ->from($mail_from_address, $app_name.' Accounting')
            ->subject('Congratulation! Your Account Has Been Verified')
            ->greeting('Hello, '.$notifiable_name.' (#'.$notifiable->uuid.')')
            ->line('This is to notify you that your '.$app_name.' profile has been verified.')
            ->action('Open Dashboard', $dashboard_url);
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
