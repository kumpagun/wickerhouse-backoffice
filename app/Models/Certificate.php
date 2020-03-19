<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Certificate extends Eloquent  
{
  protected $collection = 'certificates';
  protected $fillable = [
    'title',
    'certificate_image',
    'font_position',
    'font_size',
    'font_color',
    'font_newline',
    'course_position',
    'course_size',
    'course_color',
    'status'
  ];
  
}