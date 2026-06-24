<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Interfaces\HasImageAttributes;
use App\Models\Model;
use App\Traits\Models\HasImageAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Slide
 * @property mixed edit_url
 * @package App\Models\CMS
 * @method static withTrashed()
 */

class Slide extends Model implements HasImageAttributes
{
    use HasImageAttribute;
    use SoftDeletes;
    use HasFactory;

    protected $table    = 'cms_slides';
    protected $fillable = [
        'title',
        'description',
        'photo',
        'action_url',
        'action_label',
        'cardinality',
    ];

    protected $casts = ['deleted_at'=>'datetime'];


    /**
     * @param string $attribute
     * @return string
     */
    public static function imageDir($attribute = 'photo'): string
    {
        return 'public' . DS . 'slides';
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function defaultImage($attribute = 'photo'): string
    {
        return 'slider-' . ((rand(1, 32) % 3)) . '.png';
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
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.slide.update', ['slide' => $this->id]);
    }

}
