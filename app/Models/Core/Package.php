<?php
declare(strict_types=1);

namespace App\Models\Core;

use App\Interfaces\HasImageAttributes;
use App\Models\ETC\Currency;
use App\Models\Model;
use App\Traits\Models\HasImageAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Package
 * @property mixed min_duration
 * @property mixed max_duration
 * @property int referral_bonus_count
 * @property double referral_bonus_rate
 * @property int referral_bonus_release_time
 * @property int min_amount
 * @property int max_amount
 * @property boolean is_active
 * @property int interest_interval
 * @property float min_interest_rate
 * @property float max_interest_rate
 * @property mixed $service_charge_rate
 * @package App\Models\Core
 */
class Package extends Model implements HasImageAttributes
{
    use HasImageAttribute;
    use HasFactory;

    protected Currency $currency;

    protected $table    = 'core_packages';
    protected $fillable = [
        'name',
        'description',
        'photo',
        'max_amount',
        'min_amount',
        'split_amount',
        'service_charge_rate',
        'referral_bonus_rate',
        'referral_bonus_count',
        'referral_bonus_release_time',
        'min_interest_rate',
        'max_interest_rate',
        'interest_interval',
        'min_duration',
        'max_duration',
        'is_active',
    ];
    protected $casts    = [
        'is_active' => 'boolean',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->currency = Currency::getDefault();
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function imageDir($attribute = 'photo'): string
    {
        return 'public' . DS . 'packages' . DS . $attribute;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function defaultImage($attribute = 'photo'): string
    {
        return 'package-' . (rand(1, 32) % 3) . '.svg';
    }

    /**
     * @return Builder
     */
    public static function activeOnly(): Builder
    {
        return self::where('is_active', true);
    }

    /**
     * @return HasMany
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
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
    public function minAmount($unit = null): string
    {
        return to_currency($this->min_amount, $this->currency->symbol, $unit ?? $this->currency->minor_unit);
    }

    /**
     * @return string
     */
    public function maxAmount($unit = null): string
    {
        return to_currency($this->max_amount, $this->currency->symbol, $unit ?? $this->currency->minor_unit);
    }

    public function getInterestRateStr()
    {
        return ($this->min_interest_rate == $this->max_interest_rate ? $this->min_interest_rate.'%' : $this->min_interest_rate.'-'.$this->max_interest_rate.'%');
    }
    
    public function getReferralBonusRates(): array
    {
        $arr = explode(',', (string)$this->referral_bonus_rate);
        array_walk($arr, function (&$item, $index) {
            $item = (int)trim($item);
        });

        return $arr;
    }

    public function getReferralBonusRate($index = 0)
    {
        $arr = $this->getReferralBonusRates();
        return array_key_exists($index, $arr) ? $arr[$index] : 0;
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
    public function getEditUrlAttribute(): string
    {
        return route('core.admin.package.update', ['package' => $this->id]);
    }
}
