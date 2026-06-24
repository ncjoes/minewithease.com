<?php

namespace App\Notifications;

use App\Models\Auth\User;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeMessage extends Notification
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user              = $this->user;
        $mail_from_address = config('mail.from.address');
        $app_name          = Setting::get(Setting::KEY_ORG_NAME);

        return (new MailMessage)
            ->from($mail_from_address, $app_name . ' Team')
            ->subject('Welcome to '.$app_name.'!')
            ->greeting('Hello ' . $notifiable->name)
            ->greeting('Welcome to ' . $app_name)
            ->line('We are thrilled to have you on board! With ' . $app_name . ', you can securely store and manage your digital assets in one place.')
            ->line('Our platform offers a user-friendly interface, robust security features, and seamless integration with various blockchain networks to ensure that your experience is both enjoyable and secure.')
            ->line('To get started, simply log in to your account and explore the features we have to offer.')
            ->action('Login', route('core.client.dashboard'))
            ->line('If you have any questions or need assistance, our support team is here to help. Feel free to reach out to us at any time.')
            ->line('Thank you for joining our community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
