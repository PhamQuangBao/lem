<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(Users::class)->using(UserRoleUsers::class);;
    }

}
