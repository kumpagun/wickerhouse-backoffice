<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Homework extends Eloquent  
{
  protected $collection = 'homeworks';
  protected $fillable = [
    'course_id',
    'question',
    'answer_type',
    'status',
  ];
}