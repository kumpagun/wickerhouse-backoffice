<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MyTraining extends Eloquent  
{
  protected $collection = 'my_trainings';
}