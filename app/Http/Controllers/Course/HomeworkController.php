<?php

namespace App\Http\Controllers\Course;

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
// Controller
use App\Http\Controllers\Course\CourseController;
// Model
use App\Models\Course;
use App\Models\Homework;
use App\Models\Category;
use App\Models\Teacher;


class HomeworkController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_homework($course_id) {
    $data = Homework::where('course_id',new ObjectId($course_id))->where('status',1)->first();
    return $data;
  }
  public function update_course($course_id) {
    $course = Course::find($course_id);
    $homework = Homework::where('course_id', new ObjectId($course_id))->where('status',1)->first(); 
    if(!empty($homework)) {
      $course->have_homework = true;
    } else {
      $course->have_homework = false;
    }
    $course->save();
    return true;
  }
  public function homework_index(){
    $datas = Homework::query()->where('status','!=',0)->get();
    return view('homework.homework_index',['datas' => $datas]);
  }
  public function homework_create($course_id, $id=''){
    $course_controller = new CourseController;
    $courses = $course_controller->get_course();

    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->course_id = $course_id;
      $data->question = '';
      $data->answer_type = '';
      $data->status = 1;
    } else {
      $data = Homework::find($id);
    }
    $withData = [
      'id' => $id,
      'data' => $data,
      'courses' => $courses
    ]; 
    return view('homework.homework_detail',$withData);
  }
  public function homework_store(Request $request){
    $id = $request->input('id');
    $course_id = $request->input('course_id');
    $question = $request->input('question');

    $rules = [
      'course_id' => 'required',
      'question' => 'required'
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'homework')->withInput();
    }

    if(!empty($id)) {
      $homework = Homework::find($id);
    } else {
      $homework = new Homework();
    }
    $homework->question = $question;
    $homework->course_id = new ObjectId($course_id);
    $homework->status = 1;
    $homework->save();

    $this->update_course($course_id);

    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข Homework', new ObjectId($current_user->_id), $homework->getTable(), $homework->getAttributes(),$current_user->username);
    // return redirect()->route('homework_index');
  
    return redirect()->route('course_create', ['id' => $course_id, '#homework']);
  }

  public function homework_delete($id){
    $homework = Homework::find($id);
    $homework->status = 0;
    $homework->save();

    $this->update_course($homework->course_id);

    $current_user = Auth::user();
    ActivityLogClass::log('ลบ Homework', new ObjectId($current_user->_id), $homework->getTable(), $homework->getAttributes(),$current_user->username);

    return redirect()->route('course_create', ['id' => $homework->course_id, '#homework']);
  }
}
