<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Report_member_access extends Eloquent  
{
  protected $collection = 'report_member_accesses';
  protected $fillable = [
    'course_id',
    'training_id',
    'employee_id',
    'tinitial',
    'tf_name',
    'tl_name',
    'workplace',
    'title_name',
    'division_name',
    'section_name',
    'dept_name',
    'staff_grade',
    'job_family',
    'play_course',
    'play_course_end',
    'pretest',
    'posttest',
    'status'
  ];
}