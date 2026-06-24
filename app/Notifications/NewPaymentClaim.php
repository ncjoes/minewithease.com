<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Interfaces\Payable;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NewPaymentClaim
 * @package App\Notifications
 */
class NewPaymentClaim extends Notification implements ShouldQueue
{
    use Queueable;

    public $deposit;

    /**
     * Create a new notification instance.
     * @param Payable $deposit
     *
     * @return void
     */
    public function __construct(Payable $deposit)
    {
        $this->deposit = $deposit;
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
        $deposit = $this->deposit;
        $depositor = $deposit->user;
        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        $mail_message = (new MailMessage)
            ->subject("New Payment Claim by {$depositor->name}")
            ->greeting('Hi Admin')
            ->line('You have a new payment claim on '.$app_name.'! Check and verify')
            ->action('View Invoice', $deposit->admin_url);

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
