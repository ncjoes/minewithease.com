<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class ContactFormMessage
 * @package App\Notifications
 */
class ContactFormMessage extends Notification implements ShouldQueue
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
        $message  = $this->message;
        $app_name = Setting::get(Setting::KEY_ORG_NAME);

        $mail_message = (new MailMessage)
            ->from($message['email'], $message['name'])
            ->subject($app_name . ' Contact Form Message from ' . $message['name'])
            ->greeting('Hello Admin');

        foreach (explode("\n\r", $message['message']) as $line) {
            $mail_message->line($line);
        }

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
