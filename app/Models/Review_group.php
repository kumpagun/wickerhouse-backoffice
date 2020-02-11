<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Review_group extends Eloquent  
{
  protected $collection = 'review_groups';
  protected $fillable = [
    'title',
    'course_id',
    'position',
    'status',
  ];
}