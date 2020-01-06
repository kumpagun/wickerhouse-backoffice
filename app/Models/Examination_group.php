<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Examination_group extends Eloquent  
{
  protected $collection = 'examination_groups';
  protected $fillable = [
    'type',
    'duration_limit',
    'duration_sec',
    'status'
  ];
}