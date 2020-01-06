<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Examination extends Eloquent  
{
  protected $collection = 'examinations';
  protected $fillable = [
    'exam_group_id',
    'course_id',
    'question',
    'choice',
    'answer_value',
    'answer_key',
    'status'
  ];
}