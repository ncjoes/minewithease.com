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
 * Class AccountStatusChange
 * @package App\Notifications
 */
class AccountStatusChange extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     * @var User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
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
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $app_name          = Setting::get(Setting::KEY_ORG_NAME);
        $mail_from_address = env('MAIL_FROM_ADDRESS');
        $user              = $this->user;
        $dashboard_url     = route('core.client.dashboard');
        $notifiable_name   = $notifiable->first_name;

        return (new MailMessage)
            ->from($mail_from_address, $app_name . ' Accounting')
            ->subject($app_name . ' Account ' . $user->status())
            ->greeting('Hello, '.$notifiable_name.' (#'.$user->uuid.')')
            ->line('This is a notification on your '.$app_name.' account.')
            ->line('Type: Account Status Changed')
            ->line('Time-Date: '.date_time_for_humans($user->updated_at))
            ->line('Details: Your account status was recently changed to "'.$user->status().'". Contact support for more information.')
            ->line('Visit your dashboard for more details.')
            ->action('Go to Dashboard', $dashboard_url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
