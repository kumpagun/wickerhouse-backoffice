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
    'teachers',
    'description',
    'have_certificate',
    'certificate_id',
    'tag',
    'thumbnail',
    'coming_soon',
    'status',
    'test_status',
    'rating',
    'duration',
    'total_view',
    'total_episode',
    'have_pretest_duration', //Boolean
    'pretest_duration_sec',
    'have_posttest_duration', //Boolean
    'posttest_duration_sec',
    'have_document', //Boolean
    'have_pretest', //Boolean
    'total_pretest',
    'have_posttest', //Boolean
    'total_posttest',
    'have_posttest_passing_point',
    'posttest_passing_point',
    'have_posttest_limit', //Boolean
    'posttest_limit_total',
    'posttest_display_answer', //Boolean
    'require_course',
    'benefits',
    'appropriates',
    'review_url'
  ];
  protected $date = ['published_at'];
}