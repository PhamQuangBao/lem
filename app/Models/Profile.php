<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,date,date,string,string,int,int,date,int,double,date,int,string,string,int,text,string>
     */
    protected $table = 'profile';
    protected $fillable = [
        'id',
        'job_id',
        'submit_date',
        'name',
        'birthday',
        'phone_number',
        'mail',
        'address',
        'profile_status_id',
        'language_id',
        'university_id',
        'year_of_experience',
        'note',
        'calendar_key',
        'time_at',
        'time_end',
    ];

    /**
     * Relationship: Profile - Jobs : 1 - 1
     *
     */
    public function profileJobs()
    {
        return $this->hasOne(Jobs::class, 'id', 'job_id');
    }

    /**
     * Relationship: Profile - ProfileStatuses : 1 - 1
     *
     */
    public function profileStatus()
    {
        return $this->hasOne(ProfileStatuses::class, 'id', 'profile_status_id');
    }

    /**
     * The file that belong to the profile n - 1
     */
    public function files()
    {
        return $this->hasMany(Files::class, 'profile_id', 'id');
    }

    /**
     * Relationship: Profile - Universities : 1 - 1
     *
     */
    public function profileUniversities()
    {
        return $this->hasOne(Universities::class, 'id', 'university_id');
    }
}
