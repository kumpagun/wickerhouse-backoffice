<?php
namespace App\Http\Controllers\Report;

use Mem;
use Response;
use DB;
use Auth;

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
use App\Models\Employee;

class MemberAccessByUserTrainingController extends Controller
{
  public function get_employee_id_from_head() {
    $employee_id = Auth::user()->username;
    $arr_employee_id = [];
    array_push($arr_employee_id, $employee_id);
    $employees = Employee::whereIn('heads', $arr_employee_id)->get();

    $data_back = [];
    if(!empty($employees)) {
      foreach($employees as $employee) {
        array_push($data_back, $employee->employee_id);
      }
    } 

    return $data_back;
  }

  public function access_content_by_user (Request $request)
  {
    $search_input = $request->input('search_input');
    $search_group = $request->input('search_group');
    $filter_status = $request->input('filter_status');
    $platform = $request->input('platform');

    $employee_id = [];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $employee_id = $this->get_employee_id_from_head();
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
      return Excel::download(new Export_Report_member_access_by_user($datas), Carbon::now()->timestamp.'.xlsx');
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
}
