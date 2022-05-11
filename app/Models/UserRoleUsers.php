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
        'user_id',
        'role_id',
    ];

    /**
     * Relationship: User - UserRoleUsers : 1 - n
     *
     */
    public function users()
    {
        return $this->hasMany(Users::class, 'user_id');
    }

    /**
     * Relationship: UserRoles - UserRoleUsers : 1 - n
     *
     */
    public function userRoles()
    {
        return $this->hasMany(userRoles::class, 'role_id');
    }
}
