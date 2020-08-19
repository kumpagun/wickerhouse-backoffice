<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use Excel;
// Models
use App\Models\Course;
use App\Models\Training;
use App\Models\Review;
use App\Models\Member;
use App\Models\Review_group;
use App\Models\Review_choice;
use App\Models\Review_answer;

class ReviewController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function get_choice($id) {
    $datas = Review_choice::find($id);
    $data_back = [];
    foreach($datas->choices as $data) {
      array_push($data_back, $data['title']);
    }
    return $data_back;
  }

  public function review_index() {
    $datas = Course::where('status',1)->get();
    $withData = [
      'datas' => $datas
    ];
    return view('report.review.review_index',$withData);
  }

  public function review_create(Request $request, $course_id) {
    $course_id = new ObjectId($course_id);
    $platform = $request->input('platform');

    $course = Course::find($course_id);

    $query = Review_answer::whereNull('training_id');
    $query->where('course_id', $course_id);
    $query->where('status', 1);
    $query->select('user_id');
    $query->groupBy('user_id');
    $data_total = $query->count();

    $review_choice = Review::where('status',1)->get();
    $data_choice = [];
    $data_question = [];
    foreach($review_choice as $data) {
      if($data->type=='choice') {
        $data_choice[$data->_id] = $this->get_choice($data->choice_id);
        $data_question[$data->_id] = $data->questions;
      }
    }

    $datas_report = [];
    $count_report = [];
    $query = Review_answer::whereNull('training_id');
    $query->where('course_id', $course_id);
    $query->where('status', 1);
    $datas = $query->chunk(1000, function($rows) use ($data_choice, &$datas_report, &$count_report) {
      foreach($rows as $data) {
        $review_id = (string)$data->review_id;
        if($data->type=='choice') {
          foreach($data->review_choice_answer as $index => $choice) {
            if(is_int($choice)) {
              if(empty($datas_report[$review_id]['choice'][$index][$data_choice[$review_id][$choice]])) {
                $datas_report[$review_id]['choice'][$index][$data_choice[$review_id][$choice]] = 1;
              } else {
                $datas_report[$review_id]['choice'][$index][$data_choice[$review_id][$choice]]++;
              }
              if(empty($datas_report[$review_id]['choice_total'][$index])) {
                $datas_report[$review_id]['choice_total'][$index] = 1;
              } else {
                $datas_report[$review_id]['choice_total'][$index]++;
              }
            }
          }
        } else {
          if(empty($datas_report[$review_id]['text'])) {
            $datas_report[$review_id]['text'] = [];
            $count_report[$review_id] = 0;
          }
          if(!empty($data->review_text_answer) && $count_report[$review_id] < 2) {
            array_push($datas_report[$review_id]['text'], $data->review_text_answer);
            $count_report[$review_id]++;
          }
        }
      }
    });
   
    $review_group = Review_group::where('status',1)->where('course_id', $course_id)->get();
    $reviews = Review::where('status',1)->where('course_id', $course_id)->get();
    
    if($platform=='excel') {
      return Excel::download(new Export_Review_Course($course,$review_group,$reviews,$data_question,$data_choice,$datas_report,$count_report,$data_total), Carbon::now()->timestamp.'.xlsx');
    } else {
      $withData = [
        'course' => $course,
        'review_group' => $review_group,
        'reviews' => $reviews,
        'data_question' => $data_question,
        'data_choice' => $data_choice,
        'datas_report' => $datas_report,
        'count_report' => $count_report,
        'data_total' => $data_total
      ];
  
      return view('report.review.review_create',$withData);
    }
  }

  public function review_create_answer_text($review_id) {
    $review = Review::find($review_id);

    $query = Review_answer::query()->where('review_id',new ObjectId($review_id));
    $query->where('type', 'text');
    $query->whereNotNull('review_text_answer');
    $query->where('review_text_answer','<>','');
    $query->where('status', 1);
    $query->orderBy('created_at','asc');
    $datas = $query->paginate(50);

    $training = Training::find($datas[0]->training_id);
    
    $withData = [
      'training' => $training,
      'review' => $review,
      'datas' => $datas
    ];

    return view('report.review.review_create_answer_text',$withData);
  }

  public function review_by_user($course_id) {
    $course_id = new ObjectId($course_id);
    
    $course = Course::find($course_id);

    // Get member in Review_answer
    $query_review_ans = Review_answer::select('user_id')->whereNull('training_id')->where('course_id', $course_id)->where('status', 1)->groupBy('user_id')->get();
    $arr_emp = [];
    foreach($query_review_ans as $row) {
      array_push($arr_emp, new ObjectId($row->user_id));
    }

    //Users
    $query_employee = Member::whereIn('_id',$arr_emp)->get(); 
    $employees = [];
    foreach($query_employee as $row) {
      $employees[$row->_id]['company'] = !empty($row->company) ? $row->company : '';
      $employees[$row->_id]['name'] = !empty($row->fullname) ? $row->fullname : '';
      $employees[$row->_id]['employee_id'] = !empty($row->employee_id) ? $row->employee_id : '';
      $employees[$row->_id]['position'] = !empty($row->position) ? $row->position : '';
    }

    // Question and Choice
    $review_choice = Review::where('status',1)->get();
    $data_choice = [];
    $data_question = [];
    foreach($review_choice as $data) {
      if($data->type=='choice') {
        $data_choice[$data->_id] = $this->get_choice($data->choice_id);
        $data_question[$data->_id] = $data->questions;
      }
    }

    // หัวข้อ review ทั้งหมด
    $review_group = Review_group::where('status',1)->where('course_id', $course_id)->get();
    $reviews = Review::where('status',1)->where('course_id', $course_id)->get();

    $review_group_arr = [];
    foreach($review_group as $row) {
      $review_group_arr[$row->_id] = $row->title;
    }
    $reviews_arr = [];
    $total_reviews_arr = [];
    foreach($reviews as $row) {
      $review_group = $review_group_arr[(string)$row->review_group_id];
      $review_title = strip_tags($row->title);
      if($row->type=='choice') {
        foreach($row->questions as $questions) {
          if(empty($reviews_arr[$review_group][$review_title])) $reviews_arr[$review_group][$review_title] = [];
          array_push($reviews_arr[$review_group][$review_title], $questions);

          if(empty($total_reviews_arr[$review_group])) $total_reviews_arr[$review_group] = 0;
          $total_reviews_arr[$review_group]++;
        }
      } else {
        if(empty($reviews_arr[$review_group][$review_title])) $reviews_arr[$review_group][$review_title] = [];
        array_push($reviews_arr[$review_group][$review_title], 'rowspan');

        if(empty($total_reviews_arr[$review_group])) $total_reviews_arr[$review_group] = 0;
        $total_reviews_arr[$review_group]++;
      }
    }

    // Review Answer
    $datas_report = [];
    $datas_report_createdAt = [];
    $query = Review_answer::whereNull('training_id')->where('course_id', $course_id)->where('status', 1);
    $datas = $query->chunk(1000, function($rows) use ($data_choice, &$datas_report, &$datas_report_createdAt) {
      foreach($rows as $data) {
        $review_id = (string)$data->review_id;
        $user_id = (string)$data->user_id;
        $datas_report_createdAt[$user_id] = $data->created_at;
        if($data->type=='choice') {
          foreach($data->review_choice_answer as $index => $choice) {
            if(is_int($choice)) {
              $datas_report[$user_id][$review_id]['choice'][$index] = $data_choice[$review_id][$choice];
            }
          }
        } else {
          if(empty($datas_report[$user_id][$review_id]['text'])) {
            $datas_report[$user_id][$review_id]['text'] = [];
          }
          array_push($datas_report[$user_id][$review_id]['text'], $data->review_text_answer);
        }
      }
    });

    $withData = [
      'course' => $course,
      'review_group' => $review_group,
      'review_group_arr' => $review_group_arr,
      'total_reviews_arr' => $total_reviews_arr,
      'reviews' => $reviews,
      'reviews_arr' => $reviews_arr,
      'employees' => $employees,
      'datas_report' => $datas_report,
      'datas_report_createdAt' => $datas_report_createdAt
    ];

    return Excel::download(new Export_Review_Byuser($course,$review_group,$review_group_arr,$total_reviews_arr,$reviews,$reviews_arr,$employees,$datas_report,$datas_report_createdAt), Carbon::now()->timestamp.'.xlsx');
  }
}