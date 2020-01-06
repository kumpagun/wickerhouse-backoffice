<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Company extends Eloquent  
{
  protected $collection = 'companys';
  protected $fillable = ['status','title'];
  
}