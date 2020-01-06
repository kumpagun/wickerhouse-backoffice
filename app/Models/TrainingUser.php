<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TrainingUser extends Eloquent  
{
  protected $collection = 'training_users';
}