<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use ActivityLogClass;
use FuncClass;
use File;
use Image;
use URL;
// Model
use App\Models\Course;
use App\Models\Training;
use App\Models\Review;
use App\Models\Review_group;
use App\Models\Review_choice;


class ReviewController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_review_group($course_id)
  {
    $data = Review_group::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    return $data;
  }
  public function review_index($review_group_id){
    $review_group = Review_group::where('_id',new ObjectId($review_group_id))->where('status',1)->first();
    $review = Review::where('review_group_id',new ObjectId($review_group_id))->where('status',1)->get();
    $choices = Review_choice::where('status',1)->get();
    $withData = [
      'review_group_id' => $review_group_id,
      'review_group' => $review_group,
      'review' => $review,
      'choices' => $choices
    ];
    return view('review.review_index',$withData);
  }
  public function review_group_store(Request $request){
    $id = $request->input('review_group_id');
    $course_id = $request->input('course_id');
    $status = $request->input('status');
    $title = $request->input('title');
    $rules = [
      'title' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'review')->withInput();
    }
    if(!empty($id)) {
      $course = Review_group::find($id);
    } else {
      $position = Review_group::where('course_id',new ObjectId($course_id))->where('status',1)->count();
      $course = new Review_group();
      $course->position = $position;
    }
    $course->course_id = new ObjectId($course_id);
    $course->title = $title;
    $course->status = intval($status);
    $course->save();

    ActivityLogClass::log('แก้ไข review', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->route('review_index', ['id' => $course_id, '#review']);
  }
  public function review_group_delete($review_group_id){
    $review_group = Review_group::find($review_group_id);
    $review_group->status = 0;
    $review_group->save();
    ActivityLogClass::log('ลบ review group', new ObjectId(Auth::user()->_id), $review_group->getTable(), $review_group->getAttributes(),Auth::user()->username);
    return redirect()->route('review_index', ['id' => $review_group->course_id, '#review']);
  }
  
  public function review_answer_index($course_id,$type=''){
    $course = Course::find($course_id);
    $query = Review_group::where('course_id',new ObjectId($course_id))->where('status',1);
    if(!empty($type) && $type=='answer') {
      $query->whereNotNull('answer');
    } else if(!empty($type) && $type=='no_answer') {
      $query->whereNull('answer');
    }
    $datas = $query->paginate(20);
    $withData = [
      'type' => $type,
      'course' => $course,
      'datas' => $datas
    ];
    return view('review.review_answer_index',$withData);
  }

  public function review_answer_store(Request $request){
    $review_answer_id = $request->input('review_answer_id');
    $answer = $request->input('answer');

    $rules = [
      'answer' => 'required'
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'question')->withInput();
    }

    $question = Review_group::find($review_answer_id);
    $question->answer = $answer;
    $question->answer_at = new UTCDateTime(Carbon::now()->timestamp * 1000);
    $question->answer_by = new ObjectId(Auth::user()->_id);
    $question->save();

    ActivityLogClass::log('ตอบคำถาม question', new ObjectId(Auth::user()->_id), $question->getTable(), $question->getAttributes(),Auth::user()->username);
    return redirect()->back()->with('status',200);
  }
  public function review_store(Request $request){
    $review_group_id = $request->input('review_group_id');
    $title = $request->input('title');
    $type = $request->input('type');
    $choice_id = $request->input('choice_id'); 
    $questions = $request->input('questions');
    $require = $request->input('require');
    $status = $request->input('status');
    $arr_question = [];
    $count = 0;
    if(!empty($questions)) {
      foreach($questions as $row) {
        if(!empty($row) && $row['questions']!='') {
          $result = [
            'key' => $count,
            'title' => $row['questions']
          ];
          array_push($arr_question,$result);
          $count++;
        }
      }
    }
    if(!empty($require)) {
      $require = true;
    } else {
      $require = false;
    }
    if(!empty($id)) {
      $course = Review::find($id);
    } else {
      $course = new Review();
    }
    $course->review_group_id = new ObjectId($review_group_id);
    $course->title = $title;
    $course->type = $type;
    $course->choice_id = new ObjectId($choice_id);
    $course->questions = $arr_question;
    $course->require = $require; // boolean
    $course->status = intval($status);
    $course->save();

    ActivityLogClass::log('เพิ่ม review_choice', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->back()->with('status',200);
  }
  public function review_choice_store(Request $request){
    $title = $request->input('title');
    $choices = $request->input('choices');
    $arr_choice = [];
    $count = 0;
    if(!empty($choices)) {
      foreach($choices as $row) {
        if(!empty($row) && $row['choices']!='') {
          $result = [
            'key' => $count,
            'title' => $row['choices']
          ];
          array_push($arr_choice,$result);
          $count++;
        }
      }
    }
    if(!empty($id)) {
      $course = Review_choice::find($id);
    } else {
      $course = new Review_choice();
    }
    $course->title = $title;
    $course->choices = $arr_choice;
    $course->status = intval(1);
    $course->save();

    ActivityLogClass::log('เพิ่ม review_choice', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->back()->with('status',200);
  }
}
