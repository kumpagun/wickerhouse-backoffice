<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Banner extends Eloquent  
{
  protected $collection = 'banners';
  protected $fillable = [
    'image_path',
    'position',
    'status'
  ];
  
}