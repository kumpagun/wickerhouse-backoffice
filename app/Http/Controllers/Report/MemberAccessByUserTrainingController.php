<?php
namespace App\Http\Controllers\Report;

use Mem;
use Response;
use DB;
use Auth;
use Member as MemberClass;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use App\ImportExcels\MembersImport;
use Maatwebsite\Excel\Facades\Excel;
// Model
use App\Models\Report_member_access;
use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\Employee;
use App\Models\Member;

use App\Models\Examination_user;
use App\Models\Examination_answer;

use App\Models\User_play_course_end;
use App\Models\User_play_course;
use App\Models\User_play_course_log;
use App\Models\User_view_course;

use App\Models\Review_answer;
use App\Models\Reviews_user;


class MemberAccessByUserTrainingController extends Controller
{
  public function access_content_by_user (Request $request)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $search_input = $request->input('search_input');
    $search_group = $request->input('search_group');
    $filter_status = $request->input('filter_status');
    $platform = $request->input('platform');

    $employee_id = [];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $employee_id = MemberClass::get_employee_id_from_head();
    }

    $query_group = Training::query()->where('status',1)->orderBy('created_at','desc')->get();
    if(empty($search_group)){
      $group_id_select = $query_group[0]->_id;
    } else {
      $group_id_select = $search_group;
    }

    $datas_group = Training::find($group_id_select);
    $group_id = new ObjectId($datas_group->_id);
    $course_id = new ObjectId($datas_group->course_id);
    $query = Report_member_access::query()->where('status',1)->where('training_id', $group_id )->where('course_id', $course_id);
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $query->whereIn('employee_id',$employee_id);
    }  
    if($filter_status=='active') {
      $query->where('play_course','>',0);
    } else if($filter_status=='inactive') {
      $query->where('play_course',0);
    }
    $datas = $query->get();

    $group_name = $datas_group->title;
    $now = Carbon::now()->format('dmY');
    $file = $group_name.'_'.$now.'.xls';
    $path = config('app.url').'storage/excel/exports/'.$group_id_select.'/'.$file;

    if($platform=='excel') {
      return Excel::download(new Export_Report_training_member_access_by_user($group_name,$datas), Carbon::now()->timestamp.'.xlsx');
    }

    $update_date = '-';
    if(!empty($datas[0])) {
      $update_date = $datas[0]->created_at;
    }

    return view('report.member_access_by_user_training', [
      'datas' => $datas,
      'filter_status' => $filter_status,
      'query_group' => $query_group,
      'search_group' => $search_group,
      'update_date' => $update_date,
      'path' => $path
    ]);
  }
  
  // Excel
  public function crontab_access_content_excel ()
  {
    ini_set('max_execution_time', 300); //300 seconds = 5 minutes
    set_time_limit(300);
    ini_set('memory_limit', '-1');
    $query_groups = Training::query()->where('status',1)->get();
    foreach($query_groups as $query_group) {
      $training_id = $query_group->_id;
      $training = Training::find($training_id);
      $group_name = $training->title;
      $now = Carbon::now()->format('dmY');
      $group_id = new ObjectId($training->_id);
      $course_id = new ObjectId($training->course_id);
      // $query = Report_member_access::query()->where('status',1)->where('training_id', $group_id )->where('course_id', $course_id);
      
      $title = $group_name.'_'.$now;

      Excel::store(new Export_Report_member_access($group_id,$course_id), 'app/public/excel/exports/'.$training_id.'/'.$title.'.xls');
    }
  }

  public function update_user_training() {
    $course_id = new ObjectId("5eaa52c91b5e685f21185c55");
    $training_id = new ObjectId("5eac2b6f4dec115cf2115c46");
    $training_user = TrainingUser::where('course_id',$course_id)->where('training_id',$training_id)->where('status',1)->get();
    $employee_id = [];
    foreach($training_user as $row) {
      array_push($employee_id, $row->employee_id);
    }
    $members = Member::whereIn('employee_id',$employee_id)->get();
    $member_id = [];
    foreach($members as $row) {
      array_push($member_id, new ObjectId($row->_id));
    }

    $examination_user = Examination_user::whereIn('user_id',$member_id)->where('course_id',$course_id)->whereNull('training_id')->select('user_id')->groupBy('user_id')->get();
    $member_id_training_null = [];
    foreach($examination_user as $row) {
      array_push($member_id_training_null, $row->user_id);
    }

    foreach($member_id_training_null as $mem_id) {
      Examination_user::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      Examination_answer::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      User_play_course_end::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      User_play_course::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      User_play_course_log::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      User_view_course::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      Review_answer::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
      Reviews_user::where('course_id',$course_id)->where('user_id',new ObjectId($mem_id))->whereNull('training_id')->update(['training_id' => $training_id]);
    }
  }
}
