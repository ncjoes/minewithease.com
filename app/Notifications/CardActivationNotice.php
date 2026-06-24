<?php

namespace App\Notifications;

use App\Models\Core\Card;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CardActivationNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public Card $card;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
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
        $mail_from_address = config('mail.from.address');
        $app_name          = Setting::get(Setting::KEY_ORG_NAME);

        return (new MailMessage)
            ->from($mail_from_address, $app_name.' Accounting')
            ->subject('Congratulation! Your Web3 Card Has Been Activated')
            ->greeting('Hello, '.$notifiable->name.' (#'.$notifiable->uuid.')')
            ->line('This is to notify you that your Web3 card has been successfully activated.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for being a part of our community!');
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
