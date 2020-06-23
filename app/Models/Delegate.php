<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Delegate extends Eloquent  
{
  protected $collection = 'delegates';
}