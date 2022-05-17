<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatuses extends Model
{
    use HasFactory;
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
     * Relationship: Statuses- Job : 1 - n
     *
     */
    public function Jobs() {
        return $this->hasMany(Jobs::class, 'id', 'job_status_id');
    }
}
