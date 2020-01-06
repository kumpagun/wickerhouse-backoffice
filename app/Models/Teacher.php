<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Teacher extends Eloquent  
{
  protected $collection = 'teachers';
  protected $fillable = [
    'name',
    'subtitle',
    'slug',
    'label',
    'profile_image',
    'description',
    'history',
    'status',
    'test_status',
  ];
}