<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Interfaces\HasImageAttributes;
use App\Models\Model;
use App\Traits\Models\FindBySlug;
use App\Traits\Models\HasImageAttribute;
use App\Traits\Models\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @property boolean is_published
 * @property Category category
 * @property boolean system_defined
 * @property mixed url
 * @property mixed edit_url
 * @property mixed title
 * @property mixed $slug
 * Class Page
 * @package App\Models\CMS
 */
class Page extends Model implements HasImageAttributes
{
    use FindBySlug;
    //use SoftDeletes;
    use HasImageAttribute;
    use HasFactory;

    const SLUG_ABOUT = 'about-us';
    const SLUG_PRIVACY  = 'privacy-policy';
    const SLUG_T_AND_C  = 'terms-and-conditions';

    protected $fillable = [
        'cardinality',
        'slug',
        'title',
        'summary',
        'content',
        'cover_photo',
        'thumb_photo',
        'system_defined',
        'show_in_menu',
        'show_in_footer',
        'is_published',
        'category_id',
    ];

    protected $table    = 'cms_pages';

    protected $casts    = [
        'system_defined' => 'boolean',
        'show_in_menu'   => 'boolean',
        'show_in_footer' => 'boolean',
        'is_published'   => 'boolean',
    ];

    public static function recent($count = null, $paginate = null)
    {
        /**
         * @var Builder
         */
        $query = static::where(['is_published' => true])->latest('published_at');
        if (is_int($paginate)) {
            return $query->paginate($paginate);
        }

        return is_null($count) ? $query->get() : $query->take($count)->get();
    }

    /**
     * @param null $count
     * @param null $paginate
     * @return mixed
     */
    public static function published($count = null, $paginate = null)
    {
        /**
         * @var Builder
         */
        $query = static::where('is_published', true)->orderBy('cardinality', 'desc');
        if (is_int($paginate)) {
            return $query->paginate($paginate);
        }

        return is_null($count) ? $query->get() : $query->take($count)->get();
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function imageDir($attribute): string
    {
        return 'public' . DS . 'posts' . DS . ($attribute == 'cover_photo' ? 'cover' : 'thumb');
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function defaultImage($attribute): string
    {
        return ($attribute == 'cover_photo' ? 'page-cover.jpg' : 'page-thumb.jpg');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Update the model in the database.
     *
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        return $this->system_defined
            ? parent::update(Arr::except($attributes, ['slug']), $options)
            : parent::update($attributes, $options);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('cms.page.view', ['page' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.page.update', ['page' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getCoverUrlAttribute(): string
    {
        return asset($this->getImageUrl('cover_photo'));
    }

    /**
     * @return string
     */
    public function getThumbUrlAttribute(): string
    {
        return asset($this->getImageUrl('thumb_photo'));
    }
}
