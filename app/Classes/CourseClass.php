<?php

namespace App\Classes;
use Request;
use MongoDB\BSON\ObjectId as ObjectId;
use App\User;
use Maklad\Permission\Models\Role;

use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Category;

class CourseClass
{   
  public function get_name_course($course_id = ''){
    $name = '-';
    $data = Course::find($course_id);
    if ($data) {
      $name = $data->title;
    }
    return $name;
  }
  public function get_name_category($category_id = ''){
    $name = '-';
    $data = Category::find($category_id);
    if ($data) {
      $name = $data->title;
    }
    return $name;
  }
}
