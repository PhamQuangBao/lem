<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileStatuses extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'id',
        'profile_status_group_id',
        'name',
    ];

    /**
     * Relationship: Statuses- Cv : 1 - n
     *
     */
    public function Profile() {
        return $this->hasMany(Profile::class, 'id', 'profile_status_id');
    }
}
