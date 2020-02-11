<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Review_choice extends Eloquent  
{
  protected $collection = 'review_choices';
  protected $fillable = [
    'title',
    'choices', // array
    'status',
  ];
}