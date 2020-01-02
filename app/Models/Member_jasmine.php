<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Member_jasmine extends Eloquent  
{
  protected $collection = 'member_jasmines';
  protected $fillable = [
    'employee_id',
    'tinitial' ,
    'firstname' ,
    'lastname' ,
    'emptype' ,
    'workplace' ,
    'email' ,
    'sex' ,
    'title',
    'division' ,
    'section' ,
    'department',
    'company' ,
    'staff_grade' ,
    'job_family' ,
  ];
  protected $date = ['joined_date','birthdate'];
  
}