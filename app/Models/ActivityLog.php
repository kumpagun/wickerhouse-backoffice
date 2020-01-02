<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ActivityLog extends Eloquent  
{
  protected $collection = 'activity_logs';
}