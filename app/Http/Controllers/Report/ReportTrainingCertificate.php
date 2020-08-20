<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use Jenssegers\Agent\Agent;
use DB;
use PDF;
use CourseClass;
use FuncClass;
use Excel;
use Auth;
use Member;

// Model
use App\Models\Report_member_access;
use App\Models\Report_member_access_by_course;
use App\Models\Training;
use App\Models\Employee;
use App\Models\Member as Member_model;
use App\Models\Course;
use App\Models\User_play_course_end;

class ReportTrainingCertificate extends Controller
{
  public function __construct()
  {
    
  }

  public function index() {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $employees = Employee::where('status',1)->get(); 
    $withData = [
      'datas' => $employees
    ];
    return view('report.training_certificate',$withData);
  }

  public function certificate($employee_id) {
    $member = Member_model::where('employee_id',$employee_id)->first();
    $trainings = Training::where('status',1)->get();
    $training_end = [];
    foreach($trainings as $training) {
      $course = Course::find($training->course_id);
      $total_ep = $course->total_episode;
      $total_end = User_play_course_end::where('user_id',new ObjectId($member->_id))->where('training_id',new ObjectId($training->_id))->select('episode_id')->groupBy('episode_id')->count();
      $result = [];
      if($total_ep==$total_end) {
        $result = [
          'title' => $course->title,
          'created_at' => FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($training->created_at,'d/m/Y')
        ];
        array_push($training_end,$result);
      }
    }
    $withData = [
      'member' => $member,
      'training_end' => $training_end
    ];

    $filename = 'หนังสือรับรองการฝึกอบรม '.$member->fullname.'.pdf';

    // return view('report.certificate', $withData);
    $pdf = PDF::loadView('report.certificate', $withData);
    return $pdf->download($filename);
  }
}