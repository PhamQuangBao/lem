<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'id',
        'profile_id',
        'name',
        'file',
        'link',
    ];

    /**
     * Relationship: Profile - Files : 1 - n
     *
     */
    public function profile()
    {
        return $this->hasMany(Profile::class, 'profile_id', 'id');
    }
}