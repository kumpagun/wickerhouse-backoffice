<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;
use ActivityLogClass;
use Auth;
use CourseClass;
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\Member;
use App\Models\Employee;
use App\Models\Employee_vip;
use App\Models\Mail_log;

class EmailLogController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(Request $request) {
    $filter_type = $request->input('filter_type');
    $type = ['new_training','alert_training_not_complete'];

    $query = Mail_log::where('status',1);
    if(!empty($filter_type)) {
      $query->where('type',$filter_type);
    } else {
      $query->whereIn('type',$type);
    }
    $query->orderBy('created_at','desc');
    $mail_log = $query->paginate(25);

    $withInput = [
      'type' => $type,
      'filter_type' => $filter_type,
      'mail_log' => $mail_log
    ];

    return view('email_log.email_log-list', $withInput);
  }

  public function detail(Request $request, $mail_log_id) {
    $platform = $request->input('platform');
    $mail_log = Mail_log::find($mail_log_id);
    $employee = '';
    if(!empty($mail_log->employee_id)) {
      $employee = Employee::whereIn('employee_id',$mail_log->employee_id)->get();
    }

    $withInput = [
      'mail_log' => $mail_log,
      'employee' => $employee
    ];

    if($platform=='excel') {
      if($mail_log->type=='new_training') {
        $title_type = 'รอบการอบรมใหม่';
      } elseif($mail_log->type=='alert_training_not_complete') {
        $title_type = 'แจ้งเตือนผู้ที่ยังไม่ผ่านการอบรม';
      }
      $title = CourseClass::get_training_name($mail_log->training_id).'_'.$title_type.'_'.Carbon::now()->timestamp;
      return Excel::download(new Export_Email_log($employee), $title.'.xlsx');
    }

    return view('email_log.email_log-detail', $withInput);
  }
}
