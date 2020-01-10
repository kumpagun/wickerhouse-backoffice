<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Question extends Eloquent  
{
  protected $collection = 'questions';
  protected $fillable = [
    'question',
    'course_id',
    'training_id',
    'user_id',
    'created_at',
    'status',
  ];
}