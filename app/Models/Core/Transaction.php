<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Transaction
 * @method static create(array $array)
 * @method static whereIn(string $string, array $delete_targets)
 * @property Account account
 * @property double amount
 * @property Currency currency
 * @property double new_balance
 * @property Carbon created_at
 * @property mixed description
 * @property Model $item
 * @property mixed $item_type
 * @package App\Models\Core
 */
class Transaction extends Model
{
    protected $fillable = [
        'currency_id',
        'account_id',
        'item_id',
        'item_type',
        'amount',
        'local_amount',
        'new_balance',
        'new_local_balance',
        'description',
        'created_at',
        'updated_at'
    ];
    protected $table    = 'core_transactions';

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return MorphTo
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return string
     */
    public function effect(): string
    {
        return ($this->isCredit() ? 'Credit' : 'Debit');
    }

    /**
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    /**
     * @return string
     */
    public function itemDesc(): string
    {
        return is_object($this->item) ? ucwords($this->item_type) . '#' . (is_string($this->item->uuid) ? $this->item->uuid : $this->item->id) : 'n/a';
    }

    /**
     * @return string
     */
    public function amount(): string
    {
        return to_currency($this->amount, $this->currency->symbol, $this->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function localAmount(): string
    {
        return to_currency($this->local_amount, $this->account->currency->symbol, $this->account->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function newBalance()
    {
        return to_currency($this->new_balance, $this->currency->symbol, $this->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function newLocalBalance()
    {
        return to_currency($this->new_local_balance, $this->account->currency->symbol, $this->account->currency->minor_unit);
    }
}
