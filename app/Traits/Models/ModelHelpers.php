<?php
declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class ModelHelpers
 *
 * @package App\Models\Traits
 */
trait ModelHelpers
{
    /**
     * @param null $class
     *
     * @return mixed
     */
    public static function morphKey($class = null)
    {
        $morph_map = array_flip(Relation::morphMap());

        return $morph_map[$class ?: static::class];
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public static function morphClass($key)
    {
        $morph_map = Relation::morphMap();

        return $morph_map[$key];
    }

    /**
     * @param $column
     * @param $value
     *
     * @return mixed
     */
    public static function findByColumn($column, $value)
    {
        return is_array($value) ? self::whereIn($column, $value) : self::where($column, $value);
    }

    /**
     * @return array
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}
