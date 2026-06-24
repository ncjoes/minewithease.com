<?php

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\FindByUUID;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Swap extends Model
{
    use FindByUUID;

    const S_PENDING = 0;
    const S_COMPLETED = 1;
    const S_CANCELLED = 2;

    protected $table = 'core_swaps';

    protected $fillable = [
        'uuid',
        'currency_id',
        'user_id',
        'source_account_id',
        'destination_account_id',
        'amount',
        'source_local_amount',
        'destination_local_amount',
        'status',
    ];

    public static function generateUUID($accountId): string
    {
        $random_characters = self::makeUID(2, 3);
        $uuid              = $accountId . '-' . $random_characters;

        if (is_object(self::findByUUID($uuid))) {
            return self::generateUUID($accountId);
        }

        return $uuid;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceAccount()
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }

    public function amount(): string
    {
        return to_currency($this->amount, $this->currency->symbol, $this->currency->minor_unit);
    }

    public function sourceLocalAmount(): string
    {
        $sourceAccount = $this->sourceAccount;

        return to_currency($this->source_local_amount, $sourceAccount->currency->symbol, $sourceAccount->currency->minor_unit);
    }

    public function destinationLocalAmount(): string
    {
        $destinationAccount = $this->destinationAccount;

        return to_currency($this->destination_local_amount, $destinationAccount->currency->symbol, $destinationAccount->currency->minor_unit);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'item');
    }

    public static function states(): array
    {
        return [
            self::S_PENDING => 'Pending',
            self::S_COMPLETED => 'Completed',
            self::S_CANCELLED => 'Cancelled',
        ];
    }

    public function status(): string
    {
        $states = self::states();
        return $states[$this->status] ?? 'Unknown';
    }

    public function isPending(): bool
    {
        return $this->status == self::S_PENDING;
    }

}
