<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Product extends Eloquent  
{
  protected $collection = 'products';
  protected $fillable = [
    
  ];
  protected $date = ['published_at'];
}