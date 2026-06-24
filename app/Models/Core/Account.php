<?php

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $table = 'core_accounts';

    protected $fillable = [
        'user_id',
        'currency_id',
        'wallet_address',
        'balance',
        'local_balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'double',
        'local_balance' => 'double',
        'is_active' => 'boolean',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function channel()
    {
        return $this->currency->channels()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function updateBalance()
    {
        $this->update(['balance' => $this->calculateBalance()]);
    }

    public function updateLocalBalance()
    {
        $this->update(['local_balance' => $this->calculateLocalBalance()]);
    }

    public function updateBalances()
    {
        $this->update(['balance' => $this->calculateBalance(), 'local_balance' => $this->calculateLocalBalance()]);
    }

    public function calculateBalance()
    {
        $balance = $this->transactions()->sum('amount');

        return (float)$balance;
    }

    public function calculateLocalBalance()
    {
        $balance = $this->transactions()->sum('local_amount');

        return (float)$balance;
    }

    public function balance(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->balance, $currency->symbol, $currency->minor_unit);
    }

    public function localBalance(): string
    {
        return to_currency($this->local_balance, $this->currency->symbol, $this->currency->minor_unit);
    }

    public function photo_url(): string
    {
        $channel = $this->currency->channels()->firstOrNew();
        return $channel->photo_url;
    }
}
