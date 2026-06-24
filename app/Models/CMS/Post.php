<?php

declare(strict_types=1);

namespace App\Models\CMS;

use App\Interfaces\HasImageAttributes;
use App\Models\Model;
use App\Traits\Models\FindBySlug;
use App\Traits\Models\HasImageAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static withTrashed()
 * @method static create(array $array_except)
 * @property mixed edit_url
 * @property mixed is_published
 * @property string $slug
 * Class Post
 * @package App\Models\CMS
 */
class Post extends Model implements HasImageAttributes
{
    use FindBySlug;
    use SoftDeletes;
    use HasImageAttribute;
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'summary',
        'content',
        'cover_photo',
        'thumb_photo',
        'published_at',
        'is_published',
        'category_id',
    ];

    protected $casts    = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'deleted_at'   =>  'datetime'
    ];

    protected $table    = 'cms_posts';

    /**
     * @param int|null $count
     * @param int|null $paginate
     *
     * @return mixed
     */
    public static function recent($count = null, $paginate = null)
    {
        /**
         * @var Builder
         */
        $query = static::where('is_published', true)->latest('published_at');
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
        return ($attribute == 'cover_photo' ? 'post-cover.jpg' : 'post-thumb.jpg');
    }

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
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('cms.post.view', ['post' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.post.update', ['post' => $this->slug]);
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
