<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRoleUsers extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int>
     */
    protected $table = 'user_role_users';
    protected $fillable = [
        'id',
        'user_id',
        'role_id',
    ];

    /**
     * Relationship: User - UserRoleUsers : 1 - n
     *
     */
    public function users()
    {
        return $this->hasOne(Users::class, 'user_id', 'id');
    }

    /**
     * Relationship: UserRoles - UserRoleUsers : 1 - n
     *
     */
    public function userRoles()
    {
        return $this->hasOne(UserRoles::class, 'id', 'role_id');
    }
}
