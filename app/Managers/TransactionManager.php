<?php
declare(strict_types=1);

namespace App\Managers;

use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\Core\Bonus;
use App\Models\Core\Deposit;
use App\Models\Core\Portfolio;
use App\Models\Core\Swap;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Currency;
use App\Traits\Controllers\ResolveExchangeRate;
use Carbon\Carbon;

/**
 * Class TransactionManager
 * @package App\Managers
 */
abstract class TransactionManager
{
    use ResolveExchangeRate;
    
    /**
     * @param User $user
     * @param $amount
     * @return array
     */
    public static function creditTrialBalance(Account $account, $amount): array
    {
        $base_currency = Currency::getDefault();
        $local_amount   = self::getExchangeValue($base_currency->alpha_code, $account->currency->alpha_code, $amount, $account->currency->minor_unit);

        $transaction      = Transaction::create([
            'currency_id' => $base_currency->id,
            'account_id'  => $account->id,
            'item_id'     => $account->id,
            'item_type'   => Account::morphKey(),
            'amount'      => $amount,
            'local_amount'=> $local_amount,
            'new_balance' => round($account->balance + $amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $local_amount, $account->currency->minor_unit),
            'description' => "Trial Account Funding",
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Account $account
     * @param $amount
     * @param string $description
     * @return array
     */
    public static function reconcileAccount(Account $account, $amount, Carbon $datetime, string $description = "Account Reconciliation"): array
    {
        $base_currency = Currency::getDefault();
        $local_amount   = self::getExchangeValue($base_currency->alpha_code, $account->currency->alpha_code, $amount, $account->currency->minor_unit);

        $transaction      = Transaction::create([
            'currency_id' => $base_currency->id,
            'account_id'  => $account->id,
            'item_id'     => $account->id,
            'item_type'   => Account::morphKey(),
            'description' => $description,
            'amount'      => $amount,
            'local_amount'=> $local_amount,
            'new_balance' => round($account->balance + $amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $local_amount, $account->currency->minor_unit),
            'created_at'  => $datetime,
            'updated_at'  => $datetime,
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Deposit $deposit
     * @param User $user
     * @return array
     */
    public static function logDeposit(Deposit $deposit): array
    {
        $base_currency = $deposit->currency;
        $account          = $deposit->account;

        $transaction      = Transaction::create([
            'currency_id' => $base_currency->id,
            'account_id'  => $account->id,
            'item_id'     => $deposit->id,
            'item_type'   => Deposit::morphKey(),
            'description' => "Fund deposit ({$deposit->uuid})",
            'amount'      => $deposit->amount,
            'local_amount'=> $deposit->local_amount,
            'new_balance' => round($account->balance + $deposit->amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $deposit->local_amount, $account->currency->minor_unit),
            'created_at'  => $deposit->verified_at,
            'updated_at'  => $deposit->verified_at,
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Deposit $deposit
     * @param User $user
     * @return array
     */
    public static function logDepositReversal(Deposit $deposit, User $user): array
    {
        $base_currency = $deposit->currency;
        $account          = $deposit->account;

        $transaction      = Transaction::create([
            'currency_id' => $deposit->currency->id,
            'account_id'  => $account->id,
            'item_id'     => $deposit->id,
            'item_type'   => Deposit::morphKey(),
            'description' => "Reversal of fund deposit ({$deposit->uuid})",
            'amount'      => -$deposit->amount,
            'local_amount'=> -$deposit->local_amount,
            'new_balance' => -round($account->balance - $deposit->amount, $base_currency->minor_unit),
            'new_local_balance' => -round($account->local_balance - $deposit->local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Bonus $bonus
     * @return array
     */
    public static function logBonus(Bonus $bonus): array
    {
        $account = $bonus->account;
        $amount  = $bonus->amount;
        $base_currency = $bonus->currency;

        //Credit
        $transaction = Transaction::create([
            'currency_id' => $bonus->currency->id,
            'account_id'  => $account->id,
            'item_id'     => $bonus->id,
            'item_type'   => Bonus::morphKey(),
            'description' => $bonus->description,
            'amount'      => +$amount,
            'local_amount'=> $bonus->local_amount,
            'new_balance' => round($account->balance + $bonus->amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $bonus->local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Bonus $bonus
     * @return array
     */
    public static function logBonusReversal(Bonus $bonus): array
    {
        $$account      = $bonus->account;
        $amount        = $bonus->amount;
        $base_currency = $bonus->currency;

        //Debit
        $transaction = Transaction::create([
            'currency_id' => $bonus->currency->id,
            'account_id'  => $account->id,
            'item_id'     => $bonus->id,
            'item_type'   => Bonus::morphKey(),
            'description' => 'Bonus Cancelled (' . $bonus->description . ')',
            'amount'      => -$amount,
            'local_amount'=> -$bonus->local_amount,
            'new_balance' => round($account->balance - $bonus->amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance - $bonus->local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Portfolio $portfolio
     * @param double $amount
     * @param double $local_amount
     * @param null $message
     * @return array
     */
    public static function logPortfolioDebit(Portfolio $portfolio, $amount, $local_amount, $message = null): array
    {
        $account = $portfolio->account;
        $local_currency = $account->currency;
        $base_currency = $portfolio->currency;
        $message = is_null($message) ? "#{$portfolio->uuid} - New Stake Activated!" : $message;

        $transaction = Transaction::create([
            'currency_id' => $base_currency->id,
            'account_id'  => $account->id,
            'item_id'     => $portfolio->id,
            'item_type'   => Portfolio::morphKey(),
            'description' => $message,
            'amount'      => -$amount,
            'local_amount'=> -$local_amount,
            'new_balance' => round($account->balance - $amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance - $local_amount, $local_currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Portfolio $portfolio
     * @param $amount
     * @param null $message
     * @return array
     */
    public static function logPortfolioCredit(Portfolio $portfolio, $amount, $message = null): array
    {
        $base_currency = $portfolio->currency;
        $account = $portfolio->account;
        $local_amount = self::getExchangeValue($base_currency->alpha_code, $account->currency->alpha_code, $amount, $account->currency->minor_unit);
        $message = is_null($message) ? "#{$portfolio->uuid} Earning on Stake." : $message;

        $transaction = Transaction::create([
            'currency_id' => $base_currency->id,
            'account_id'  => $account->id,
            'item_id'     => $portfolio->id,
            'item_type'   => Portfolio::morphKey(),
            'description' => $message,
            'amount'      => $amount,
            'local_amount'=> $local_amount,
            'new_balance' => round($account->balance + $amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Withdrawal $withdrawal
     * @return array
     */
    public static function logWithdrawal(Withdrawal $withdrawal): array
    {
        $account             = $withdrawal->account;
        $base_currency = $withdrawal->currency;

        $transaction = Transaction::create([
            'currency_id' => $withdrawal->currency->id,
            'account_id'     => $account->id,
            'item_id'     => $withdrawal->id,
            'item_type'   => Withdrawal::morphKey(),
            'description' => "Fund withdrawal request {$withdrawal->uuid}",
            'amount'      => -$withdrawal->amount,
            'local_amount'=> -$withdrawal->local_amount,
            'new_balance' => round($account->balance - $withdrawal->amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance - $withdrawal->local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    /**
     * @param Withdrawal $withdrawal
     * @param null $message
     * @return array
     */
    public static function logWithdrawalReversal(Withdrawal $withdrawal, $message = null): array
    {
        $account             = $withdrawal->account;
        $message          = is_null($message) ? "Withdrawal Revered - {$withdrawal->uuid}" : $message;
        $base_currency = Currency::getDefault();

        $transaction = Transaction::create([
            'currency_id' => $withdrawal->currency->id,
            'account_id'  => $account->id,
            'item_id'     => $withdrawal->id,
            'item_type'   => Withdrawal::morphKey(),
            'description' => $message,
            'amount'      => $withdrawal->amount,
            'local_amount'=> $withdrawal->local_amount,
            'new_balance' => round($account->balance + $withdrawal->amount, $base_currency->minor_unit),
            'new_local_balance' => round($account->local_balance + $withdrawal->local_amount, $account->currency->minor_unit),
        ]);
        $account->updateBalances();

        return [$transaction];
    }

    public static function logSwap($swap): array
    {
        $base_currency = $swap->currency;
        $message = "Currency Swap - {$swap->uuid}";

        $transaction1 = Transaction::create([
            'currency_id' => $swap->currency_id,
            'account_id'  => $swap->source_account_id,
            'item_id'     => $swap->id,
            'item_type'   => Swap::morphKey(),
            'description' => $message,
            'amount'      => -$swap->amount,
            'local_amount'=> -$swap->source_local_amount,
            'new_balance' => round($swap->sourceAccount->balance - $swap->amount, $base_currency->minor_unit),
            'new_local_balance' => round($swap->sourceAccount->local_balance - $swap->source_local_amount, $swap->sourceAccount->currency->minor_unit),
        ]);
        $swap->sourceAccount->updateBalances();

        $transaction2 = Transaction::create([
            'currency_id' => $swap->currency_id,
            'account_id'  => $swap->destination_account_id,
            'item_id'     => $swap->id,
            'item_type'   => Swap::morphKey(),
            'description' => $message,
            'amount'      => +$swap->amount,
            'local_amount'=> +$swap->destination_local_amount,
            'new_balance' => round($swap->destinationAccount->balance + $swap->amount, $base_currency->minor_unit),
            'new_local_balance' => round($swap->destinationAccount->local_balance + $swap->destination_local_amount, $swap->destinationAccount->currency->minor_unit),
        ]);
        $swap->destinationAccount->updateBalances();

        $swap->update(['status' => Swap::S_COMPLETED]);

        return [$transaction1, $transaction2];
    }
}
