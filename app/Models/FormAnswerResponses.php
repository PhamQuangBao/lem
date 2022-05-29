<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAnswerResponses extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int,int,string>
     */
    protected $table = 'form_answer_responses';
    protected $fillable = [
        'id',
        'question_id',
        'answer',
    ];
}
