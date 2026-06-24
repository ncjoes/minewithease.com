<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Models\Auth\User;
use App\Models\Model;
use App\Traits\Models\FindByName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Region
 * @package App\Models\ETC
 */
class Region extends Model
{
    use FindByName;

    protected $table    = 'etc_regions';
    protected $fillable = [
        'country_id',
        'name',
        'capital',
    ];

    /**
     * @return array
     */
    public static function names()
    {
        return static::all()->pluck('name', 'id')->all();
    }

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
