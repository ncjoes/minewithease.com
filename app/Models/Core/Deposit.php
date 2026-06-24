<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Interfaces\Payable;
use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Models\Model;
use App\Traits\Models\FindByStatus;
use App\Traits\Models\FindByUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Deposit
 * @property mixed amount
 * @property Currency currency
 * @property mixed id
 * @property mixed uuid
 * @property Channel channel
 * @property User user
 * @property double local_amount
 * @property mixed admin_url
 * @property mixed url
 * @property mixed $status
 * @property Carbon verified_at
 * @package App\Models\Core
 */
class Deposit extends Model implements Payable
{
    use FindByUUID;
    use FindByStatus;

    const S_PENDING  = 1;
    const S_PAID_IN  = 2;
    const S_VERIFIED = 3;
    const S_REVERSED = 4;
    const S_CANCELED = 5;

    protected $fillable = [
        'currency_id',
        'account_id',
        'uuid',
        'amount',
        'local_amount',
        'trans_ref',
        'status',
        'verified_at',
    ];
    protected $casts    = [
        'verified_at'   =>  'datetime',
    ];

    protected $table    = 'core_deposits';

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
     * @return MorphMany
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'item');
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('core.client.deposit.view', ['deposit' => $this->uuid]);
    }

    public function getExpiresAtAttribute()
    {
        $max_validity_period = (int) Setting::get(Setting::KEY_INVOICE_MAX_VALIDITY, 45);

        return $this->created_at->copy()->addMinutes($max_validity_period);
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
    public function status(): string
    {
        switch ($this->status) {
            case self::S_PENDING :
                $str = 'Pending';
                break;
            case self::S_PAID_IN :
                $str = 'Processing';
                break;
            case self::S_VERIFIED :
                $str = 'Verified';
                break;
            case self::S_REVERSED :
                $str = 'Reversed';
                break;
            case self::S_CANCELED :
                $str = 'Canceled';
                break;
            default :
                $str = 'unknown';
        }

        return $str;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->status == self::S_VERIFIED;
    }

    /**
     * @return bool
     */
    public function isCancelable(): bool
    {
        return ($this->status == self::S_PENDING);
    }

    /**
     * @return bool
     */
    public function isProcessing(): bool
    {
        return ($this->status == self::S_PAID_IN);
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return ($this->status == self::S_PENDING);
    }

    /**
     * @return string
     */
    public function getAdminUrlAttribute() : string
    {
        return route('core.admin.deposit.view', ['deposit' => $this->getRouteKey()]);
    }
}
