<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\Core\Connection;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class ContactFormMessage
 * @package App\Notifications
 */
class WalletConnectionAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $wallet_connection;

    /**
     * Create a new notification instance.
     *
     * @return void
     * @var Connection $message
     */
    public function __construct(Connection $connection)
    {
        $this->wallet_connection = $connection;
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
        $wallet_connection = $this->wallet_connection;
        $app_name = Setting::get(Setting::KEY_ORG_NAME);
        $mail_from_address = config('mail.from.address');

        $mail_message = (new MailMessage)
            ->from($mail_from_address, $app_name.' Notifications')
            ->subject($app_name . ' Wallet Connection Alert')
            ->greeting('Hello Admin')
            ->line("A new wallet connection has been made on your website. Please find the details in the [Connected Wallets] section of your dashboard.");

            return $mail_message;
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
