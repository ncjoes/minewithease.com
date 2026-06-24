<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Interfaces\HasImageAttributes;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\HasImageAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Channel
 * @property mixed edit_url
 * @property Currency currency
 * @property mixed min_amount
 * @property mixed max_amount
 * @property boolean is_active
 * @property boolean for_inflow
 * @property boolean for_outflow
 * @property mixed split_amount
 * @property mixed $payment_wallet
 * @package App\Models\Core
 */
class Channel extends Model implements HasImageAttributes
{
    use HasImageAttribute;
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'name',
        'photo',
        'website',
        'min_amount',
        'max_amount',
        'split_amount',
        'description',
        'payment_wallet',
        'is_active',
        'for_inflow',
        'for_outflow',
        'wallet_address_placeholder',
        'wallet_address_format',
        'rank',
    ];
    protected $casts    = [
        'is_active'   => 'boolean',
        'for_inflow'  => 'boolean',
        'for_outflow' => 'boolean',
    ];
    protected $table    = 'core_channels';

    /**
     * @return Builder
     */
    public static function activeOnly(): Builder
    {
        return self::where('is_active', true);
    }

    /**
     * @return mixed
     */
    public static function forDeposits()
    {
        return self::where(['is_active' => true, 'for_inflow' => true]);
    }

    /**
     * @return mixed
     */
    public static function forWithdrawals()
    {
        return self::where(['is_active' => true, 'for_outflow' => true]);
    }

    /**
     * @param string $attribute
     *
     * @return string
     */
    public static function imageDir($attribute = 'photo'): string
    {
        return 'public' . DS . 'channels' . DS . $attribute;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function defaultImage($attribute = 'photo'): string
    {
        return 'channel.png';
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
    public function getPhotoUrlAttribute(): string
    {
        return asset($this->getImageUrl('photo'));
    }

    /**
     * @return string
     */
    public function minAmountStr(): string
    {
        $default_currency = Currency::getDefault();

        return to_currency($this->min_amount, $default_currency->symbol, $default_currency->minor_unit);
    }

    /**
     * @return mixed
     */
    public function minAmount()
    {
        return $this->min_amount;
    }

    /**
     * @return string
     */
    public function maxAmountStr(): string
    {
        $default_currency = Currency::getDefault();

        return to_currency($this->max_amount, $default_currency->symbol, $default_currency->minor_unit);
    }

    /**
     * @return mixed
     */
    public function maxAmount()
    {
        return $this->max_amount;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->is_active ? 'Yes' : 'No';
    }

    /**
     * @return string
     */
    public function inflow(): string
    {
        return $this->for_inflow ? 'Yes' : 'No';
    }

    /**
     * @return string
     */
    public function outflow(): string
    {
        return $this->for_outflow ? 'Yes' : 'No';
    }

    /**
     * @return string
     */
    public function getEditUrlAttribute(): string
    {
        return route('core.admin.channel.update', ['channel' => $this->id]);
    }
}
