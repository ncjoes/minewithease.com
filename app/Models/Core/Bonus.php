<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\FindByStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Bonus
 * @property Currency currency
 * @property mixed amount
 * @property mixed description
 * @property mixed id
 * @property Account account
 * @property string status
 * @package App\Models\Core
 * @method static create(array $array)
 */
class Bonus extends Model
{
    use FindByStatus;

    const S_PENDING  = "1";
    const S_APPROVED = "2";
    const S_RELEASED = "3";
    const S_CANCELED = "0";

    protected $table    = 'core_bonuses';
    protected $fillable = [
        'currency_id',
        'account_id',
        'amount',
        'local_amount',
        'description',
        'item_id',
        'item_type',
        'due_from',
        'status',
    ];
    protected $casts = [
        'due_from'  =>  'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->account->user();
    }

    /**
     * @return MorphTo
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphMany
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'item');
    }

    /**
     * @return string
     */
    public function amount(): string
    {
        return to_currency($this->amount, $this->currency->symbol, $this->currency->minor_unit);
    }

    /**
     * @return mixed
     */
    public function localAmount()
    {
        return to_currency($this->local_amount, $this->account->currency->symbol, $this->account->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return self::states()[$this->status];
    }

    public static function states(): array
    {
        return [
            self::S_PENDING  => 'Pending',
            self::S_APPROVED => 'Approved',
            self::S_RELEASED => 'Released',
            self::S_CANCELED => 'Cancelled',
        ];
    }
}
