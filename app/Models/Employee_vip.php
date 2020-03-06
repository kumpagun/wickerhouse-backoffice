<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Employee_vip extends Eloquent  
{
  protected $collection = 'employee_vips';
}