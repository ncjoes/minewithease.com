<?php
declare(strict_types=1);

namespace App\Interfaces;

interface HasImageAttributes
{
    static function imageDir($attribute);

    static function defaultImage($attribute);
}
