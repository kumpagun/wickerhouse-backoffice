<?php
namespace App\Http\Controllers\Report;

use Mem;
use Response;
use DB;
use Auth;
use Member;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use App\ImportExcels\MembersImport;
use Maatwebsite\Excel\Facades\Excel;
// Model
use App\Models\Report_member_access_except_training as Report_member_access;
use App\Models\Course;
use App\Models\Employee;

class MemberAccessByUserController extends Controller
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
      $employee_id = Member::get_employee_id_from_head();
    }

    $query_group = Course::query()->where('status',1)->orderBy('created_at','desc')->get();
    if(empty($search_group)){
      $group_id_select = $query_group[0]->_id;
    } else {
      $group_id_select = $search_group;
    }
    $datas_group = Course::find($group_id_select);
    $group_id = new ObjectId($datas_group->_id);
    $course_id = new ObjectId($datas_group->_id); 

    $get_last_date = Report_member_access::where('status',1)->where('course_id', $course_id)->orderBy('created_at','desc')->first();
    if(!empty($get_last_date)) {
      $filter_date = new UTCDateTime(Carbon::parse($get_last_date->created_at)->startOfDay());
    }

    $query = Report_member_access::query()->where('status',1)->where('course_id', $course_id);
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $query->whereIn('employee_id',$employee_id);
    }  
    if($filter_status=='active') {
      $query->where('play_course','>',0);
    } else if($filter_status=='inactive') {
      $query->where('play_course',0);
    }
    if(!empty($filter_date)) {
      $query->where('created_at','>=',$filter_date);
    }
    $datas = $query->get();

    $group_name = $datas_group->title;
    $now = Carbon::now()->format('dmY');
    $file = $group_name.'_'.$now.'.xls';
    $path = config('app.url').'storage/excel/exports/'.$group_id_select.'/'.$file;

    if($platform=='excel') {
      return Excel::download(new Export_Report_member_access_by_user($group_name,$datas), Carbon::now()->timestamp.'.xlsx');
    }

    $update_date = '-';
    if(!empty($datas[0])) {
      $update_date = $datas[0]->created_at;
    }

    return view('report.member_access_by_user', [
      'datas' => $datas,
      'filter_status' => $filter_status,
      'query_group' => $query_group,
      'search_group' => $search_group,
      'update_date' => $update_date,
      'path' => $path
    ]);
  }
}
