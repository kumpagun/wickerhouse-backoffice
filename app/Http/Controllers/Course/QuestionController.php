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
use App\Models\Training;
use App\Models\Question;


class QuestionController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function question_index(){
    $datas = Course::where('type','standard')->where('status',1)->get();
    return view('question.question_index',['datas' => $datas]);
  }
  
  public function question_answer_index($course_id,$type=''){
    $course = Course::find($course_id);
    $query = Question::where('course_id',new ObjectId($course_id))->where('status',1);
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
    return view('question.question_answer_index',$withData);
  }

  public function question_answer_store(Request $request){
    $question_answer_id = $request->input('question_answer_id');
    $answer = $request->input('answer');

    $rules = [
      'answer' => 'required'
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'question')->withInput();
    }

    $question = Question::find($question_answer_id);
    $question->answer = $answer;
    $question->answer_at = new UTCDateTime(Carbon::now()->timestamp * 1000);
    $question->answer_by = new ObjectId(Auth::user()->_id);
    $question->save();

    ActivityLogClass::log('ตอบคำถาม question', new ObjectId(Auth::user()->_id), $question->getTable(), $question->getAttributes(),Auth::user()->username);
    return redirect()->back();
  }
}
