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
use App\Models\Employee;
use App\Models\Report_member_access;
use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\Examination_answer;
use App\Models\User_play_course_log;
use App\Models\User_play_course_end;

class ReportController extends Controller
{
  // CRONTAB 192.168.30.16
  public function access_content_by_user (Request $request)
  {
    $date_now = Carbon::now();
    $date = new UTCDateTime($date_now->startOfDay());

    $date_start  = new UTCDateTime(Carbon::now()->addDays(1)->startOfDay());
    $date_end  = new UTCDateTime(Carbon::now()->subDays(1)->endOfDay());
    $query = Training::where('status',1);
    $query->where('published_at','<=',$date_start);
    $query->where('expired_at','>=',$date_end);
    $trainings = $query->get();
    if(!empty($trainings)) {
      Report_member_access::where('created_at', '<', $date)->where('status', 1)->update(['status' => 0]);
      Report_member_access::where('created_at', '>=', $date)->where('status',1)->delete();
      foreach($trainings as $row) {
        $this->get_data($row);
      }
    }
  }

  public function get_data($input_datas) {
    $datas = [];
    $training_id = new ObjectId($input_datas->_id);
    $course_id = new ObjectId($input_datas->course_id);
    $user_test = [
      new ObjectId("5de5e565bc48d45e27e5f349")
    ];

    // ผู้เรียนทั้งหมด
    $members_jas = TrainingUser::where('training_id', $training_id)->where('status',1)->get();
    $members_jas_employee_id = [];
    foreach($members_jas as $row) {
      array_push($members_jas_employee_id, $row->employee_id);
    }

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
    // Pretest
    $pretests = Examination_answer::select('user_id','point')->where('course_id',$course_id)->where('training_id',$training_id)->whereIn('user_id',$memberId_jas)->where('answertype','pretest')->groupBy('user_id','point')->get();
    // Posttest
    $posttests = Examination_answer::raw(function ($collection) use ($memberId_jas, $course_id, $training_id, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'answertype' => 'posttest',
            'user_id' => [ '$in' => $memberId_jas ],
            'user_id' => ['$nin' => $user_test],
            'course_id' => $course_id,
            'training_id' => $training_id
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
            'user_id' => ['$nin' => $user_test],
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
            'user_id'       => ['$nin' => $user_test],
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
    foreach($members as $member) {
      foreach($member->employees as $index => $value) {
        $datas[$member->_id][$index] = $value;
      }
    }
    // Member ที่ยังไม่ login เข้าระบบ
    foreach($members_jasmine as $value) {
      $datas[$value->_id]['employee_id'] = $value->employee_id;
      $datas[$value->_id]['tinitial'] = $value->tinitial;
      $datas[$value->_id]['firstname'] = $value->tf_name;
      $datas[$value->_id]['lastname'] = $value->tl_name;
      $datas[$value->_id]['workplace'] = $value->workplace;
      $datas[$value->_id]['title'] = $value->title_name;
      $datas[$value->_id]['division'] = $value->division_name;
      $datas[$value->_id]['section'] = $value->section_name;
      $datas[$value->_id]['department'] = $value->dept_name;
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
    $dataArray = [];
    $now = new UTCDateTime(Carbon::now()->timestamp * 1000);
    foreach ($datas as $data) {
      $employee_id = '';
      $tinitial = '';
      $firstname = '';
      $lastname = '';
      $workplace = '';
      $title = '';
      $division = '';
      $section = '';
      $department = '';
      $staff_grade = '';
      $job_family = '';
      $play_course = 0;
      $play_course_end = 0;
      $pretest = NULL;
      $posttest = NULL;
      if(!empty($data['employee_id'])) { $employee_id = $data['employee_id']; } 
      if(!empty($data['tinitial'])) { $tinitial = $data['tinitial']; } 
      if(!empty($data['firstname'])) { $firstname = $data['firstname']; } 
      if(!empty($data['lastname'])) { $lastname = $data['lastname']; } 
      if(!empty($data['title'])) { $title = $data['title']; } 
      if(!empty($data['workplace'])) { $workplace = $data['workplace']; } 
      if(!empty($data['division'])) { $division = $data['division']; } 
      if(!empty($data['section'])) { $section = $data['section']; } 
      if(!empty($data['department'])) { $department = $data['department']; } 
      if(!empty($data['staff_grade'])) { $staff_grade = $data['staff_grade']; } 
      if(!empty($data['job_family'])) { $job_family = $data['job_family']; } 
      if(!empty($data['play_course'])) { $play_course = $data['play_course']; } 
      if(!empty($data['play_course_end'])) { $play_course_end = $data['play_course_end']; } 
      if(!empty($data['pretest'])) { $pretest = $data['pretest']; } 
      if(!empty($data['posttest'])) { $posttest = $data['posttest']; }
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
          'division' => $division,
          'section' => $section,
          'department' => $department,
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
}