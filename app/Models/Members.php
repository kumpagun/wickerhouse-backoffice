<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Member extends Eloquent  
{
  protected $collection = 'members';
  protected $fillable = ['status','fullname','firstname','lastname','email','employee_id','workplace','position','company','department'];
}