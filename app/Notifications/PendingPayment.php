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
 * Class PendingPayment
 * @package App\Notifications
 */
class PendingPayment extends Notification implements ShouldQueue
{
    use Queueable;

    public $deposit;

    /**
     * Create a new notification instance.
     * @param Payable $deposit
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
        $app_name = Setting::get(Setting::KEY_ORG_NAME);
        $app_domain = config('app.domain');
        $deposit = $this->deposit;
        $invoice_url = $deposit->url;
        $notifiable_name = $notifiable->first_name;
        $amount = $deposit->amount();
        $mail_from_address = env('MAIL_FROM_ADDRESS');

        return (new MailMessage)
            ->from($mail_from_address, $app_name.' Accounting')
            ->subject('Pending Deposit Invoice ['.$amount.']')
            ->greeting('Hello, '.$notifiable_name.' (#'.$notifiable->uuid.')')
            ->line('Be reminded that you have a pending payment of '.$amount.
                '. Endeavor to make payment as it will be automatically cancelled after 24 hours.')
            ->action('Make Payment Now', $invoice_url);
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
