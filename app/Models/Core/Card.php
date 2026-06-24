<?php

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

class Card extends Model implements Payable
{
    use FindByUUID;
    use FindByStatus;

    const S_ACTIVATED = 1;
    const S_PENDING = 0;
    const S_CANCELLED = -1;
    const S_DEACTIVATED = 2;
    const S_VERIFIED = 3;
    const S_PAID_IN = 4;

    protected $table = 'core_cards';

    protected $fillable = [
        'uuid',
        'channel_id',
        'user_id',
        'amount',
        'local_amount',
        'payment_reference',
        'name',
        'email',
        'phone',
        'address',
        'status',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'double',
        'local_amount' => 'double',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * @param $accountId
     * @return string
     */
    public static function generateUUID($accountId): string
    {
        $random_characters = self::makeUID(4, 6);
        $uuid              = $accountId . '-' . $random_characters;

        if (is_object(self::findByUUID($uuid))) {
            return self::generateUUID($accountId);
        }

        return $uuid;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function amount(): string
    {
        $default_currency = Currency::getDefault();
        return to_currency($this->amount, $default_currency->symbol, $default_currency->minor_unit);
    }

    /**
     * @return string
     */
    public function localAmount(): string
    {
        return to_currency($this->local_amount, $this->channel->currency->symbol, $this->channel->currency->minor_unit);
    }

    public static function states(): array
    {
        return [
            self::S_ACTIVATED => 'Activated',
            self::S_DEACTIVATED => 'De-activated',
            self::S_CANCELLED => 'Cancelled',
            self::S_PENDING => 'Pending',
            self::S_VERIFIED => 'Verified',
            self::S_PAID_IN => 'Paid-In',
        ];
    }

    public function status(): string
    {
        $states = self::states();
        return $states[$this->status] ?? 'Unknown';
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

    public function getExpiresAtAttribute()
    {
        $max_validity_period = (int)Setting::get(Setting::KEY_INVOICE_MAX_VALIDITY, 45);

        return $this->created_at->copy()->addMinute($max_validity_period);
    }

    public function getUrlAttribute(): string
    {
        return route('core.client.card.view-invoice', ['card' => $this->uuid]);
    }

    public function getAdminUrlAttribute(): string
    {
        return route('core.admin.card.view', ['card' => $this->getRouteKey()]);
    }
}
