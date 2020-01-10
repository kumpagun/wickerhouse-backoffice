<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class HomeworkAnswer extends Eloquent  
{
  protected $collection = 'homework_answers';
  protected $fillable = [
    'homework_id',
    'user_id',
    'status',
    'training_id',
    'answer_text',
    'answer_file',
    'result',
    'description',
    'inspector',
    'homework_data',
    'course_id'
  ];
}