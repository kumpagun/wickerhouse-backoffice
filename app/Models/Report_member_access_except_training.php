<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Report_member_access_except_training extends Eloquent  
{
  protected $collection = 'report_member_access_except_trainings';
  protected $fillable = [
    'course_id',
    'employee_id',
    'tinitial',
    'firstname',
    'lastname',
    'workplace',
    'title',
    'company',
    'division',
    'section',
    'department',
    'branch',
    'region',
    'staff_grade',
    'job_family',
    'play_course',
    'play_course_end',
    'play_course_end_all_ep',
    'pretest',
    'posttest',
    'created_day',
    'created_month',
    'created_year',
    'status'
  ];
}