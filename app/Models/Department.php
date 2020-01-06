<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Department extends Eloquent  
{
  protected $collection = 'departments';
  protected $fillable = ['status','title','company_id'];
  
}