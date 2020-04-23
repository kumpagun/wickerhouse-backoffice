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
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
// Controller
use App\Http\Controllers\Course\CourseController;
// Model
use App\Models\Course;
use App\Models\Mail_log;
use App\Models\Member;
use App\Models\Question;
use App\Models\Teacher;
use App\Models\Training;


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
    return redirect()->back()->with('status',200);
  }

  public function question_send_email(Request $request){
    $question_id = $request->input('question_id');
    $teacher_id = $request->input('teacher_id');
    $email_replys = [];
    $teacher_ids = [$teacher_id];
    $email_subject = '';
    $email_body = '';
    $secret = '10F2A09msxV33';
    $time = Carbon::now()->timestamp;
    $key = md5($secret.$time); 

    $now = new UTCDateTime(Carbon::now()->timestamp * 1000);

    $teacher = Teacher::find($teacher_id);
    $question = Question::find($question_id);
    $course = Course::where('_id', new ObjectId($question->course_id))->first();
    $member = Member::where('_id', new ObjectId($question->user_id))->first();

    $email_subject = 'Jas Online Learning: คุณมีคำถามจากคอร์สเรียน '.$course->title;
   
    if(!empty($member)) {
      array_push($email_replys, 'sorachai.b@mono.co.th');
      // array_push($email_replys, $member->email);
    }
   
    $email_body = "<strong>เรียนคุณ ".$teacher->name."</strong><br/><br/>";
    $email_body .= "<p>คุณมีคำถามจาก Jas Online Learning รายละเอียดดังนี้</p>";
    $email_body .= "<p><strong>วันที่</strong>: ".FuncClass::utc_to_carbon_format_time_zone_bkk($question->created_at)."</p>";
    $email_body .= "<p><strong>หลักสูตร</strong>: ".$course->title."</p>";
    $email_body .= "<p><strong>ผู้ถาม</strong>: ".$member->fullname."</p>";
    $email_body .= "<p><strong>คำถาม</strong>: ".$question->question."</p><br/><br/>";
    $email_body .= "<p>กรุณาตอบกลับทาง Link นี้: <a href='".URL::previous()."'>".URL::previous()."</a></p>";

    

    $client = new Client();
    try {
      $url = 'https://api-dev.jasonlinelearning.com/mail/send_qa_teacher';
      $params = [
        'question_id' => $question_id,
        'teacher_ids' => $teacher_ids,
        'email_replys' => $email_replys,
        'subject' => $email_subject,
        'body' => $email_body,
        'time' => $time,
        'key' => $key
      ];
      $res = $client->request('POST', $url, [
        'form_params' => $params
      ]);
      $status_code = $res->getStatusCode();
      $body = $res->getBody();

      $mail_log = new Mail_log();
      if ($status_code == 200) {
        $data = json_decode($body, true);

        $mail_log->course_id = new ObjectId($question->course_id);
        $mail_log->training_id = new ObjectId($question->training_id);
        $mail_log->user_id = [new ObjectId($question->user_id)];
        $mail_log->employee_id = [$member->employee_id];
        $mail_log->teacher_id = [new ObjectId($teacher_id)];
        $mail_log->question_id = new ObjectId($question_id);
        $mail_log->type = 'question';
        $mail_log->sent_to = [$teacher->email];
        $mail_log->reply_to = $email_replys;
        $mail_log->subject = $email_subject;
        $mail_log->body = $email_body;
        if($data['code']==200) {
          $mail_log->status = 1;
          $question->sent_mail = $teacher->email;
          $question->sent_at = $now;
          $question->save();
        } else {
          $mail_log->status = 0;
        }
        $mail_log->api_callback = $data;
        $mail_log->save();
      }
    } catch (GuzzleException $e) {
      dd($e);
    }

    return redirect()->back()->with('status',200);
  }
}
