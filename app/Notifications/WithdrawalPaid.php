<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\Core\Withdrawal;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalPaid extends Notification implements ShouldQueue
{
    use Queueable;

    private $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @param Withdrawal $withdrawal
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
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
        $mail_from_address = config('mail.from.address');
        $withdrawal        = $this->withdrawal;
        $user              = $withdrawal->user;
        $dashboard_url     = route('core.client.dashboard');
        $notifiable_name   = $notifiable->name;
        $date_time         = date_time_for_humans($withdrawal->created_at);

        return (new MailMessage)
            ->from($mail_from_address, $app_name . ' Finance')
            ->subject($app_name . ' Withdrawal Has Been Sent!')
            ->greeting('Hello ' . $notifiable_name . ' (#' . $user->uuid . ')')
            ->line("{$withdrawal->amountStr()} has been successfully sent to your {$withdrawal->channel->name} account {$withdrawal->account_address}.")
            ->line("Date/Time: {$date_time}")
            ->line("Transaction Hash is {$withdrawal->getTransReference()}")
            ->action('Go to Dashboard', $dashboard_url)
            ->line('Thank you for being with us!');
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
