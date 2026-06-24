<?php
declare(strict_types=1);

namespace App\Models\Auth;

use App\Models\Model;
use App\Traits\Models\FindByName;
use App\Traits\Models\ModelHelpers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 * @package App\Models\Auth
 */
class Role extends Model
{
    use FindByName;
    use ModelHelpers;

    const ROOT   = '*';
    const ADMIN  = 'a';
    const EDITOR = 'e';

    //Project specific roles
    const CLIENT = 'm';

    protected $table    = 'auth_roles';
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'auth_role_user');
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'auth_permission_role');
    }
}
