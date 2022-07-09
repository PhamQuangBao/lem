<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviews extends Model
{
    use HasFactory;
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,string,int,int,int,int,int,text,string,string,string,double,double,date>
     */
    protected $fillable = [
        'id',
        'profile_id',
        'interviewer_id',
        'overall_band_id',
        'primary_skill_id',
        'prim_level_id',
        'secondary_skill_id',
        'second_level_id',
        'technical_skills_note',
        'english_level',
        'soft_skills',
        'overall_assessment',
        'expected_salary',
        'current_salary',
        'onboard_date',
    ];

    /**
     * Relationship: Profile - Interviews : 1 - 1
     *
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }
}
