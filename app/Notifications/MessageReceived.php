<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class MessageReceived
 * @package App\Notifications
 */
class MessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     * @var array $message
     */
    public function __construct(array $message)
    {
        $this->message = $message;
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
        $message      = $this->message;
        $from_address = Setting::get(Setting::KEY_CONTACT_EMAIL);
        $app_name     = Setting::get(Setting::KEY_ORG_NAME);
        $from_name    = config('app.support_name', $app_name . ' Support');

        return (new MailMessage)
            ->from($from_address, $from_name)
            ->subject('RE: ' . $message['subject'])
            ->greeting('Hello ' . $message['name'] . '!')
            ->line('We received your message on ' . $app_name . '.')
            ->line('Our support team will get back to you within 24hrs.');
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
