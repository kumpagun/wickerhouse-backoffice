<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Report_overview extends Eloquent  
{
  protected $collection = 'report_overviews';
  protected $fillable = [
    'course_id',
    'company',
    'division',
    'section',
    'department',
    'branch',
    'region',
    'user_active',
    'user_inactive',
    'user_success',
    'status'
  ];
}