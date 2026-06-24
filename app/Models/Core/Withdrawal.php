<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Models\Auth\User;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\FindByStatus;
use App\Traits\Models\FindByUUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Withdrawal
 * @property Currency currency
 * @property mixed status
 * @property Channel channel
 * @property Account account
 * @property mixed uuid
 * @property double amount
 * @property double local_amount
 * @property mixed $trans_ref
 * @package App\Models\Core
 */
class Withdrawal extends Model
{
    use FindByUUID;
    use FindByStatus;

    const S_PENDING  = 1;
    const S_APPROVED = 2;
    const S_DECLINED = 3;
    const S_FAILED   = 4;
    const S_PAID_OUT = 5;
    const S_CANCELED = 6;

    protected $table    = 'core_withdrawals';
    protected $fillable = [
        'currency_id',
        'account_id',
        'uuid',
        'amount',
        'local_amount',
        'payment_wallet',
        'trans_ref',
        'status',
        'progress_value',
        'progress_description',
        'processed_at',
    ];

    protected $casts    = [
        'verified_at'   =>  'datetime',
        'processed_at'  =>  'datetime',
        'progress_value' =>  'float',
        'amount' => 'float',
        'local_amount' => 'float',
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
            case self::S_APPROVED:
                $str = 'Approved';
                break;
            case self::S_DECLINED:
                $str = 'Declined';
                break;
            case self::S_FAILED:
                $str = 'Failed';
                break;
            case self::S_PAID_OUT :
                $str = 'Paid';
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
     * @return string
     */
    public function userStatus(): string
    {
        switch ($this->status) {
            case self::S_DECLINED:
                $str = 'Declined';
                break;
            case self::S_PENDING :
                $str = 'Pending';
                break;
            case self::S_APPROVED:
                $str = 'Processing';
                break;
            case self::S_FAILED:
                $str = 'Failed';
                break;
            case self::S_PAID_OUT :
                $str = 'Paid';
                break;
            case self::S_CANCELED :
                $str = 'Canceled';
                break;
            default :
                $str = 'unknown';
        }

        return $str;
    }

    public function getTransReference()
    {
        return $this->trans_ref;
    }

    /**
     * @return string
     */
    public function transRefUrl(): string
    {
        return 'https://www.blockchain.com/' . $this->currency->alpha_code . '/tx/' . $this->trans_ref;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return ($this->status == self::S_PAID_OUT);
    }

    /**
     * @return bool
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, [self::S_PENDING]);
    }


    public function isApproved(): bool
    {
        return $this->status == self::S_APPROVED;
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('core.client.withdrawal.view', ['withdrawal' => $this->getRouteKey()]);
    }

    /**
     * @return string
     */
    public function getAdminUrlAttribute(): string
    {
        return route('core.admin.withdrawal.view', ['withdrawal' => $this->getRouteKey()]);
    }
}
