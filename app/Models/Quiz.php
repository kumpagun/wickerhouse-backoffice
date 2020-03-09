<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Quiz extends Eloquent  
{
  protected $collection = 'episode_quizs';
  protected $fillable = [
    'quiz_group_id',
    'course_id',
    'episode_id',
    'question',
    'choice',
    'answer_value',
    'answer_key',
    'status'
  ];
}