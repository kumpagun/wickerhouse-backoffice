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
use App\Models\Examination_group;
use App\Models\Examination;
use App\Models\Course;

class ExaminationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_examination_group($course_id) {
    $data = Examination_group::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    return $data;
  }
  public function get_examination_group_pretest($course_id) {
    $data = Examination_group::where('course_id',new ObjectId($course_id))->where('type','pretest')->where('status',1)->first();
    return $data;
  }
  public function get_examination_group_posttest($course_id) {
    $data = Examination_group::where('course_id',new ObjectId($course_id))->where('type','posttest')->where('status',1)->first();
    return $data;
  }
  public function get_examination($examination_group) {
    $data = Examination::find(new ObjectId($examination_group))->first();
    return $data;
  }
  public function update_course($course_id) {
    $course = Course::find($course_id);
    $course->have_pretest = false;
    $course->have_posttest = false;
    $course->total_pretest = 0;
    $course->total_posttest = 0;
    $course->save();
    
    $examination_group = Examination_group::where('course_id', new ObjectId($course_id))->where('status',1)->get(); 
    foreach($examination_group as $row) {
      $total = Examination::where('exam_group_id', new ObjectId($row->_id))->where('status',1)->count();
      if(in_array('pretest',$row->type)) {
        $course->have_pretest = true;
        $course->total_pretest = $total;
      } else if(in_array('posttest',$row->type)) {
        $course->have_posttest = true;
        $course->total_posttest = $total;
      } else {
        $course->have_pretest = true;
        $course->total_pretest = $total;
        $course->have_posttest = true;
        $course->total_posttest = $total;
      }
    }
    $course->save();
    return true;
  }
  public function examination_group_store(Request $request){
    $course_id = $request->input('course_id');
    $type = $request->input('type');
    $arr_type = [];
    if($type=='pretest' || $type=='posttest') {
      array_push($arr_type, $type);
    } else {
      array_push($arr_type, 'pretest');
      array_push($arr_type, 'posttest');
    }

    $examination = new Examination_group();
    $examination->course_id = new ObjectId($course_id);
    $examination->type = $arr_type;
    $examination->status = 1;
    $examination->save();

    $this->update_course($course_id);

    ActivityLogClass::log('เพิ่มหรือแก้ไข examination_group', new ObjectId(Auth::user()->_id), $examination->getTable(), $examination->getAttributes(),Auth::user()->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#examination']);
  }
  public function examination_group_delete($id){
    $examination = Examination_group::find($id);
    $examination->status = 0;
    $examination->save();

    $this->update_course($examination->course_id);

    ActivityLogClass::log('ลบ examination_group', new ObjectId(Auth::user()->_id), $examination->getTable(), $examination->getAttributes(),Auth::user()->username);
    return redirect()->route('course_create', ['id' => $examination->course_id, '#examination']); 
  }

  public function examination_index($id){
    $examination_group = Examination_group::where('_id',new ObjectId($id))->first();
    $examination = Examination::where('exam_group_id',new ObjectId($id))->where('status',1)->get();

    $datas = [
      'examination_group' => $examination_group,
      'examination' => $examination
    ];

    return view('examination.examination_index',$datas);
  }

  public function course_posttest_update(Request $request) {
    $posttest_limit = true;
    $posttest_limit_total = 10;

    $posttest_duretion = true;
    $posttest_duretion_sec = 10;

    $posttest_passing_point = 10;

    $have_pretest = true;
    $total_pretest = 10;

    $have_posttest = true;
    $total_posttest = 10;
  }

  public function examination_create($examination_group_id, $id=''){
    $examination_group = Examination_group::where('_id',new ObjectId($examination_group_id))->first();

    if(empty($id)) {
      $examination = new \stdClass();
      $examination->_id = '';
      $examination->question = '';
      $examination->answer_value = [];
      $examination->answer_key = '';
      $examination->choice[0]['title'] = '';
      $examination->choice[1]['title'] = '';
      $examination->choice[2]['title'] = '';
      $examination->choice[3]['title'] = '';
    } else {
      $examination = Examination::where('_id',new ObjectId($id))->first();
    }

    $datas = [
      'examination_group' => $examination_group,
      'examination' => $examination
    ];

    return view('examination.examination_detail',$datas);
  }

  public function examination_store(Request $request){
    $examination_group_id = $request->input('examination_group_id');
    $id = $request->input('id');
    $question = $request->input('question');
    $answer_key = $request->input('answer_key');
    $choice_0 = $request->input('choice_0');
    $choice_1 = $request->input('choice_1');
    $choice_2 = $request->input('choice_2');
    $choice_3 = $request->input('choice_3');

    $choice = $this->examination_format_choice($choice_0,$choice_1,$choice_2,$choice_3);
    $examination_group = Examination_group::find(new ObjectId($examination_group_id));
    if(!empty($id)) {
      $examination = Examination::find($id);
    } else {
      $examination = new Examination();
    }
    $examination->course_id = new ObjectId($examination_group->course_id);
    $examination->exam_group_id = new ObjectId($examination_group->_id);
    $examination->question = $question;
    $examination->answer_key = (int)$answer_key;
    $examination->answer_value = $choice[$answer_key];
    $examination->choice = $choice;
    $examination->status = 1;
    $examination->save();

    $this->update_course($examination_group->course_id);

    ActivityLogClass::log('เพิ่มหรือแก้ไข examination', new ObjectId(Auth::user()->_id), $examination->getTable(), $examination->getAttributes(),Auth::user()->username);
    
    return redirect()->route('examination_index', ['id' => $examination_group_id, '#'.$id]);
  }

  public function examination_format_choice($choice_0,$choice_1,$choice_2,$choice_3) {
    $datas = [];

    $choice = new \stdClass();
    $choice->title = $choice_0;
    $choice->key = 0;
    array_push($datas, $choice);

    $choice = new \stdClass();
    $choice->title = $choice_1;
    $choice->key = 1;
    array_push($datas, $choice);
    
    $choice = new \stdClass();
    $choice->title = $choice_2;
    $choice->key = 2;
    array_push($datas, $choice);

    $choice = new \stdClass();
    $choice->title = $choice_3;
    $choice->key = 3;
    array_push($datas, $choice);

    return $datas;
  }

  public function examination_delete($id){
    $examination = Examination::find($id);
    $examination->status = 0;
    $examination->save();

    $this->update_course($examination->course_id);

    ActivityLogClass::log('ลบ examination', new ObjectId(Auth::user()->_id), $examination->getTable(), $examination->getAttributes(),Auth::user()->username);
    return redirect()->route('examination_index', ['id' => $examination->exam_group_id, '#'.$id]);
  }
}
