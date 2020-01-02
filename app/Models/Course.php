<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Course extends Eloquent  
{
  protected $collection = 'courses';
  protected $fillable = [
    'code',
    'slug',
    'category_id',
    'type',
    'title',
    'teacher_id',
    'description',
    'tag',
    'thumbnail',
    'coming_soon',
    'status',
    'test_status',
    'rating',
    'duration',
    'total_view',
    'total_episode',
    'have_document',
    'have_document_type',
    'have_pretest',
    'total_pretest',
    'have_posttest',
    'total_posttest',
    'posttest_passing_point',
    'posttest_limit',
    'posttest_limit_total',
    'posttest_display_answer',
    'require_course',
    'benefits',
    'appropriates'
  ];
  protected $date = ['published_at'];
}