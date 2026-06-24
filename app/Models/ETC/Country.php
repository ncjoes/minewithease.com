<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Models\Auth\User;
use App\Models\Model;
use App\Traits\Models\FindByISO2;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Country
 * @property boolean is_active
 * @property string iso2
 * @property Collection activeCurrencies
 * @property mixed id
 * @package App\Models\ETC
 */
class Country extends Model
{
    use FindByISO2;

    protected $table    = 'etc_countries';
    protected $fillable = [
        'continent_id',
        'name',
        'iso2',
        'iso3',
        'is_active',
    ];
    protected $casts    = [
        'is_active' => 'boolean'
    ];

    /**
     * @return Builder
     */
    public static function activeOnly()
    {
        return self::where('is_active', true);
    }

    /**
     * @return BelongsTo
     */
    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    /**
     * @return BelongsToMany
     */
    public function activeCurrencies()
    {
        return $this->currencies()->where('is_active', true);
    }

    /**
     * @return BelongsToMany
     */
    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'etc_country_currency');
    }

    /**
     * @return HasMany
     */
    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return string
     */
    public function status()
    {
        return $this->is_active ? 'Yes' : 'No';
    }
}
