<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Giftcode_group extends Eloquent  
{
  protected $collection = 'giftcode_groups';
  protected $fillable = [
    'training_id',
    'course_id',
    'total',
    'published_at',
    'expired_at',
    'status'
  ];
}