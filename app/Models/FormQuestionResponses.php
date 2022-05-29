<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormQuestionResponses extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,string>
     */
    protected $table = 'form_question_responses';
    protected $fillable = [
        'id',
        'job_id',
        'question',
    ];
}
