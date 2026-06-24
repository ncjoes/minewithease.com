<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\Model;
use App\Traits\Models\FindByStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property mixed status
 */
class Testimony extends Model
{
    use FindByStatus;
    use HasFactory;

    const S_PENDING   = 'd';
    const S_PUBLISHED = 'p';

    protected $table    = 'cms_testimonies';
    protected $fillable = [
        'name',
        'statement',
        'status',
    ];

    public static function statuses()
    {
        return [
            self::S_PENDING   => 'Pending',
            self::S_PUBLISHED => 'Published',
        ];
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }
}