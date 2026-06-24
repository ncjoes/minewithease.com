<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Interfaces\HasImageAttributes;
use App\Models\Model;
use App\Traits\Models\FindBySlug;
use App\Traits\Models\HasImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @property string slug
 * @property string $type
 * @package App\Models\CMS
 */
class Category extends Model implements HasImageAttributes
{
    use FindBySlug;
    use SoftDeletes;
    use HasImageAttribute;
    use HasFactory;

    const C_ABOUT    = 'about-us';
    const C_SERVICES = 'our-services';

    const TYPE_POST = 'post';
    const TYPE_PAGE = 'page';
    const TYPE_FAQ  = 'faq';

    protected $table = 'cms_categories';

    protected $fillable = [
        'type',
        'slug',
        'title',
        'description',
        'cardinality',
        'system_defined',
        'show_in_menu',
        'show_in_footer',
        'use_index',
    ];
    protected $casts    = [
        'system_defined' => 'boolean',
        'show_in_menu'   => 'boolean',
        'show_in_footer' => 'boolean',
    ];

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
        return ($attribute == 'cover_photo' ? 'category-cover.jpg' : 'category-thumb.jpg');
    }

    /**
     * @return HasMany
     */
    public function publishedPages(): HasMany
    {
        return $this->hasMany(Page::class)
                    ->where('is_published', true)
                    ->orderByDesc('cardinality');
    }

    /**
     * @return HasMany
     */
    public function menuPages(): HasMany
    {
        return $this->pages()->where('show_in_menu', true);
    }

    /**
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    /**
     * @return HasMany
     */
    public function footerPages(): HasMany
    {
        return $this->pages()->where('show_in_footer', true);
    }

    /**
     * @return HasMany
     */
    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('is_published', true)->orderByDesc('published_at');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('cms.post.index', ['category' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.category.update', ['category' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getTypeStrAttribute(): string
    {
        return $this->type;
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
