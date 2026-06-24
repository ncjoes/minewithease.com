<?php
declare(strict_types=1);

namespace App\Managers;

use App\Interfaces\Payable;
use App\Models\Auth\User;
use App\Models\Core\Card;
use App\Models\Core\Deposit;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Setting;
use App\Notifications\AccountStatusChange;
use App\Notifications\CardActivationNotice;
use App\Notifications\ContactFormMessage;
use App\Notifications\MessageReceived;
use App\Notifications\NewPaymentClaim;
use App\Notifications\NewTransaction;
use App\Notifications\NewWithdrawalRequest;
use App\Notifications\PendingPayment;
use App\Notifications\VerificationNotice;
use App\Notifications\VerificationRequest;
use App\Notifications\VerifyEmail;
use App\Notifications\WalletConnectionAlert;
use App\Notifications\WelcomeMessage;
use App\Notifications\WithdrawalPaid;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

/**
 * Class NotificationManager
 * @package App\Managers
 */
abstract class NotificationManager
{
    public static function sendWelcomeMessage(User $user): void
    {
        $user->notify(new WelcomeMessage($user));
    }

    /**
     * @param array $array
     */
    public static function sendTransactionNotices(array $array = []): void
    {
        $array = Arr::flatten($array);

        foreach ($array as $transaction) {
            self::sendTransactionNotice($transaction);
        }
    }

    /**
     * @param Transaction $transaction
     */
    public static function sendTransactionNotice(Transaction $transaction): void
    {
        $user = $transaction->account->user;
        $user->notify(new NewTransaction($transaction));
    }

    /**
     * @param User $user
     * @return array
     */
    public static function sendEmailVerificationRequest(User $user): array
    {
        $user->notify(new VerifyEmail());
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }
        return ['status' => false, 'message' => "Notifications are disabled for intended recipient."];
    }

    /**
     * @param User $user
     * @return array
     */
    public static function sendProfileVerificationRequest(User $user): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $to_address = Setting::get(Setting::KEY_FINANCE_EMAIL);
            Notification::route('mail', $to_address)
                ->notify(new VerificationRequest($user));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }

        return ['status' => false, 'message' => "Notifications are currently disabled by System Administrator."];
    }

    /**
     * @param User $user
     * @return array
     */
    public static function sendProfileVerificationNotice(User $user): array
    {
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $user->notify(new VerificationNotice($user));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }
        return ['status' => false, 'message' => "Notifications are disabled for intended recipient."];
    }

    /**
     * @param User $user
     * @return array
     */
    public static function sendStatusChangeNotice(User $user): array
    {
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $user->notify(new AccountStatusChange($user));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }
        return ['status' => false, 'message' => "Notifications are disabled for intended recipient."];
    }

    /**
     * @param Deposit $deposit
     * @return array
     */
    public static function sendPendingDepositNotice(Payable $deposit): array
    {
        $user = $deposit->user;
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $user->notify(new PendingPayment($deposit));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }
        return ['status' => false, 'message' => "Notifications are disabled for intended recipient."];
    }

    /**
     * @param $input
     * @return array
     */
    public static function sendMessageReceivedNotice($input): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            Notification::route('mail', $input['email'])
                ->notify(new MessageReceived($input));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }

        return ['status' => false, 'message' => "Notifications are currently disabled by System Administrator."];
    }

    /**
     * @param $input
     * @return array
     */
    public static function sendWalletConnectionAlert($connection): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $to_address = Setting::get(Setting::KEY_CONTACT_EMAIL);
            Notification::route('mail', $to_address)->notify(new WalletConnectionAlert($connection));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }

        return ['status' => false, 'message' => "Notifications are currently disabled by System Administrator."];
    }

    /**
     * @param $input
     * @return array
     */
    public static function sendContactFormMessage($input): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $to_address = Setting::get(Setting::KEY_CONTACT_EMAIL);
            Notification::route('mail', $to_address)->notify(new ContactFormMessage($input));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }

        return ['status' => false, 'message' => "Notifications are currently disabled by System Administrator."];
    }

    /**
     * @param Payable $deposit
     * @return array
     */
    public static function sendPaymentClaimNotice(Payable $deposit): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $to_address = Setting::get(Setting::KEY_FINANCE_EMAIL);
            Notification::route('mail', $to_address)->notify(new NewPaymentClaim($deposit));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }

        return ['status' => false, 'message' => "Notifications are currently disabled by System Administrator."];
    }

    public static function sendWithdrawalRequestNotice(Withdrawal $withdrawal): array
    {
        if (Setting::get(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $to_address = Setting::get(Setting::KEY_FINANCE_EMAIL);
            Notification::route('mail', $to_address)->notify(new NewWithdrawalRequest($withdrawal));

            return ['status' => true, 'message' => 'Message Sent Successfully!'];
        }
        return [
            'status' => false, 'message' => "Notifications are currently disabled by System Administrator.",
        ];
    }

    /**
     * @param Withdrawal $withdrawal
     * @return array
     */
    public static function sendWithdrawalPaidNotice(Withdrawal $withdrawal): array
    {
        $user = $withdrawal->user;
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $user->notify(new WithdrawalPaid($withdrawal));

            return [
                'status' => true, 'message' => 'Message Sent Successfully!',
            ];
        }

        return ['status' => false, 'message' => "Notifications are disabled for intended recipient."];
    }

    public static function sendCardActivationNotice(Card $card): void
    {
        $user = $card->user;
        if ($user->getSetting(Setting::KEY_ENABLE_NOTIFICATIONS)) {
            $user->notify(new CardActivationNotice($card));
        }
    }
}
