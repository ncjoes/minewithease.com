<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Models\Auth\User;
use App\Models\Core\Deposit;
use App\Models\Core\Portfolio;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NewTransaction
 * @package App\Notifications
 */
class NewTransaction extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;
    protected Transaction $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     * @var Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->user = $transaction->account->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return [
            //'mail',
            'database',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $app_name = Setting::get(Setting::KEY_ORG_NAME);
        $app_domain = config('app.domain');
        $user = $this->user;
        $transaction = $this->transaction;
        $action_url = $this->getActionUrl();
        $notifiable_name = $notifiable->first_name;

        return (new MailMessage)
            ->from('no-reply@' . $app_domain, $app_name . ' Accounting')
            ->subject($app_name . ' Transaction Alert [' . $transaction->effect() . ': ' . $transaction->amount() . ']')
            ->greeting('Hello, ' . $notifiable_name . ' (#' . $user->uuid . ')')
            ->line('This is a transaction notification on your ' . $app_name . ' account.')
            ->line('Type: ' . $transaction->effect())
            ->line('Time-Date: ' . date_time_for_humans($transaction->created_at))
            ->line('Description: ' . $transaction->description)
            ->line('Trans. Amount: ' . $transaction->amount())
            ->line('New Account Balance: ' . $transaction->newBalance())
            ->line('Visit your dashboard for more details.')
            ->action('Go to Dashboard', $action_url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toDatabase(mixed $notifiable): array
    {
        $transaction = $this->transaction;

        return [
            'title' => "{$transaction->amount()} {$transaction->effect()} on your account",
            'message' => "{$transaction->description}",
            'action_url' => $this->getActionUrl(),
        ];
    }

    protected function getActionUrl(): ?string
    {
        $transaction = $this->transaction;
        $item_type = $transaction->item_type;
        $action_url = null;

        switch ($item_type) {
            case Deposit::morphKey():
                $action_url = route('core.client.deposit.view', ['deposit' => $transaction->item->uuid]);
                break;
            case Withdrawal::morphKey():
                $action_url = route('core.client.withdrawal.view', ['withdrawal' => $transaction->item->uuid]);
                break;
            case Portfolio::morphKey():
                $action_url = route('core.client.portfolio.view', ['portfolio' => $transaction->item->uuid]);
                break;
        }

        return $action_url;
    }
}
