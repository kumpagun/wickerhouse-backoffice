<?php

namespace App\Http\Controllers\Crontab;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
// Model
use App\Models\Member;
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

    Report_member_access::where('created_at', '<', $date)->where('status', 1)->update(['status' => 0]);
    Report_member_access::where('created_at', '>=', $date)->where('status',1)->delete();

    // $training_groups = GroupOnline::where('status',1)->where('_id',new ObjectId('5df35869ff101537d0780ce8'))->get();
    $trainings = Training::where('status',1)->get();
    foreach($trainings as $row) {
      $this->get_data($row);
    }
  }

  public function get_data($input_datas) {
    $datas = [];
    $training_id = new ObjectId($input_datas->_id);
    $courseId = new ObjectId($input_datas->course_id);
    $user_test = [
      new ObjectId("5de5e565bc48d45e27e5f349")
    ];

    // ผู้เรียนทั้งหมด
    $members_jas = TrainingUser::where('training_id', $training_id)->where('status',1)->get();
    $members_jas_employee_id = [];
    foreach($members_jas as $row) {
      array_push($members_jas_employee_id, $row->employee_id);
    }

    // Members provider jasmine
    $members = Member::raw(function ($collection) use ($training_id, $user_test) {
      return $collection->aggregate([
        [
          '$lookup' => [
            'from' =>  "training_users",
            'localField' =>  "employee_id",
            'foreignField' =>  "employee_id",
            'as' =>  "training_users"
          ]
        ],
        [
          '$match' => [
            'active'        => 1,
            '_id'       => [ '$nin' => $user_test ],
            'training_users.training_id' => $training_id,
            'training_users.status' => 1
          ]
        ],
        [
          '$unwind' => '$training_users'
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
    // Member jasmine not login
    $members_jasmine = TrainingUser::where('status',1)->where('training_id', $training_id)->whereNotIn('employee_id',$jas_in_members_table)->get();
    // Play course
    $play_courses = User_play_course_log::select('user_id')->where('courseId',$courseId)->whereIn('user_id',$memberId_jas)->groupBy('user_id')->get();
    // Pretest
    $pretests = Examination_answer::select('userId','point')->where('courseId',$courseId)->whereIn('userId',$memberId_jas)->where('answertype','pretest')->groupBy('userId','point')->get();
    // Posttest
    $posttests = Examination_answer::raw(function ($collection) use ($memberId_jas, $courseId, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'answertype' => 'posttest',
            'userId' => [ '$in' => $memberId_jas ],
            'userId' => ['$nin' => $user_test],
            'courseId' => $courseId
          ]
        ],
        [
          '$group' => 
            [
              '_id' => [
                "userId" => '$userId'
              ],
              'maxTotalAmount' => [
                '$max' => '$point'
              ]
            ]
        ]
      ]);
    });
    // Play course
    $play_courses = User_play_course_log::raw(function ($collection) use ($memberId_jas, $courseId, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'user_id' => [ '$in' => $memberId_jas ],
            'user_id'       => ['$nin' => $user_test],
            'courseId' => $courseId
          ]
        ], [
          '$group' => [
            '_id' => [
              "user_id" => '$user_id'
            ],
            'ep' => ['$addToSet' => '$episodeId'] 
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
    $play_courses_ends = User_play_course_end::raw(function ($collection) use ($memberId_jas, $courseId, $user_test) {
      return $collection->aggregate([
        [
          '$match' => [
            'user_id' => [ '$in' => $memberId_jas ],
            'user_id'       => ['$nin' => $user_test],
            'courseId' => $courseId
          ]
        ], [
          '$group' => [
            '_id' => [
              "user_id" => '$user_id'
            ],
            'ep' => ['$addToSet' => '$episodeId'] 
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
    
    foreach($members as $member) {
      $datas[$member->_id]['tinitial'] = '';
      $datas[$member->_id]['tf_name'] = '';
      $datas[$member->_id]['tl_name'] = '';
      $datas[$member->_id]['workplace'] = '';
      $datas[$member->_id]['title_name'] = '';
      $datas[$member->_id]['division_name'] = '';
      $datas[$member->_id]['section_name'] = '';
      $datas[$member->_id]['dept_name'] = '';
      $datas[$member->_id]['staff_grade'] = '';
      $datas[$member->_id]['job_family'] = '';
    }
    // Member ที่ login เข้าระบบแล่้ว
    foreach($members as $member) {
      foreach($member->member_jasmines as $index => $value) {
        $datas[$member->_id][$index] = $value;
      }
    }
    // Member ที่ยังไม่ login เข้าระบบ
    foreach($members_jasmine as $value) {
      $datas[$value->_id]['employee_id'] = $value->employee_id;
      $datas[$value->_id]['tinitial'] = $value->tinitial;
      $datas[$value->_id]['tf_name'] = $value->tf_name;
      $datas[$value->_id]['tl_name'] = $value->tl_name;
      $datas[$value->_id]['workplace'] = $value->workplace;
      $datas[$value->_id]['title_name'] = $value->title_name;
      $datas[$value->_id]['division_name'] = $value->division_name;
      $datas[$value->_id]['section_name'] = $value->section_name;
      $datas[$value->_id]['dept_name'] = $value->dept_name;
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
      if(!empty($datas[(string)$pretest->userId])) {
        $datas[(string)$pretest->userId]['pretest'] = $pretest->point;
      }
    }

    // Posttest
    foreach($posttests as $posttest) {
      if(!empty($datas[(string)$posttest->_id['userId']])) {
        $datas[(string)$posttest->_id['userId']]['posttest'] = $posttest->maxTotalAmount;
      }
    }

    $this->formatData($training_group, $courseId, $datas);
  }

  public function formatData($training_group, $courseId, $datas) {
    $dataArray = [];
    $now = new UTCDateTime(Carbon::now()->timestamp * 1000);
    foreach ($datas as $data) {
      $employee_id = '';
      $tinitial = '';
      $tf_name = '';
      $tl_name = '';
      $workplace = '';
      $title_name = '';
      $division_name = '';
      $section_name = '';
      $dept_name = '';
      $staff_grade = '';
      $job_family = '';
      $play_course = 0;
      $play_course_end = 0;
      $pretest = NULL;
      $posttest = NULL;
      if(!empty($data['employee_id'])) { $employee_id = $data['employee_id']; } 
      if(!empty($data['tinitial'])) { $tinitial = $data['tinitial']; } 
      if(!empty($data['tf_name'])) { $tf_name = $data['tf_name']; } 
      if(!empty($data['tl_name'])) { $tl_name = $data['tl_name']; } 
      if(!empty($data['title_name'])) { $title_name = $data['title_name']; } 
      if(!empty($data['workplace'])) { $workplace = $data['workplace']; } 
      if(!empty($data['division_name'])) { $division_name = $data['division_name']; } 
      if(!empty($data['section_name'])) { $section_name = $data['section_name']; } 
      if(!empty($data['dept_name'])) { $dept_name = $data['dept_name']; } 
      if(!empty($data['staff_grade'])) { $staff_grade = $data['staff_grade']; } 
      if(!empty($data['job_family'])) { $job_family = $data['job_family']; } 
      if(!empty($data['play_course'])) { $play_course = $data['play_course']; } 
      if(!empty($data['play_course_end'])) { $play_course_end = $data['play_course_end']; } 
      if(!empty($data['pretest'])) { $pretest = $data['pretest']; } 
      if(!empty($data['posttest'])) { $posttest = $data['posttest']; }
      $dataArray[] = [
        'online_group_id' => $training_group,
        'courseId' => $courseId,
        'employee_id' => $employee_id,
        'tinitial' => $tinitial,
        'tf_name' => $tf_name,
        'tl_name' => $tl_name,
        'workplace' => $workplace,
        'title_name' => $title_name,
        'division_name' => $division_name,
        'section_name' => $section_name,
        'dept_name' => $dept_name,
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