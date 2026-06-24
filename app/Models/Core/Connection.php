<?php

namespace App\Models\Core;

use App\Models\Model;
use App\Traits\Models\FindByUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Connection extends Model
{
    /** @use HasFactory<\Database\Factories\ConnectionFactory> */
    use HasFactory;
    use FindByUUID;

    protected $table = "core_connections";

    protected $fillable = [
        'uuid',
        'type',
        'data'
    ];

    /**
     * @return string
     */
    public static function generateUUID(): string
    {
        return self::makeUUID(4, 8);
    }

    protected $casts = [
        'data'          => 'array',
        'deleted_at'    => 'timestamp',
    ];


}
