<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Giftcode_usage extends Eloquent  
{
  protected $collection = 'giftcode_usages';
  protected $fillable = [
    'giftcode_id',
    'employee_id',
    'user_id'
  ];
}