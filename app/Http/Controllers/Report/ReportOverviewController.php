<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use DB;
use PDF;
use CourseClass;
use FuncClass;
use Excel;
use Auth;
use Member;
// Model
use App\Models\Report_member_access;
use App\Models\Training;
use App\Models\Employee;
use App\Models\Course;

class ReportOverviewController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(Request $request) {
    $date_start = $request->input('date_start');
    $date_end = $request->input('date_end');

    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $employee_id = Member::get_employee_id_from_head();
    }

    $all_course_type = $this->get_course_type($date_start,$date_end);
  }

  public function get_course_type($date_start='',$date_end='') {
    $courses = Course::where('status',1)->select('type')->get();
    $datas = [];
    foreach($courses as $course) {
      if(empty($datas[$course->type])) {
        $datas[$course->type] = 0;
      }
      $datas[$course->type] += 1;
    }
    return $datas;
  }
}