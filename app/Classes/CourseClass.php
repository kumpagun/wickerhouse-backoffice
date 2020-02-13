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
use App\Models\Examination_group;
use App\Models\Examination;
use App\Models\Episode;
use App\Models\Training;
use App\Models\Homework;
use App\Models\HomeworkAnswer;
use App\Models\Question;
use App\Models\Review_choice;

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
  public function get_exam_total($id) {
    // $exam_group = Examination_group::find($id);
    $data = Examination::where('exam_group_id',new ObjectId($id))->where('status',1)->count();
    
    return $data;
  }
  public function get_homework_answer_total($training_id){
    $training = Training::find($training_id);
    $homework = Homework::where('course_id',new ObjectId($training->course_id))->where('status',1)->first();
    $count = 0;
    if(!empty($homework)) {
      $data = HomeworkAnswer::where('training_id', new ObjectId($training_id))
      ->where('homework_id',new ObjectId($homework->_id))
      ->where('status',1)
      ->select('user_id')
      ->groupBy('user_id')
      ->get(); 

      foreach($data as $row) {
        $count++;
      }
    }
    
    return $count;
  }
  public function get_question_total($course_id) {
    $homework = Question::where('course_id',new ObjectId($course_id))->where('status',1)->count();
    return $homework;
  }
  public function get_question_answer_total($course_id) {
    $homework = Question::where('course_id',new ObjectId($course_id))->whereNotNull('answer')->where('status',1)->count();
    return $homework;
  }
  public function get_have_homework($course_id) {
    $data = Course::find($course_id);
    return $data->have_homework;
  }

  public function get_review_choice($review_choice_id) {
    $datas = Review_choice::find($review_choice_id);
    return $datas;
  }
  public function count_ep_by_course ($course_id = '')
  {   
    $data = 0;
    if ($course_id) {
      $data = Episode::where('status',1)->where('course_id',$course_id)->count();
    } 
    return $data;
  }

  public function get_course_passing_score($course_id) {
    $query = Course::where('_id',$course_id)->first();
    $data = 0;
    if(!empty($query->posttest_passing_point)) {
      $data = $query->posttest_passing_point;
    }
    return $data;
  }
}
