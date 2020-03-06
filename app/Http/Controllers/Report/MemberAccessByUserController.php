<?php
namespace App\Http\Controllers\Report;

use Mem;
use Response;
use DB;

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

class MemberAccessByUserController extends Controller
{
  public function access_content_by_user (Request $request)
  {
    $search_input = $request->input('search_input');
    $search_group = $request->input('search_group');

    $query_group = Training::query()->where('status',1)->get();
    if(empty($search_group)){
      $group_id_select = $query_group[0]->_id;
    } else {
      $group_id_select = $search_group;
    }

    $datas_group = Training::find($group_id_select);
    $group_id = new ObjectId($datas_group->_id);
    $course_id = new ObjectId($datas_group->course_id);
    $datas = Report_member_access::query()->where('status',1)->where('training_id', $group_id )->where('course_id', $course_id)->get();

    $group_name = $datas_group->title;
    $now = Carbon::now()->format('dmY');
    $file = $group_name.'_'.$now.'.xls';
    $path = config('app.url').'storage/excel/exports/'.$group_id_select.'/'.$file;

    $update_date = '-';
    if(!empty($datas[0])) {
      $update_date = $datas[0]->created_at;
    }

    return view('report.member_access_by_user', [
      'datas' => $datas,
      'query_group' => $query_group,
      'search_group' => $search_group,
      'update_date' => $update_date,
      'path' => $path
    ]);
  }

  public function access_content_by_user_excel (Request $request)
  {
    ini_set('memory_limit', '-1');
    $search_input = $request->input('search_input');
    $search_group = $request->input('search_group');

    $query_group = Training::query()->where('status',1)->get();
    if(empty($search_group)){
      $group_id_select = $query_group[0]->_id;
    } else {
      $group_id_select = $search_group;
    }

    $training = Training::find($group_id_select);
    $group_name = $training->title;
    $now = Carbon::now()->format('dmY');
    $file = $group_name.'_'.$now.'.xls';

    $path = public_path('excel/exports/'.$group_id_select.'/'.$file);

    return response()->download($path, $file, [
      'Content-Type' => 'application/vnd.ms-excel',
      'Content-Disposition' => "attachment; filename='report.xls'"
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
