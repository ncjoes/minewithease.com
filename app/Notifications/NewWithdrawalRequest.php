<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\Core\Withdrawal;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewWithdrawalRequest extends Notification
{
    use Queueable;

    public $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $withdrawal = $this->withdrawal;
        $client     = $withdrawal->user;
        $app_name   = Setting::get(Setting::KEY_ORG_NAME);

        $mail_message = (new MailMessage)
            ->subject("New Withdrawal Request by {$client->name()}")
            ->greeting('Hi Admin')
            ->line('You have a new withdrawal request on ' . $app_name . '! Check for further actions.')
            ->line('Client-name: ' . $client->name())
            ->line('Withdrawal-Amount: ' . $withdrawal->amount() . ' (' . $withdrawal->localAmount() . ')')
            ->action('View Slip', $withdrawal->admin_url);

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
