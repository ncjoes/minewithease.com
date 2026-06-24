<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Models\Model;
use App\Traits\Models\FindByISO2;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Continent
 * @package App\Models\ETC
 */
class Continent extends Model
{
    use FindByISO2;

    protected $table    = 'etc_continents';
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}
