<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\Model;
use App\Traits\Models\FindBySlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Redirect
 * @property mixed destination
 * @property string $slug
 * @package App\Models\CMS
 */
class Redirect extends Model
{
    use FindBySlug;
    use HasFactory;

    protected $table = 'cms_redirects';

    protected $fillable = [
        'slug',
        'destination',
    ];

    /**
     * @return string
     */
    public function getEditUrlAttribute(): string
    {
        return route('cms.admin.redirect.update', ['redirect' => $this->slug]);
    }
}
