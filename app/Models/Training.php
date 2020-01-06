<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Training extends Eloquent  
{
  protected $collection = 'trainings';
  protected $fillable = ['status','title','company_id','department_ids','course_id','seq','published_at','expired_at'];
  protected $date = ['published_at','expired_at'];
}