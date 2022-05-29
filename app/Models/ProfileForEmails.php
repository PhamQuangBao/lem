<?php

namespace App\Models;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileForEmails extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $table = 'profile_for_emails';
    protected $fillable = [
        'id',
        'profile_id',
        'email_id',
        'label',
        'form_name',
        'form_email',
        'time_sent',
        'subject',
        'number_attachment',
        'attachment_key'
    ];
}
