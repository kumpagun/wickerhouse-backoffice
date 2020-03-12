<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\ImportExcels\QuizImport;
use Maatwebsite\Excel\Facades\Excel;
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
use App\Models\Episode;
use App\Models\Quiz_group;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\Training;

class QuizController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_quiz_group($course_id) {
    $data = Quiz_group::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    return $data;
  }
  public function get_quiz($quiz_group) {
    $data = Quiz::where('_id',new ObjectId($quiz_group))->first();
    return $data;
  }
  public function get_episode_not_selected($course_id) {
    $quiz_group = Quiz_group::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    $ep_selected = [];
    foreach($quiz_group as $row) {
      array_push($ep_selected, new ObjectId($row->episode_id));
    }
 
    $data = Episode::where('course_id',new ObjectId($course_id))->whereNotIn('_id',$ep_selected)->get();

    return $data;
  }
  public function update_course($course_id,$episode_id) {
    $episode = Episode::find($episode_id);
    $episode->have_quiz = false;
    $episode->total_quiz = 0;
    $episode->save();
    
    $quiz_group = Quiz_group::where('course_id', new ObjectId($course_id))->where('episode_id',new ObjectId($episode_id))->where('status',1)->get(); 
    foreach($quiz_group as $row) {
      $total = Quiz::where('quiz_group_id', new ObjectId($row->_id))->where('status',1)->get();
      $episode->have_quiz = true;
      $episode->total_quiz = $total->count();
    }
    $episode->save();
    return true;
  }
  public function quiz_group_store(Request $request){
    $course_id = $request->input('course_id');
    $episode_id = $request->input('episode_id');

    $quiz = new Quiz_group();
    $quiz->course_id = new ObjectId($course_id);
    $quiz->episode_id = new ObjectId($episode_id);
    $quiz->status = 1;
    $quiz->save();

    $this->update_course($course_id,$episode_id);

    ActivityLogClass::log('เพิ่มหรือแก้ไข quiz_group', new ObjectId(Auth::user()->_id), $quiz->getTable(), $quiz->getAttributes(),Auth::user()->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#quiz']);
  }
  public function quiz_group_delete($id){
    $quiz_group = Quiz_group::find($id);
    $quiz_group->status = 2;
    $quiz_group->save();

    Quiz::where('quiz_group_id',new ObjectId($id))->update(['status' => 0]);
    Episode::where('_id',new ObjectId($quiz_group->episode_id))->update(['passing_point' => null]);

    $this->update_course($quiz_group->course_id,$quiz_group->episode_id);

    ActivityLogClass::log('ลบ quiz_group', new ObjectId(Auth::user()->_id), $quiz_group->getTable(), $quiz_group->getAttributes(),Auth::user()->username);
    return redirect()->route('course_create', ['id' => $quiz_group->course_id, '#quiz']); 
  }

  public function quiz_index($id){
    $quiz_group = Quiz_group::where('_id',new ObjectId($id))->first();
    $quiz = Quiz::where('quiz_group_id',new ObjectId($id))->where('status',1)->get();
    $course = Course::find($quiz_group->course_id);
    $episode = Episode::find($quiz_group->episode_id);
    $datas = [
      'quiz_group' => $quiz_group,
      'quiz' => $quiz,
      'course' => $course,
      'episode' => $episode
    ];

    return view('quiz.quiz_index',$datas);
  }


  public function quiz_create($quiz_group_id, $id=''){
    $quiz_group = Quiz_group::where('_id',new ObjectId($quiz_group_id))->first();
    
    if(empty($id)) {
      $quiz = new \stdClass();
      $quiz->_id = '';
      $quiz->question = '';
      $quiz->answer_value = [];
      $quiz->answer_key = '';
      $quiz->choice[0]['title'] = '';
      $quiz->choice[1]['title'] = '';
      $quiz->choice[2]['title'] = '';
      $quiz->choice[3]['title'] = '';
    } else {
      $quiz = Quiz::where('_id',new ObjectId($id))->first();
    }

    $datas = [
      'quiz_group' => $quiz_group,
      'quiz' => $quiz
    ];

    return view('quiz.quiz_detail',$datas);
  }

  public function quiz_detail_store(Request $request){
    $quiz_group_id = $request->input('quiz_group_id');
    $passing_point = $request->input('passing_point');
    $quiz_group = Quiz_group::find($quiz_group_id);
    $episode = Episode::find($quiz_group->episode_id);
    $episode->passing_point = $passing_point;
    $episode->save();

    ActivityLogClass::log('แก้ไข episode ', new ObjectId(Auth::user()->_id), $episode->getTable(), $episode->getAttributes(),Auth::user()->username);
    
    return redirect()->route('quiz_index', ['id' => $quiz_group_id])->with('status',200);
  }

  public function quiz_store(Request $request){
    $quiz_group_id = $request->input('quiz_group_id');
    $id = $request->input('id');
    $question = $request->input('question');
    $answer_key = $request->input('answer_key');
    $choice_0 = $request->input('choice_0');
    $choice_1 = $request->input('choice_1');
    $choice_2 = $request->input('choice_2');
    $choice_3 = $request->input('choice_3');

    $choice = $this->quiz_format_choice($choice_0,$choice_1,$choice_2,$choice_3);
    $quiz_group = Quiz_group::find(new ObjectId($quiz_group_id));
    if(!empty($id)) {
      $quiz = Quiz::find($id);
    } else {
      $quiz = new Quiz();
    }
    $quiz->course_id = new ObjectId($quiz_group->course_id);
    $quiz->episode_id = new ObjectId($quiz_group->episode_id);
    $quiz->quiz_group_id = new ObjectId($quiz_group->_id);
    $quiz->question = $question;
    $quiz->answer_key = (int)$answer_key;
    $quiz->answer_value = $choice[$answer_key];
    $quiz->choice = $choice;
    $quiz->status = 1;
    $quiz->save();

    $this->update_course($quiz_group->course_id,$quiz_group->episode_id);

    ActivityLogClass::log('เพิ่มหรือแก้ไข quiz', new ObjectId(Auth::user()->_id), $quiz->getTable(), $quiz->getAttributes(),Auth::user()->username);
    
    return redirect()->route('quiz_index', ['id' => $quiz_group_id, '#'.$id])->with('status',200);
  }

  public function quiz_format_choice($choice_0,$choice_1,$choice_2,$choice_3) {
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

  public function quiz_delete($id){
    $quiz = Quiz::find($id);
    $quiz->status = 2;
    $quiz->save();

    $quiz_group = Quiz_group::find($quiz->quiz_group_id);

    $this->update_course($quiz_group->course_id,$quiz_group->episode_id);

    ActivityLogClass::log('ลบ quiz', new ObjectId(Auth::user()->_id), $quiz->getTable(), $quiz->getAttributes(),Auth::user()->username);
    return redirect()->route('quiz_index', ['id' => $quiz->quiz_group_id, '#'.$id])->with('status',200);
  }

  public function quiz_import_excel(Request $request) {
    $quiz_group_id = $request->input('quiz_group_id');
    $excel = $request->file('excel');
    $rules  = [
      'excel'       => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel');
    }
    $quiz_group = Quiz_group::find($quiz_group_id); 
    $course_id = $quiz_group->course_id;
    $user_action = Auth::user()->_id;
    $original_filename = $excel->getClientOriginalName();
    $file_path = '';
    
    $path = $excel->getRealPath();
    $extension = $excel->getClientOriginalExtension();
    $current_date =  Carbon::now()->timestamp;
    $name_excel = $current_date.'_excel.'.$extension;
    $path_file ='File/excel_quiz/backup/'.(string)$quiz_group_id;
    
    try{
      File::isDirectory($path_file) or File::makeDirectory($path_file, 0777, true, true);
      $excel->move($path_file,$name_excel);
    } catch (\Exception  $e) {
      return redirect()->back()->with('msg', 'ไม่สามารถ เก็บ File ได้');    
    }
    $path_file_name = $path_file.'/'.$name_excel;
    $file_path = $path_file_name;
    try{
      $datas = Excel::toCollection(new QuizImport,public_path($path_file_name));
      $data_insert = [];
      foreach($datas[0] as $key => $value){
        if($key>3 && !empty($value[1]) && !empty($value[2]) && !empty($value[3]) && !empty($value[4]) && !empty($value[5]) && !empty($value[6])) {
          $question = $value[1];
          $choice_0 = $value[2];
          $choice_1 = $value[3];
          $choice_2 = $value[4];
          $choice_3 = $value[5];
          $answer_value = $value[6];
          $answer_key = 0;
          if($answer_value==$choice_0) {
            $answer_key = 0;
          } else if($answer_value==$choice_1) {
            $answer_key = 1;
          } else if($answer_value==$choice_2) {
            $answer_key = 2;
          } else if($answer_value==$choice_3) {
            $answer_key = 3;
          }
          $choice = $this->quiz_format_choice($choice_0,$choice_1,$choice_2,$choice_3);

          $quiz = new Quiz();
          $quiz->course_id = new ObjectId($quiz_group->course_id);
          $quiz->episode_id = new ObjectId($quiz_group->episode_id);
          $quiz->quiz_group_id = new ObjectId($quiz_group->_id);
          $quiz->question = $question;
          $quiz->answer_key = (int)$answer_key;
          $quiz->answer_value = $choice[$answer_key];
          $quiz->choice = $choice;
          $quiz->status = 1;
          $quiz->save();

          $this->update_course($quiz_group->course_id,$quiz_group->episode_id);
        }
      }
      ActivityLogClass::log('เพิ่มหรือแก้ไข quiz', new ObjectId(Auth::user()->_id), $quiz->getTable(), $quiz->getAttributes(),Auth::user()->username);
      return redirect()->route('quiz_index', ['id' => $quiz_group_id, '#'.$id])->with('status',200);

    } catch (\Exception  $e) {
      // ActivityLogClass::log_end_import_excel($id_log,$total_import,$total_error,$total_duplicate);
      return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่ถูกต้อง');    
    }
  }
}
