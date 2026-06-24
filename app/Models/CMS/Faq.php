<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * Class Faq
 * @property boolean $is_published
 * @package App\Models\CMS
 */
class Faq extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'category_id',
        'cardinality',
        'question',
        'answer',
        'is_published'
    ];
    protected $table    = 'cms_faqs';
    protected $casts    = [
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
        $Q = static::where('is_published', true)->orderBy('cardinality', 'desc');
        if (is_int($paginate)) {
            return $Q->paginate($paginate);
        }

        return is_null($count) ? $Q->get() : $Q->take($count)->get();
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
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.faq.update', ['faq' => $this->id]);
    }
}
