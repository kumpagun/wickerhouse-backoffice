<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use Excel;
// Models
use App\Models\Training;
use App\Models\Review;
use App\Models\Review_group;
use App\Models\Review_choice;
use App\Models\Review_answer;

class ReviewTrainingController extends Controller
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
    $datas = Training::where('status',1)->get();
    $withData = [
      'datas' => $datas
    ];
    return view('report.review.review_training_index',$withData);
  }

  public function review_create(Request $request, $training_id) {
    $training_id = new ObjectId($training_id);
    $platform = $request->input('platform');
    
    $training = Training::find($training_id);
    $course_id = $training->course_id;
    $course_id = new ObjectId($course_id);

    $query = Review_answer::query()->where('training_id',$training_id);
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
    $query = Review_answer::where('training_id',$training_id);
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
      return Excel::download(new Export_Review_Training($training,$review_group,$reviews,$data_question,$data_choice,$datas_report,$count_report,$data_total), Carbon::now()->timestamp.'.xlsx');
    } else {
      $withData = [
        'training' => $training,
        'review_group' => $review_group,
        'reviews' => $reviews,
        'data_question' => $data_question,
        'data_choice' => $data_choice,
        'datas_report' => $datas_report,
        'count_report' => $count_report,
        'data_total' => $data_total
      ];
  
      return view('report.review.review_training_create',$withData);
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

    return view('report.review.review_training_create_answer_text',$withData);
  }
}