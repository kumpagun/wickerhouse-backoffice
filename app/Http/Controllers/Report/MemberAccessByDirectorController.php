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
use Illuminate\Support\Collection;
// Model
use App\Models\Report_member_access;
use App\Models\Report_member_access_except_training;
use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\Employee;
use App\Models\Member;
use App\Models\Course;

use App\Models\Examination_user;
use App\Models\Examination_answer;

use App\Models\User_play_course_end;
use App\Models\User_play_course;
use App\Models\User_play_course_log;
use App\Models\User_view_course;

use App\Models\Review_answer;
use App\Models\Reviews_user;


class MemberAccessByDirectorController extends Controller
{
  public function access_content_by_director (Request $request)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $search_year = $request->input('search_year');
    $search_group = $request->input('search_group');
    $filter_status = $request->input('filter_status');
    $platform = $request->input('platform');

    if(empty($search_year)) {
      $search_year = date('Y');
    }

    $employee_id = [];
    $employee_id = MemberClass::get_employee_id_from_head();

    $datas = [];

    $query_group = Training::where('status',1)->orderBy('created_at','desc')->get();
    if(empty($search_group)){
      
    } else {
      $group_id_select = $search_group;
      $datas_group = Training::find($group_id_select);
      $group_id = new ObjectId($datas_group->_id);
      $course_id = new ObjectId($datas_group->course_id);

      $active_emp = [];
      $inactive_emp = [];
      $user_training = [];
      $user_expect_training = [];

      // Training user
      $query = Report_member_access::where('status',1)->where('training_id', $group_id )->where('course_id', $course_id);
      $query->whereIn('employee_id',$employee_id);
      $query->where('play_course','>',0);
      $user_training = $query->get();
      foreach($user_training as $row) {
        array_push($active_emp, $row->employee_id);
      }

      $active_training = [];
      $active_training = array_diff($active_emp,$employee_id);

      // Expect training user
      $query = Report_member_access_except_training::where('status',1)->where('training_id', $group_id )->where('course_id', $course_id);
      $query->whereIn('employee_id',$active_training);
      $query->where('play_course','>',0);
      $user_expect_training = $query->get();
      foreach($user_expect_training as $row) {
        array_push($active_emp, $row->employee_id);
      }

      if($filter_status=='inactive') { 
        $user_training = [];
        $user_expect_training = [];
      }

      if($filter_status!='active') {
        $active_training = [];
        $active_training = array_diff($employee_id,$active_emp);
        // Inactive user
        $inactive_emp = Employee::where('status',1)->whereIn('employee_id',$active_training)->get();
      }

      $datas = new Collection();
      foreach($user_training as $row) {
        $result = [];
        $result['employee_id'] = $row['employee_id'];
        $result['tinitial'] = $row['tinitial'];
        $result['firstname'] = $row['firstname'];
        $result['lastname'] = $row['lastname'];
        $result['workplace'] = $row['workplace'];
        $result['title'] = $row['title'];
        $result['division'] = $row['division'];
        $result['section'] = $row['section'];
        $result['department'] = $row['department'];
        $result['branch'] = $row['branch'];
        $result['company'] = $row['company'];
        $result['region'] = $row['region'];
        $result['staff_grade'] = $row['staff_grade'];
        $result['job_family'] = $row['job_family'];
        $result['play_course'] = $row['play_course'];
        $result['pretest'] = $row['pretest'];
        $result['posttest'] = $row['posttest'];
        $result['play_course_end'] = $row['play_course_end'];
        // array_push($datas,$result);
        $datas->push((object)$result);
      }
      foreach($user_expect_training as $row) {
        $result = [];
        $result['employee_id'] = $row['employee_id'];
        $result['tinitial'] = $row['tinitial'];
        $result['firstname'] = $row['firstname'];
        $result['lastname'] = $row['lastname'];
        $result['workplace'] = $row['workplace'];
        $result['title'] = $row['title'];
        $result['division'] = $row['division'];
        $result['section'] = $row['section'];
        $result['department'] = $row['department'];
        $result['branch'] = $row['branch'];
        $result['company'] = $row['company'];
        $result['region'] = $row['region'];
        $result['staff_grade'] = $row['staff_grade'];
        $result['job_family'] = $row['job_family'];
        $result['play_course'] = $row['play_course'];
        $result['pretest'] = $row['pretest'];
        $result['posttest'] = $row['posttest'];
        $result['play_course_end'] = $row['play_course_end'];
        // array_push($datas,$result);
        $datas->push((object)$result);
      }
      foreach($inactive_emp as $row) {
        $result = [];
        $result['employee_id'] = $row['employee_id'];
        $result['tinitial'] = $row['tinitial'];
        $result['firstname'] = $row['tf_name'];
        $result['lastname'] = $row['tl_name'];
        $result['workplace'] = $row['workplace'];
        $result['title'] = $row['title_name'];
        $result['division'] = $row['division_name'];
        $result['section'] = $row['section_name'];
        $result['department'] = $row['dept_name'];
        $result['branch'] = $row['branch_name'];
        $result['company'] = $row['company'];
        $result['region'] = $row['region'];
        $result['staff_grade'] = $row['staff_grade'];
        $result['job_family'] = $row['job_family'];
        $result['play_course'] = 0;
        $result['pretest'] = '-';
        $result['posttest'] = '-';
        $result['play_course_end'] = 0;
        // array_push($datas,$result);
        $datas->push((object)$result);
      }

      $group_name = $datas_group->title;
      $now = Carbon::now()->format('dmY');
      $file = $group_name.'_'.$now.'.xls';
      // $path = config('app.url').'storage/excel/exports/'.$group_id_select.'/'.$file;
      
      if($platform=='excel') {
        return Excel::download(new Export_Report_member_access_by_director($group_name,$datas), Carbon::now()->timestamp.'.xlsx');
      }
    }
    
    return view('report.member_access_by_director', [
      'datas' => $datas,
      'filter_status' => $filter_status,
      'query_group' => $query_group,
      'search_year' => $search_year,
      'search_group' => $search_group,
      // 'path' => $path
    ]);
  }

  public function get_course_from_year($year) {
    $date_start  = new UTCDateTime(Carbon::create($year, 1, 1, 0, 0, 0)->startOfYear());
    $date_end  = new UTCDateTime(Carbon::create($year, 1, 1, 0, 0, 0)->endOfYear());

    $course = Training::where('created_at','>=',$date_start)->where('created_at','<=',$date_end)->where('status',1)->get();

    $data_back = [
      'status' => 200,
      'datas' => $course,
    ];
    
    return response()->json($data_back); 
  }
}
