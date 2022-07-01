<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,string,string,int,string>
     */
    protected $fillable = [
        'id',
        'job_status_id',
        'key',
        'request_date',
        'close_date',
        'branch_id',
        'description',
        'calendar_key',
        'time_at',
        'time_end',
    ];

    /**
     * Relationship: Job - Job Statuses : 1 - 1
     *
     */
    public function JobStatuses()
    {
        return $this->hasOne(JobStatuses::class, 'id', 'job_status_id');
    }

    /**
     * Relationship: Job - Branches : 1 - 1
     *
     */
    public function Branches()
    {
        return $this->hasOne(Branches::class, 'id', 'branch_id');
    }

    /**
     * Relationship: Job - Profile : 1 - n
     * 
     */
    public function Profile()
    {
        return $this->hasMany(Profile::class, 'job_id', 'id');
    }
}
