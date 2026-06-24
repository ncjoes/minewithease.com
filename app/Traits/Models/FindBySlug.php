<?php
declare(strict_types=1);

namespace App\Traits\Models;

use App\Models\Model;
use Illuminate\Support\Str;

trait FindBySlug
{
    public static function findBySlug($slug)
    {
        return static::findByColumn('slug', $slug)->first();
    }

    public static function makeUniqueSlug($title, Model $currentObject = null): string
    {
        $slug     = Str::slug($title);
        $existing = (
        is_object($currentObject) ? self::where('id', '<>', $currentObject->id)->where('slug', 'LIKE', $slug . '_%') : self::where('slug', 'LIKE', $slug . '_%')
        )->count();
        $next     = $slug . '_' . $existing;

        return ($existing == 0 ? $slug : self::makeUniqueSlug($next, $currentObject));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
