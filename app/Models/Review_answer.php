<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Review_answer extends Eloquent  
{
  protected $collection = 'reviews_answers';
}