<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Models\CMS\Media;
use Spatie\MediaLibrary\HasMedia as BaseInterface;

interface HasMedia extends BaseInterface
{
    public function getMediaDeleteUrl(Media $media): string;

}
