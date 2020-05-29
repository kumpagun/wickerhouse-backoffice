<?php

namespace App\Http\Controllers\Crontab;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use DB;
// Model
use App\Models\Member;
use App\Models\Member_jasmine;
use App\Models\Employee;
use App\Models\Report_member_access;
use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\Examination_user;
use App\Models\User_play_course_log;
use App\Models\User_play_course_end;

class ReportController extends Controller
{
  // CRONTAB 192.168.30.16
  public function access_content_by_user (Request $request)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $date_now = Carbon::now();
    $date = new UTCDateTime($date_now->startOfDay());

    $date_start  = new UTCDateTime(Carbon::now()->addDays(1)->startOfDay());
    $date_end  = new UTCDateTime(Carbon::now()->subDays(1)->endOfDay());
    // $query = Training::where('status',1)->where('_id',new ObjectId('5e85f50e042105442c52a970'));
    $query = Training::where('status',1);
    $query->where('published_at','<=',$date_start);
    $query->where('expired_at','>=',$date_end);
    // $query->where('_id',new ObjectId("5e85f50e042105442c52a970"));
    $trainings = $query->get(); 
    if(!empty($trainings)) {
      foreach($trainings as $row) {
        Report_member_access::where('created_at', '<', $date)->where('training_id', new ObjectId($row->_id))->where('status', 1)->update(['status' => 0]);
        Report_member_access::where('created_at', '>=', $date)->where('training_id', new ObjectId($row->_id))->where('status',1)->delete();
        $this->get_data($row);
      }
    }
  }

  public function get_data($input_datas) {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $datas = [];
    $training_id = new ObjectId($input_datas->_id);
    $course_id = new ObjectId($input_datas->course_id);
    $user_test = [
      new ObjectId("5de5e565bc48d45e27e5f349")
    ];

    // ผู้เรียนทั้งหมด
    $training_user = TrainingUser::where('status',1)->where('training_id', $training_id)->get();
    $arr_employee_id = [];
    foreach($training_user as $row) {
      array_push($arr_employee_id, $row->employee_id);
    }
    
    // Members login
    $members = Member::raw(function ($collection) use ($arr_employee_id, $user_test) {
      return $collection->aggregate([
        [
          '$lookup' => [
            'from' =>  "employees",
            'localField' =>  "employee_id",
            'foreignField' =>  "employee_id",
            'as' =>  "employees"
          ]
        ],
        [
          '$match' => [
            'active' => 1,
            'employee_id' => [ '$in' => $arr_employee_id ],
          ]
        ],
        [
          '$unwind' => '$employees'
        ]
      ]);
    });

    // user_id members
    $memberId_jas = [];
    $jas_in_members_table = [];
    foreach($members as $row) {
      array_push($memberId_jas, new ObjectId($row->_id));
      array_push($jas_in_members_table, $row['employee_id']);
    }
    // Member not login
    $members_jasmine = Employee::whereIn('employee_id',$arr_employee_id)->whereNotIn('employee_id',$jas_in_members_table)->get(); 

    // หาผู้เรียนที่มาจากการ Import excel และยังไม่เข้าเรียน
    $arr_members_jasmine = [];
    foreach($members_jasmine as $row) {
      array_push($arr_members_jasmine, $row->employee_id);
    }
    $member_import_excel = Member_jasmine::whereIn('employee_id',$arr_employee_id)->whereNotIn('employee_id',$arr_members_jasmine)->whereNotIn('employee_id',$jas_in_members_table)->get(); 
    // หาผู้เรียนที่มาจากการ Import excel และยังไม่เข้าเรียน

    // Pretest
    $pretests = Examination_user::select('user_id','point')->where('course_id',$course_id)->where('training_id',$training_id)->whereIn('user_id',$memberId_jas)->where('type','pretest')->where('status',1)->groupBy('user_id','point')->get();
    // Posttest
    $posttests = Examination_user::raw(function ($collection) use ($memberId_jas, $course_id, $training_id, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'type' => 'posttest',
            'user_id' => [ '$in' => $memberId_jas ],
            'course_id' => $course_id,
            'training_id' => $training_id,
            'status' => 1
          ]
        ],
        [
          '$group' => 
            [
              '_id' => [
                "user_id" => '$user_id'
              ],
              'maxTotalAmount' => [
                '$max' => '$point'
              ]
            ]
        ]
      ]);
    });
    
    // Play course
    $play_courses = User_play_course_log::raw(function ($collection) use ($memberId_jas, $course_id, $training_id, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'user_id' => [ '$in' => $memberId_jas ],
            'course_id' => $course_id,
            'training_id' => $training_id
          ]
        ], [
          '$group' => [
            '_id' => [
              "user_id" => '$user_id'
            ],
            'ep' => ['$addToSet' => '$episode_id'] 
          ]
        ],  [
          '$project' => [
            '_id' => 1,
            'complete_ep' => [
              '$size' => '$ep'
            ]
          ]
        ]
      ]);
    });

    // Play course end
    $play_courses_ends = User_play_course_end::raw(function ($collection) use ($memberId_jas, $course_id, $training_id, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'user_id' => [ '$in' => $memberId_jas ],
            'course_id' => $course_id,
            'training_id' => $training_id
          ]
        ], [
          '$group' => [
            '_id' => [
              "user_id" => '$user_id'
            ],
            'ep' => ['$addToSet' => '$episode_id'] 
          ]
        ],  [
          '$project' => [
            '_id' => 1,
            'complete_ep' => [
              '$size' => '$ep'
            ]
          ]
        ]
      ]);
    });
    // Member ที่ login เข้าระบบแล่้ว 
    foreach($members as $value) {
      $values_id = (string)$value->_id;
      $datas[$values_id]['_id'] = $values_id;
      $datas[$values_id]['employee_id'] = $value->employees->employee_id;
      if(!empty($value->employees->tinitial)) { $datas[$values_id]['tinitial'] = $value->employees->tinitial; } else { $datas[$values_id]['tinitial'] = ''; }
      if(!empty($value->employees->tf_name)) { $datas[$values_id]['firstname'] = $value->employees->tf_name; } else { $datas[$values_id]['firstname'] = ''; }
      if(!empty($value->employees->tl_name)) { $datas[$values_id]['lastname'] = $value->employees->tl_name; } else { $datas[$values_id]['lastname'] = ''; }
      if(!empty($value->employees->workplace)) { $datas[$values_id]['workplace'] = $value->employees->workplace; } else { $datas[$values_id]['workplace'] = ''; }
      if(!empty($value->employees->title_name)) { $datas[$values_id]['title'] = $value->employees->title_name; } else { $datas[$values_id]['title'] = ''; }
      if(!empty($value->employees->company)) { $datas[$values_id]['company'] = $value->employees->company; } else { $datas[$values_id]['company'] = ''; }
      if(!empty($value->employees->division_name)) { $datas[$values_id]['division'] = $value->employees->division_name; } else { $datas[$values_id]['division'] = ''; }
      if(!empty($value->employees->section_name)) { $datas[$values_id]['section'] = $value->employees->section_name; } else { $datas[$values_id]['section'] = ''; }
      if(!empty($value->employees->dept_name)) { $datas[$values_id]['department'] = $value->employees->dept_name; } else { $datas[$values_id]['department'] = ''; }
      if(!empty($value->employees->branch_name)) { $datas[$values_id]['branch'] = $value->employees->branch_name; } else { $datas[$values_id]['branch'] = ''; }
      if(!empty($value->employees->region)) { $datas[$values_id]['region'] = $value->employees->region; } else { $datas[$values_id]['region'] = ''; }
      if(!empty($value->employees->staff_grade)) { $datas[$values_id]['staff_grade'] = $value->employees->staff_grade; } else { $datas[$values_id]['staff_grade'] = ''; }
      if(!empty($value->employees->job_family)) { $datas[$values_id]['job_family'] = $value->employees->job_family; } else { $datas[$values_id]['job_family'] = ''; }
    }
    // Member ที่ยังไม่ login เข้าระบบ
    foreach($members_jasmine as $value) {
      $datas[$value->_id]['employee_id'] = $value->employee_id;
      $datas[$value->_id]['tinitial'] = $value->tinitial;
      $datas[$value->_id]['firstname'] = $value->tf_name;
      $datas[$value->_id]['lastname'] = $value->tl_name;
      $datas[$value->_id]['workplace'] = $value->workplace;
      $datas[$value->_id]['title'] = $value->title_name;
      $datas[$value->_id]['company'] = $value->company;
      $datas[$value->_id]['division'] = $value->division_name;
      $datas[$value->_id]['section'] = $value->section_name;
      $datas[$value->_id]['department'] = $value->dept_name;
      $datas[$value->_id]['branch'] = $value->branch_name;
      $datas[$value->_id]['region'] = $value->region;
      $datas[$value->_id]['staff_grade'] = $value->staff_grade;
      $datas[$value->_id]['job_family'] = $value->job_family;
    }
    // Member ที่ยังไม่ login เข้าระบบ จากการ IMPORT EXCEL
    foreach($member_import_excel as $value) {
      $datas[$value->_id]['employee_id'] = $value->employee_id;
      $datas[$value->_id]['tinitial'] = $value->tinitial;
      $datas[$value->_id]['firstname'] = $value->firstname;
      $datas[$value->_id]['lastname'] = $value->lastname;
      $datas[$value->_id]['workplace'] = $value->workplace;
      $datas[$value->_id]['title'] = $value->title;
      $datas[$value->_id]['company'] = $value->company;
      $datas[$value->_id]['division'] = $value->division;
      $datas[$value->_id]['section'] = $value->section;
      $datas[$value->_id]['department'] = $value->department;
      $datas[$value->_id]['branch'] = $value->branch;
      $datas[$value->_id]['region'] = $value->region;
      $datas[$value->_id]['staff_grade'] = $value->staff_grade;
      $datas[$value->_id]['job_family'] = $value->job_family;
    }
    
    // Play course
    foreach($play_courses as $play_course) {
      if(!empty($datas[(string)$play_course->_id['user_id']])) {
        $datas[(string)$play_course->_id['user_id']]['play_course'] = $play_course->complete_ep;
      }
    }
    
    // Play course end
    foreach($play_courses_ends as $play_courses_end) {
      if(!empty($datas[(string)$play_courses_end->_id['user_id']])) {
        $datas[(string)$play_courses_end->_id['user_id']]['play_course_end'] = $play_courses_end->complete_ep;
      }
    }

    // Pretest
    foreach($pretests as $pretest) {
      if(!empty($datas[(string)$pretest->user_id])) {
        $datas[(string)$pretest->user_id]['pretest'] = $pretest->point;
      }
    }

    // Posttest
    foreach($posttests as $posttest) {
      if(!empty($datas[(string)$posttest->_id['user_id']])) {
        $datas[(string)$posttest->_id['user_id']]['posttest'] = $posttest->maxTotalAmount;
      }
    }
    $this->formatData($training_id, $course_id, $datas);
  }

  public function formatData($training_id, $course_id, $datas) {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $dataArray = [];
    $now = new UTCDateTime(Carbon::now()->timestamp * 1000);
    foreach ($datas as $data) {
      $employee_id = '';
      $tinitial = '';
      $firstname = '';
      $lastname = '';
      $workplace = '';
      $title = '';
      $company = '';
      $division = '';
      $section = '';
      $department = '';
      $branch = '';
      $region = '';
      $staff_grade = '';
      $job_family = '';
      $play_course = 0;
      $play_course_end = 0;
      $pretest = NULL;
      $posttest = NULL;
      if(!empty($data['employee_id'])) { $employee_id = $data['employee_id']; } else { $employee_id = ''; }
      if(!empty($data['tinitial'])) { $tinitial = $data['tinitial']; } else { $tinitial = ''; } 
      if(!empty($data['firstname'])) { $firstname = $data['firstname']; } else { $firstname = ''; } 
      if(!empty($data['lastname'])) { $lastname = $data['lastname']; } else { $lastname = ''; } 
      if(!empty($data['title'])) { $title = $data['title']; } else { $title = ''; } 
      if(!empty($data['workplace'])) { $workplace = $data['workplace']; } else { $workplace = ''; } 
      if(!empty($data['company'])) { $company = $data['company']; } else { $company = ''; } 
      if(!empty($data['division'])) { $division = $data['division']; } else { $division = ''; } 
      if(!empty($data['section'])) { $section = $data['section']; } else { $section = ''; } 
      if(!empty($data['department'])) { $department = $data['department']; } else { $department = ''; } 
      if(!empty($data['branch'])) { $branch = $data['branch']; } else  { $branch = ''; } 
      if(!empty($data['region'])) { $region = $data['region']; } else  { $region = ''; } 
      if(!empty($data['staff_grade'])) { $staff_grade = $data['staff_grade']; } else { $staff_grade = ''; } 
      if(!empty($data['job_family'])) { $job_family = $data['job_family']; } else { $job_family = ''; } 
      if(!empty($data['play_course'])) { $play_course = $data['play_course']; } else { $play_course = 0; } 
      if(!empty($data['play_course_end'])) { $play_course_end = $data['play_course_end']; } else { $play_course_end = 0; } 
      if(isset($data['pretest'])) { $pretest = $data['pretest']; } else { $pretest = NULL; }
      if(isset($data['posttest'])) { $posttest = $data['posttest']; } else { $posttest = NULL; }
      if(!empty($employee_id)) {
        $dataArray[] = [
          'training_id' => $training_id,
          'course_id' => $course_id,
          'employee_id' => $employee_id,
          'tinitial' => $tinitial,
          'firstname' => $firstname,
          'lastname' => $lastname,
          'workplace' => $workplace,
          'title' => $title,
          'company' => $company,
          'division' => $division,
          'section' => $section,
          'department' => $department,
          'branch' => $branch,
          'region' => $region,
          'staff_grade' => $staff_grade,
          'job_family' => $job_family,
          'play_course' => $play_course,
          'play_course_end' => $play_course_end,
          'pretest' => $pretest,
          'posttest' => $posttest,
          'status' => 1,
          'created_at' => $now,
          'updated_at' => $now
        ];
      }
    }
    $this->insertDataArray($dataArray);
  }

  public function insertDataArray($data) {
		// ini_set("memory_limit","10M");
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
		$start = 0;
		$length = 100;

		$limit = count($data);
		while ($start < $limit) {
			$data_slice = array_slice ( $data, $start, $length );
			// dd($data_slice);
			DB::table('report_member_accesses')->insert($data_slice);
			$start += $length;
		}
		return array('status' => 'success');
  }

  public function update_branch() {
    $query = Report_member_access::whereNull('region');
    $query->select('employee_id');
    $query->groupBy('employee_id')->limit(10000);
    $datas = $query->get();

    foreach($datas as $data) {
      $employee = Employee::where('employee_id',$data->employee_id)->first(); dd($employee);
      if(!empty($employee->region)) {
        Report_member_access::where('employee_id',$data->employee_id)->update(['region' => $employee->region]);
      }
    }
  }
}
