<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\Models\ModelHelpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;

/**
 * Class Model
 *
 * @property int id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static create(array $array_except)
 * @method \Illuminate\Database\Eloquent\Builder where(...$args)
 * @method \Illuminate\Database\Eloquent\Builder whereIn(string $string, array $ids)
 * @method \Illuminate\Database\Eloquent\Builder orderByDesc(string $column)
 */
abstract class Model extends EloquentModel
{
    use ModelHelpers;

    protected static $handlers = [
        'creating' => [],
        'created'  => [],
        'deleting' => [],
        'deleted'  => [],
        'updating' => [],
        'updated'  => [],
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    function __construct(array $attributes = [])
    {
        //Boot traits
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (Str::endsWith($method, 'TraitBoot')) {
                $this->$method();
            }
        }

        parent::__construct($attributes);
    }

    protected static function boot()
    {
        static::creating(function ($object) {
            return self::executeHandlers($object, self::$handlers['creating'], true);
        });

        static::created(function ($object) {
            self::executeHandlers($object, self::$handlers['created']);
        });

        static::deleting(function ($object) {
            return self::executeHandlers($object, self::$handlers['deleting'], true);
        });

        static::deleted(function ($object) {
            self::executeHandlers($object, self::$handlers['deleted']);
        });

        static::updating(function ($object) {
            return self::executeHandlers($object, self::$handlers['updating'], true);
        });

        static::updated(function ($object) {
            self::executeHandlers($object, self::$handlers['updated']);
        });

        parent::boot();
    }

    /**
     * @param $object
     * @param $handlers
     * @param bool $returns
     * @return bool
     * @throws Exception
     */
    private static function executeHandlers($object, $handlers, $returns = false)
    {
        $return = true;
        foreach ($handlers as $key => $handler) {
            $result = $handler($object);
            if (!isset($result) && $returns) {
                throw new Exception("Handler $key returned null, true or false expected.");
            } else {
                $return &= $result;
            }
        }

        return $return;
    }

    /**
     * @param $event
     * @param $closure
     * @param null $name
     */
    protected static function addModelHandler($event, $closure, $name = null)
    {
        if ($name) {
            self::$handlers[$event][$name] = $closure;
        } else {
            self::$handlers[$event][] = $closure;
        }
    }

    /**
     * @param $event
     * @param $name
     */
    protected static function removeModelHandler($event, $name)
    {
        unset(self::$handlers[$event][$name]);
    }

    public function cleanUp()
    {
        if (method_exists($this, 'deletePhoto')) {
            //Unlink photo
            $this->deletePhoto();

            //Unlink thumbnail
            $this->deleteThumbnail();
        }
    }
}
