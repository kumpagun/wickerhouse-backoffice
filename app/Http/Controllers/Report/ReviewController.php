<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
// Models
use App\Models\Training;
use App\Models\Review;
use App\Models\Review_group;
use App\Models\Review_choice;
use App\Models\Review_answer;

class ReviewController extends Controller
{
  public function __construct()
  {

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
    $datas = Training::where('status',1)->get();
    $withData = [
      'datas' => $datas
    ];
    return view('report.review.review_index',$withData);
  }

  public function review_create($training_id) {
    $training_id = new ObjectId($training_id);
    
    $training = Training::find($training_id);
    $course_id = $training->course_id;
    $course_id = new ObjectId($course_id);

    $query = Review_answer::query()->where('training_id',$training_id);
    $query->where('course_id', $course_id);
    $query->where('status', 1);
    $query->orderBy('created_at','asc');
    $datas = $query->get();

    $review_choice = Review::where('status',1)->get();
    $data_choice = [];
    foreach($review_choice as $data) {
      if($data->type=='choice') {
        $data_choice[$data->_id] = $this->get_choice($data->choice_id);
      }
    }

    $datas_report = [];
    $count_report = [];
    foreach($datas as $data) {
      $review_id = (string)$data->review_id;
      if($data->type=='choice') {
        foreach($data->review_choice_answer as $choice) {
          if(is_int($choice)) {
            if(empty($datas_report[$review_id]['choice'][$data_choice[$review_id][$choice]])) {
              $datas_report[$review_id]['choice'][$data_choice[$review_id][$choice]] = 1;
            } else {
              $datas_report[$review_id]['choice'][$data_choice[$review_id][$choice]]++;
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
   
    $review_group = Review_group::where('status',1)->get();
    $reviews = Review::where('status',1)->get();
    
    $withData = [
      'training' => $training,
      'review_group' => $review_group,
      'reviews' => $reviews,
      'data_choice' => $data_choice,
      'datas_report' => $datas_report,
      'count_report' => $count_report
    ];

    return view('report.review.review_create',$withData);
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
}