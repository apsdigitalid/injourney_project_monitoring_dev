<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RoleUser
 *
 * @property int $user_id
 * @property int $role_id
 * @property-read mixed $icon
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 * @mixin \Eloquent
 */
class RoleUser extends BaseModel
{
    protected $table = 'role_user';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_id');
    }

    public $timestamps = false;
}
