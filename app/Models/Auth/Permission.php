<?php
declare(strict_types=1);

namespace App\Models\Auth;

use App\Models\Model;
use App\Traits\Models\FindByName;
use App\Traits\Models\ModelHelpers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Permission
 * @package App\Models\Auth
 */
class Permission extends Model
{
    use FindByName;
    use ModelHelpers;

    const ROOT = '*';

    protected $table = 'auth_permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'auth_permission_user');
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'auth_permission');
    }
}
