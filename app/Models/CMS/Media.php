<?php
declare(strict_types=1);

namespace App\Models\CMS;

use App\Traits\Models\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use ModelHelpers;
    use HasFactory;

    protected $table = 'cms_media';

    public function getDeleteUrlAttribute()
    {
        return $this->model->getMediaDeleteUrl($this);
    }

    public function isImg()
    {
        return in_array($this->extension, ['jpg', 'png', 'jpeg', 'gif']);
    }
}