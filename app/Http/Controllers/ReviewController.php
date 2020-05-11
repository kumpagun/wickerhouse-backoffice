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
  public function update_course($course_id) {
    $course = Course::find($course_id);
    $course->have_review = false;
    $course->total_review = 0;
    $course->save();
    
    $review_group = Review_group::where('course_id', new ObjectId($course_id))->where('status',1)->get(); 
    $total = 0;
    foreach($review_group as $row) {
      $total += Review::where('review_group_id', new ObjectId($row->_id))->where('status',1)->count();
    }
    if($total > 0) {
      $course->have_review = true;
      $course->total_review = $total;
    }
    $course->save();
    return true;
  }
  public function get_review_group($course_id)
  {
    $data = Review_group::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    return $data;
  }
  public function review_index($review_group_id){
    $review_group = Review_group::where('_id',new ObjectId($review_group_id))->first();
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

    $this->update_course($course_id);

    ActivityLogClass::log('แก้ไข review', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->route('review_index', ['id' => $course->_id]);
  }
  public function review_group_delete($review_group_id){
    $review_group = Review_group::find($review_group_id);
    $review_group->status = 2;
    $review_group->save();

    $this->update_course($review_group->course_id);

    ActivityLogClass::log('ลบ review group', new ObjectId(Auth::user()->_id), $review_group->getTable(), $review_group->getAttributes(),Auth::user()->username);
    return redirect()->route('course_create', ['id' => $review_group->course_id, '#review']);
  }
  public function review_create($type,$review_group_id,$id='') {
    $review_group = Review_group::where('_id',new ObjectId($review_group_id))->where('status',1)->first();
    $choices = Review_choice::where('status',1)->get();
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->title = '';
      $data->review_group_id = '';
      $data->type = '';
      $data->choice_id = '';
      $data->questions = [];
      $data->require = '';
      $data->status = 1;
    } else {
      $data = Review::find($id);
    }
    $withData = [
      'review_group_id' => $review_group_id,
      'review_group' => $review_group,
      'choices' => $choices,
      'type' => $type,
      'data' => $data,
    ]; 
    return view('review.review_detail',$withData);
  }
  public function review_store(Request $request){
    $review_group_id = $request->input('review_group_id');
    $id = $request->input('id');
    $title = $request->input('title');
    $type = $request->input('type');
    $course_id = $request->input('course_id');
    $choice_id = $request->input('choice_id'); 
    $questions = $request->input('questions');
    $require = $request->input('require');
    $status = $request->input('status');
    $arr_question = [];
    if(!empty($questions)) {
      foreach($questions as $row) {
        if(!empty($row) && $row['questions']!='') {
          $result = $row['questions'];
          array_push($arr_question,$result);
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
    $course->course_id = new ObjectId($course_id);
    $course->title = $title;
    $course->type = $type;
    if($type=='choice') {
      $course->choice_id = new ObjectId($choice_id);
      $course->questions = $arr_question;
    } 
    $course->require = $require; // boolean
    $course->status = intval($status);
    $course->save();

    $this->update_course($course_id);

    ActivityLogClass::log('เพิ่ม review_choice', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->route('review_index',['review_group_id'=>$review_group_id])->with('status',200);
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
  public function review_delete($review_id){
    $review = Review::find($review_id);
    $review->status = 2;
    $review->save();

    $this->update_course($review->course_id);

    ActivityLogClass::log('ลบ review', new ObjectId(Auth::user()->_id), $review->getTable(), $review->getAttributes(),Auth::user()->username);
    return redirect()->route('course_create', ['id' => $review->course_id, '#review']);
  }
}
