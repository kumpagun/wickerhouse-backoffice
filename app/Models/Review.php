<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Review extends Eloquent  
{
  protected $collection = 'reviews';
  protected $fillable = [
    'review_group_id',
    'title',
    'type',
    'choice_id',
    'questions',
    'require',
    'status'
  ];
}