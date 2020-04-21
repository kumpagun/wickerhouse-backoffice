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
    $filter_date = $request->input('filter_date');

    $type = ['new_training','alert_training_not_complete'];

    if(!empty($filter_date)) {
      $filter_date = explode('-', $filter_date);
      $str_date_start = str_replace(' ', '', $filter_date[0]);
      $str_date_end = str_replace(' ', '', $filter_date[1]);
      $calendar_date_start = Carbon::parse($str_date_start)->format('Y-m-d');
      $calendar_date_end = Carbon::parse($str_date_end)->format('Y-m-d');
      
      $date_start  = new UTCDateTime(Carbon::parse($str_date_start)->startOfDay());
      $date_end  = new UTCDateTime(Carbon::parse($str_date_end)->endOfDay());
    } else {
      $calendar_date_start = Carbon::now()->subDays(6)->format('Y-m-d');
      $calendar_date_end = Carbon::now()->format('Y-m-d');

      $date_start  = new UTCDateTime(now()->subDays(6)->startOfDay());
      $date_end  = new UTCDateTime(now()->endOfDay());
    }

    $query = Mail_log::where('status',1);
    if(!empty($filter_type)) {
      $query->where('type',$filter_type);
    } else {
      $query->whereIn('type',$type);
    }
    $query->where('created_at','>=',$date_start);
    $query->where('created_at','<=',$date_end);
    $mail_log = $query->get();

    $withInput = [
      'filter_type' => $filter_type,
      'calendar_date_start' => $calendar_date_start,
      'calendar_date_end' => $calendar_date_end,
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
