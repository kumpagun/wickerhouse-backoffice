<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Quiz_group extends Eloquent  
{
  protected $collection = 'episode_quiz_groups';
  protected $fillable = [
    'course_id',
    'episode_id',
    'require_all_correct',
    'status'
  ];
}