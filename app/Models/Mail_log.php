<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Mail_log extends Eloquent  
{
  protected $collection = 'mail_logs';
  protected $fillable = [
    'type',
    'from',
    'sent_to',
    'reply_to',
    'subject',
    'body',
    'callback',
    'course_id',
    'training_id',
    'question_id',
    'teacher_id',
    'user_id',
    'status'
  ];
}