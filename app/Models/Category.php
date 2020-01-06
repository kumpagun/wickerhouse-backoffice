<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Category extends Eloquent  
{
  protected $collection = 'categorys';
  protected $fillable = ['status','title','slug','code','description','seq'];
  
}