<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Models\Core\Channel;
use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Currency
 * @property mixed is_active
 * @property mixed id
 * @property mixed symbol
 * @property mixed minor_unit
 * @property mixed alpha_code
 * @package App\Models\ETC
 */
class Currency extends Model
{
    const BTC     = 'BTC';
    const DEFAULT = 'USD';

    protected $table    = 'etc_currencies';
    protected $fillable = [
        'num_code',
        'alpha_code',
        'minor_unit',
        'name',
        'symbol',
        'is_active',
        'is_crypto',
    ];
    protected $casts    = [
        'is_active' => 'boolean',
        'is_crypto' => 'boolean',
    ];

    public static function findByAlphaCode($code)
    {
        return static::findByColumn('alpha_code', $code)->first();
    }

    /**
     * @return Builder
     */
    public static function activeOnly(): Builder
    {
        return self::where('is_active', true);
    }

    /**
     * @return self
     */
    public static function getDefault(): Currency
    {
        $default = Setting::get(Setting::KEY_DEFAULT_CURRENCY);

        return self::findByColumn('alpha_code', $default)->first();
    }

    /**
     * @return BelongsToMany
     */
    public function activeCountries(): BelongsToMany
    {
        return $this->countries()->where('is_active', true);
    }

    /**
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'etc_country_currency');
    }

    /**
     * @return HasMany
     */
    public function depositChannels(): HasMany
    {
        return $this->channels()->where('for_inflow', true);
    }

    /**
     * @return HasMany
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * @return HasMany
     */
    public function withdrawalChannels(): HasMany
    {
        return $this->channels()->where('for_outflow', true);
    }

    /**
     * @param $val
     * @return mixed
     */
    public function getSymbolAttribute($val)
    {
        return is_null($val) ? $this->alpha_code : $val;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->is_active ? 'Yes' : 'No';
    }
}
