<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Reviews_user extends Eloquent  
{
  protected $collection = 'reviews_users';
}