<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileHistorys extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,string,string>
     */
    protected $table = 'profile_historys';
    protected $fillable = [
        'id',
        'mail',
        'profile_data',
        // 'interview_data',
    ];
}
