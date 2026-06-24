<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Models\Model;
use App\Traits\Models\FindByName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class District
 * @package App\Models\ETC
 */
class District extends Model
{
    use FindByName;

    protected $table    = 'etc_districts';
    protected $fillable = [
        'name',
        'region_id',
    ];

    /**
     * @param Region|null $region
     * @return array
     */
    public static function names(Region $region = null)
    {
        if (is_object($region)) {
            return $region->districts->pluck('name', 'id')->all();
        }

        return static::all()->pluck('name', 'id')->all();
    }

    /**
     * @return BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
