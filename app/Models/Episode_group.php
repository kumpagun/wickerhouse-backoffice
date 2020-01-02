<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Episode_group extends Eloquent  
{
  protected $collection = 'episode_groups';
  protected $fillable = [
    'course_id', // ObjectId
    'title',
    'position',
    'status'
  ];
}