<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\FindByStatus;
use App\Traits\Models\FindByUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Portfolio
 * @property Currency currency
 * @property Package package
 * @property Carbon created_at
 * @property Account account
 * @property Collection transactions
 * @property Carbon last_rewarded_at
 * @property Carbon $expires_at
 * @property int $max_duration
 * @property int $interest_interval
 * @property float $min_interest_rate
 * @property float $max_interest_rate
 * @package App\Models\Core
 */
class Portfolio extends Model
{
    use FindByUUID;
    use FindByStatus;

    const S_ACTIVE    = 1;
    const S_COMPLETED = 2;
    const S_CLOSED    = 3;

    protected $table    = 'core_portfolios';
    protected $fillable = [
        'uuid',
        'currency_id',
        'account_id',
        'package_id',
        'amount',
        'local_amount',
        'min_interest_rate',
        'max_interest_rate',
        'interest_interval',
        'min_duration',
        'max_duration',
        'service_charge_rate',
        'created_at',
        'updated_at',
        'expires_at',
        'last_rewarded_at',
        'status',
    ];

    protected $casts    = [
        'expires_at'    =>  'datetime',
        'last_rewarded_at'  =>  'datetime',
    ];

    /**
     * @param $accountId
     * @return string
     */
    public static function generateUUID($accountId): string
    {
        $random_characters = self::makeUID(2, 3);
        $uuid              = $accountId . '-' . $random_characters;

        if (is_object(self::findByUUID($uuid))) {
            return self::generateUUID($accountId);
        }

        return $uuid;
    }

    /**
     * @return Builder
     */
    public static function activeOnly(): Builder
    {
        return self::findByStatus([self::S_ACTIVE]);
    }

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
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
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
     * @return string
     */
    public function localAmount(): string
    {
        return to_currency($this->local_amount, $this->account->currency->symbol, $this->account->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function profit(): string
    {
        return to_currency($this->calculateProfit(), $this->currency->symbol, $this->currency->minor_unit);
    }


    public function calculateProfit()
    {
        return $this->transactions()->where('amount', '>', 0)->whereNot('amount', $this->amount)->sum('amount');
    }

    /**
     * @return string
     */
    public function status(): string
    {
        switch ($this->status) {
            case self::S_ACTIVE :
                $str = 'Active';
                break;
            case self::S_COMPLETED :
                $str = 'Completed';
                break;
            case self::S_CLOSED :
                $str = 'Closed';
                break;
            default :
                $str = 'unknown';
        }

        return $str;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == self::S_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status == self::S_COMPLETED;
    }

    /**
     * @return float|int
     */
    public function progress()
    {
        $raw_progress = ($this->age() / $this->package->max_duration);

        return min($raw_progress, 1);
    }

    /**
     * @return mixed
     */
    public function age()
    {
        $now     = Carbon::now();
        $raw_age = $this->created_at->diffInDays($now, false);

        return min($raw_age, $this->max_duration);
    }

    /**
     * @return bool
     */
    public function isCancellable(): bool
    {
        $now = Carbon::now();

        return $this->status == self::S_ACTIVE && $now->greaterThan($this->created_at->addDays($this->package->min_duration));
    }

    public function getInterestRateAttribute()
    {
        return ($this->min_interest_rate == $this->max_interest_rate) ? $this->min_interest_rate : $this->min_interest_rate . ' - ' . $this->max_interest_rate;
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('core.client.portfolio.view', ['portfolio' => $this->uuid]);
    }

    /**
     * @return string
     */
    public function getAdminUrlAttribute(): string
    {
        return route('core.admin.portfolio.view', ['portfolio' => $this->getRouteKey()]);
    }
}
