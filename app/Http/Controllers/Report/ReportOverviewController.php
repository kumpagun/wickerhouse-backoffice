<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use Jenssegers\Agent\Agent;
use DB;
use PDF;
use CourseClass;
use FuncClass;
use Excel;
use Auth;
use Member;

// Model
use App\Models\Report_member_access;
use App\Models\Report_member_access_by_course;
use App\Models\Training;
use App\Models\Employee;
use App\Models\Course;
use App\Models\User_play_course_end;

class ReportOverviewController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->region_full = [
      'บริหารส่วนกลาง',
      'ภาคตะวันออก (RO1)',
      'ภาคตะวันออกเฉียงเหนือตอนล่าง (RO2)',
      'ภาคตะวันออกเฉียงเหนือตอนบน (RO3)',
      'ภาคเหนือตอนล่าง (RO4)',
      'ภาคเหนือตอนบน (RO5)',
      'ภาคตะวันตก (RO6)',
      'ภาคใต้ตอนบน (RO7)',
      'ภาคใต้ตอนล่าง (RO8)',
      'ภาคกลาง (RO9)',
      'กรุงเทพฯและปริมณฑล (RO10)'
    ];
    $this->region_short = [
      'บริหารส่วนกลาง',
      'RO1',
      'RO2',
      'RO3',
      'RO4',
      'RO5',
      'RO6',
      'RO7',
      'RO8',
      'RO9',
      'RO10'
    ];
  }

  public function index(Request $request) {
    $date = $request->input('date');

    if(!empty($date)) {
      $date = explode('-', $date);
      $str_date_start = str_replace(' ', '', $date[0]);
      $str_date_end = str_replace(' ', '', $date[1]);
      $calendar_date_start = Carbon::createFromFormat('d/m/Y',$str_date_start)->format('Y-m-d');
      $calendar_date_end = Carbon::createFromFormat('d/m/Y',$str_date_end)->format('Y-m-d');
      
      $date_start  = new UTCDateTime(Carbon::createFromFormat('d/m/Y',$str_date_start)->startOfDay());
      $date_end  = new UTCDateTime(Carbon::createFromFormat('d/m/Y',$str_date_end)->endOfDay());
    } else {
      $calendar_date_start = Carbon::now()->subDays(6)->format('Y-m-d');
      $calendar_date_end = Carbon::now()->format('Y-m-d');

      $date_start  = new UTCDateTime(now()->subDays(6)->startOfDay());
      $date_end  = new UTCDateTime(now()->endOfDay());
    }

    // $date_start  = new UTCDateTime(Carbon::now()->startOfDay());
    // $date_end  = new UTCDateTime(Carbon::now()->endOfDay());
    $employee_id = [];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $employee_id = Member::get_employee_id_from_head();
    }

    $arr_course = $this->get_course_in_period($date_start,$date_end);

    // ประเภทหลักสูตรทั้งหมด
    $get_course_type = $this->get_course_type($arr_course,$date_start,$date_end,$employee_id);

    // จำนวนพนักงานทั้งหมด เข้าเรียนแล้ว, ยังไม่เข้าเรียน, เรียนสำเร็จ
    $all_employee = Employee::where('status',1)->where('created_at','<=',$date_end)->count();
    $get_employee_stat = $this->get_employee_stat($arr_course,$date_start,$date_end,$employee_id);

    // Course category
    $get_course_category = $this->get_course_category($arr_course,$date_start,$date_end,$employee_id); 

    // ชื่อคอร์ส แยกตามรอบการอบรม เข้าเรียนแล้ว, ยังไม่เข้าเรียน, เรียนสำเร็จ 
    $get_course_stat = $this->get_course_stat($arr_course,$date_start,$date_end,$employee_id);

    // จำนวนผู้เข้าเรียนของบริษัทนั้นๆ
    $get_company_stat = $this->get_company_stat($arr_course,$date_start,$date_end,$employee_id);

    // จำนวนผู้เข้าเรียน แต่ละแผนก ของบริษัท 3BB
    $get_company_3bb_stat = $this->get_company_3bb_stat($arr_course,$date_start,$date_end,$employee_id);

    // Device
    $get_device = $this->get_device($arr_course,$date_start,$date_end,$employee_id);

    // หลักสูตรที่ได้รับความนิยม (หลักสูตรทั่วไป)
    $get_top5_course_general = $this->get_top5_course_general($arr_course,$date_start,$date_end,$employee_id);
    // พนักงานเข้าเรียนมากที่สุด
    $get_top5_employee = $this->get_top5_employee($arr_course,$date_start,$date_end,$employee_id);
    // แผนกที่พนักงานเข้าเรียนมากที่สุด
    $get_top5_department = $this->get_top5_department($arr_course,$date_start,$date_end,$employee_id);
    // Job family ที่พนักงานเข้าเรียนมากที่สุด
    $get_top5_job_family = $this->get_top5_job_family($arr_course,$date_start,$date_end,$employee_id);
    // ผู้เรียนที่สำเร็จหลักสูตรมากที่สุด
    $get_top5_playend_all_ep = $this->get_top5_playend_all_ep($arr_course,$date_start,$date_end,$employee_id);


    $withData = [
      'date_start' => $calendar_date_start,
      'date_end' => $calendar_date_end,
      'course_type' => $get_course_type,
      'all_employee' => $all_employee,
      'employee_stat' => $get_employee_stat,
      'course_standard_stat' => $get_course_stat['standard'],
      'course_general_stat' => $get_course_stat['general'],
      'course_category' => $get_course_category,
      'company_stat' => $get_company_stat,
      'company_3bb_stat' => $get_company_3bb_stat,
      'device' => $get_device,
      'top5_course_general' => $get_top5_course_general,
      'top5_employee' => $get_top5_employee,
      'top5_department' => $get_top5_department,
      'top5_job_family' => $get_top5_job_family,
      'top5_playend_all_ep' => $get_top5_playend_all_ep
    ];

    return view('report.dashboard_overview',$withData);
  }

  public function get_course_in_period($date_start,$date_end) {
    $courses = Course::where('status',1)
      // ->where('type','standard')
      ->where('created_at','>=',$date_start)
      ->where('created_at','<=',$date_end)
      ->get();

    $datas = [];
    foreach($courses as $row) {
      array_push($datas, new ObjectId($row->_id));
    }

    return $datas;
  }

  public function get_course_type($arr_course='',$date_start='',$date_end='') {
    $courses = Course::where('status',1)->whereIn('_id',$arr_course)->select('type')->get();
    $datas = [];
    foreach($courses as $course) {
      if(empty($datas[$course->type])) {
        $datas[$course->type] = 0;
      }
      $datas[$course->type] += 1;
    }
    $data_back['label'] = [];
    $data_back['total'] = [];
    array_push($data_back['label'],'หลักสูตรมาตรฐาน');
    if(!empty($datas['standard'])) {
      array_push($data_back['total'], [
        'value' => $datas['standard'],
        'name' => 'หลักสูตรมาตรฐาน'
      ]);
    } else {
      // array_push($data_back['total'], [
      //   'value' => 0,
      //   'name' => 'หลักสูตรมาตรฐาน'
      // ]);
    }
    array_push($data_back['label'],'หลักสูตรทั่วไป');
    if(!empty($datas['general'])) {
      array_push($data_back['total'], [
        'value' => $datas['general'],
        'name' => 'หลักสูตรทั่วไป'
      ]);
    } else {
      // array_push($data_back['total'], [
      //   'value' => 0,
      //   'name' => 'หลักสูตรทั่วไป'
      // ]);
    }
    return $data_back;
  }

  public function get_employee_stat($arr_course='',$date_start='',$date_end='',$employee_id) {
    $total_course = count($arr_course);
    // พนักงานทั้งหมด
    $query = Employee::where('status',1)->where('created_at','<=',$date_end);
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $query->whereIn('employee_id',$employee_id);
    }
    $employee = $query->count();
    // พนักงานที่เข้าเรียน
    $employee_active = 0;
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
      'play_course' => [ '$ne' => 0 ]
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "employee_id" => '$employee_id'
            ],
            'count' => ['$sum' => 1] 
          ]
        ]
      ]);
    });
    foreach($member_access_by_course as $row) {
      $employee_active++;
    }
    // พนักงานที่เข้าเรียนจบทุกคอร์ส
    $employee_success = 0;
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
      'play_course_end_all_ep' => true
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ],  
        [
          '$group' => [
            '_id' => [
              "employee_id" => '$employee_id'
            ],
            'course' => ['$addToSet' => '$course_id'] 
          ]
        ],
        [
          '$project' => [
            '_id' => 1,
            'total_course' => [
              '$size' => '$course'
            ]
          ]
        ],
        [
          '$sort' => [
            'total_course' => -1
          ]
        ]
      ]);
    });
    foreach($member_access_by_course as $row) {
      if($row->total_course==$total_course) {
        $employee_success++;
      }
    }
    // ยังไม่เข้ารียน
    $inactive = $employee - $employee_active;

    $datas = [
      'all' => $employee,
      'active' => $employee_active,
      'success' => $employee_success,
      'inactive' => $inactive
    ];

    $data_back['label'] = [];
    $data_back['total'] = [];
    if(!empty($employee_active)) {
      array_push($data_back['label'], 'เข้าเรียนแล้ว');
      array_push($data_back['total'], [
        'value' => $employee_active,
        'name' => 'เข้าเรียนแล้ว'
      ]);
    }
    if(!empty($inactive)) {
      array_push($data_back['label'], 'ยังไม่เข้าเรียน');
      array_push($data_back['total'], [
        'value' => $inactive,
        'name' => 'ยังไม่เข้าเรียน'
      ]);
    }
    if(!empty($employee_success)) {
      array_push($data_back['label'], 'เรียนสำเร็จ');
      array_push($data_back['total'], [
        'value' => $employee_success,
        'name' => 'เรียนสำเร็จ'
      ]);
    }
    
    return $data_back;
  }

  public function get_course_stat($arr_course='',$date_start='',$date_end='',$employee_id) {
    $employee = Employee::where('status',1)->count();
    $datas = [];
    // เข้าเรียนกี่คน 
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ]
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "course_id" => '$course_id'
            ],
            'user' => ['$addToSet' => '$employee_id'] 
          ]
        ],
        [
          '$project' => [
            '_id' => 1,
            'count' => [
              '$size' => '$user'
            ]
          ]
        ],
      ]);
    });
    foreach($member_access_by_course as $row) {
      $datas[(string)$row->_id['course_id']]['active'] = $row->count;
      $datas[(string)$row->_id['course_id']]['success'] = 0;
      $datas[(string)$row->_id['course_id']]['inactive'] = $employee;
    }
    // เรียนจบกี่คน
    foreach($arr_course as $course) {
      $match = [
        'created_at' => [ '$gte' => $date_start ],
        'created_at' => [ '$lte' => $date_end ],
        'course_id' => new ObjectId($course),
        'play_course_end_all_ep' => true
      ];
      if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
        $match['employee_id'] = [ '$in' => $employee_id ]; 
      }
      $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
        return $collection->aggregate([
          [
            '$match' => $match
          ], 
          [
            '$group' => [
              '_id' => [
                "course_id" => '$course_id'
              ],
              'user' => ['$addToSet' => '$employee_id'] 
            ]
          ],
          [
            '$project' => [
              '_id' => 1,
              'count' => [
                '$size' => '$user'
              ]
            ]
          ],
        ]);
      });
      foreach($member_access_by_course as $row) {
        $datas[(string)$row->_id['course_id']]['success'] = $row->count;
      }
    }

    $data_back['standard']['label'] = [];
    $data_back['standard']['inactive'] = [];
    $data_back['standard']['active'] = [];
    $data_back['standard']['success'] = [];
    $data_back['general']['label'] = [];
    $data_back['general']['inactive'] = [];
    $data_back['general']['active'] = [];
    $data_back['general']['success'] = [];
    $course_inactive = [];
    foreach($datas as $course_id => $course_data) {
      $course = Course::find($course_id); 
      $title = $course->title." (".FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($course->created_at,'d/m/Y').")";
      array_push($course_inactive, new ObjectId($course_id));

      // $inactive = $course_data['inactive'] - $course_data['active'];
      // $all = $course_data['active'] + $course_data['success'] + $inactive;
      // $active = number_format(($course_data['active'] * 100)/$all, 2, '.', '');
      // $success = number_format(($course_data['success'] * 100)/$all, 2, '.', '');
      // $inactive = number_format(($inactive * 100)/$all, 2, '.', '');

      $inactive = $course_data['inactive'] - $course_data['active'];
      $all = $course_data['active'] + $course_data['success'] + $inactive;
      $active = $course_data['active'];
      $success = $course_data['success'];
      
      if($course->type=='standard') {
        array_push($data_back['standard']['label'], $title);
        if(!empty($inactive)) {
          array_push($data_back['standard']['inactive'], $inactive);
        }
        if(!empty($active)) {
          array_push($data_back['standard']['active'], $active);
        }
        if(!empty($success)) {
          array_push($data_back['standard']['success'], $success);
        }
      } else {
        array_push($data_back['general']['label'], $title);
        if(!empty($inactive)) {
          array_push($data_back['general']['inactive'], $inactive);
        }
        if(!empty($active)) {
          array_push($data_back['general']['active'], $active);
        }
        if(!empty($success)) {
          array_push($data_back['general']['success'], $success);
        }
      }
    }

    $courses = Course::whereNotIn('_id',$course_inactive)->where('status',1)
      ->where('created_at','>=',$date_start)
      ->where('created_at','<=',$date_end)
      ->get();
    foreach($courses as $course) {
      $title = $course->title." (".FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($course->created_at,'d/m/Y').")";
      if($course->type=='standard') {
        array_push($data_back['standard']['label'], $title);
        array_push($data_back['standard']['inactive'], 100);
      } else {
        array_push($data_back['general']['label'], $title);
        array_push($data_back['general']['inactive'], 100);
      }
    }
    
    return $data_back;
  }

  public function get_course_category($arr_course='',$date_start='',$date_end='',$employee_id) {
    $courses = Course::whereIn('_id',$arr_course)->get();
    $data_back = [];
    $data_back['standard'] = [];
    $data_back['general'] = [];
    foreach($courses as $row) {
      if(empty($data_back[$row->type][(string)$row->category_id])) {
        $data_back[$row->type][(string)$row->category_id] = 0;
      }
      $data_back[$row->type][(string)$row->category_id]++;
    }
    return $data_back;
  }

  public function get_company_stat($arr_course='',$date_start='',$date_end='',$employee_id) {
    // พนักงานที่เข้าเรียน
    $employee_active = 0;
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'play_course' => [ '$gt' => 0 ],
      'company' => [ '$ne' => null ]
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "company" => '$company'
            ],
            'user' => ['$addToSet' => '$employee_id'] 
          ]
        ],  
        [
          '$project' => [
            '_id' => 1,
            'total_user' => [
              '$size' => '$user'
            ]
          ]
        ],
        [
          '$sort' => [
            '_id.company' => 1
          ]
        ]
      ]);
    });

    $data_back['label'] = [];
    $data_back['result'] = [];
    $data_back['total'] = [];
    $total_all = 0;
    foreach($member_access_by_course as $row) {
      array_push($data_back['label'],$row->_id['company']);
      array_push($data_back['result'],$row->total_user);
      $total_all += $row->total_user;
    }

    foreach($data_back['result'] as $total) {
      $result = number_format(($total * 100) / $total_all,2,'.',',');
      array_push($data_back['total'],$result);
    }

    return $data_back;
  }

  public function get_company_3bb_stat($arr_course='',$date_start='',$date_end='',$employee_id) {
    // พนักงานที่เข้าเรียน
    $employee_active = 0;
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'play_course' => [ '$gt' => 0 ],
      'company' => 'TTT BB'
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "region" => '$region'
            ],
            'user' => ['$addToSet' => '$employee_id'] 
          ]
        ],  
        [
          '$project' => [
            '_id' => 1,
            'total_user' => [
              '$size' => '$user'
            ]
          ]
        ],
        [
          '$sort' => [
            '_id.company' => 1
          ]
        ]
      ]);
    });

    $data_back['label'] = [];
    $data_back['result'] = [];
    $data_back['total'] = [];
    $total_all = 0;

    foreach($member_access_by_course as $row) {
      $index_region = array_search($row->_id['region'], $this->region_full);
      if(!$index_region) {
        array_push($data_back['label'],$this->region_short[0]);
        array_push($data_back['result'],$row->total_user);
        $total_all += $row->total_user;
      }
    }

    foreach($this->region_full as $region) {
      foreach($member_access_by_course as $row) {
        if($region==$row->_id['region']) {
          $index_region = array_search($region, $this->region_full);
          array_push($data_back['label'],$this->region_short[$index_region]);
          array_push($data_back['result'],$row->total_user);
          $total_all += $row->total_user;
        }
      }
    }

    foreach($data_back['result'] as $total) {
      $result = number_format(($total * 100) / $total_all,2,'.',',');
      array_push($data_back['total'],$result);
    }

    return $data_back;
  }

  public function get_device($arr_course='',$date_start='',$date_end='',$employee_id) {
    $user_play_course_end = User_play_course_end::where('status',1)
      ->where('created_at','>=',$date_start)
      ->where('created_at','<=',$date_end)
      ->select('uid')
      ->groupBy('uid')
      ->get();

    $agent = new Agent();
    $desktop = 0;
    $mobile = 0;
    $tablet = 0;
    $total = 0;
    foreach($user_play_course_end as $row) {
      $agent->setUserAgent($row->uid);
      if($agent->isMobile()) {
        $mobile++;
      } else if($agent->isTablet()) {
        $tablet++;
      } else {
        $desktop++;
      }
      $total++;
    }

    if($total!=0) {
      $desktop = number_format(($desktop * 100)/$total, 0, '.', '');
      $mobile = number_format(($mobile * 100)/$total, 0, '.', '');
      $tablet = number_format(($tablet * 100)/$total, 0, '.', '');
    }

    $data_back = [
      'desktop' => $desktop,
      'mobile' => $mobile,
      'tablet' => $tablet,
    ];

    return $data_back;
  }

  public function get_top5_course_general($arr_course='',$date_start='',$date_end='',$employee_id) {
    $courses = Course::whereIn('_id',$arr_course)
    ->where('type','general')
    ->get();

    $course_id = [];
    foreach($courses as $row) {
      array_push($course_id, new ObjectId($row->_id));
    }

    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $course_id ],
    ];
    // if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
    //   $match['employee_id'] = [ '$in' => $employee_id ]; 
    // }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "course_id" => '$course_id'
            ],
            'count' => ['$sum' => 1] 
          ]
        ],
        [
          '$sort' => [
            'count' => -1
          ]
        ]
      ]);
    });
    
    $data_back['label'] = [];
    $data_back['total'] = [];
    $loop = 1;
    foreach($member_access_by_course as $row) {
      if($loop <= 5) {
        array_push($data_back['label'], mb_substr(CourseClass::get_name_course($row->_id['course_id']), 0, 20, 'UTF-8'));
        array_push($data_back['total'], $row->count);
      }
      $loop++;
    }
    return $data_back;
  }
  public function get_top5_employee($arr_course='',$date_start='',$date_end='',$employee_id) {
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "employee_id" => '$employee_id'
            ],
            'total_user' => ['$sum' => 1] 
          ]
        ],
        [
          '$sort' => [
            'total_user' => -1
          ]
        ]
      ]);
    });
    
    $data_back['label'] = [];
    $data_back['total'] = [];
    $loop = 1;
    foreach($member_access_by_course as $row) {
      if($loop <= 5) {
        array_push($data_back['label'], Member::get_name_from_employee_id($row->_id['employee_id']));
        array_push($data_back['total'], $row->total_user);
      }
      $loop++;
    }
    return $data_back;
  }
  public function get_top5_department($arr_course='',$date_start='',$date_end='',$employee_id) {
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "department" => '$department'
            ],
            'user' => ['$addToSet' => '$employee_id'] 
          ]
        ],  
        [
          '$project' => [
            '_id' => 1,
            'total_user' => [
              '$size' => '$user'
            ]
          ]
        ],
        [
          '$sort' => [
            'total_user' => -1
          ]
        ]
      ]);
    });
    
    $data_back['label'] = [];
    $data_back['total'] = [];
    $loop = 1;

    foreach($member_access_by_course as $row) {
      $department = "อื่นๆ";
      if($row->_id['department']!='') {
        $index_region = array_search($row->_id['department'], $this->region_full); 
        if(!empty($index_region)) {
          $department = $this->region_short[$index_region];
        } else {
          $department = $row->_id['department'];
        }
      }
      if($loop <= 5) {
        array_push($data_back['label'],$department);
        array_push($data_back['total'], $row->total_user);
      }
      $loop++;
    }
    return $data_back;
  }
  public function get_top5_job_family($arr_course='',$date_start='',$date_end='',$employee_id) {
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "job_family" => '$job_family'
            ],
            'user' => ['$addToSet' => '$employee_id'] 
          ]
        ],  
        [
          '$project' => [
            '_id' => 1,
            'total_user' => [
              '$size' => '$user'
            ]
          ]
        ],
        [
          '$sort' => [
            'total_user' => -1
          ]
        ]
      ]);
    });
    
    $data_back['label'] = [];
    $data_back['total'] = [];
    $loop = 1;
    foreach($member_access_by_course as $row) {
      if($loop <= 5) {
        array_push($data_back['label'],$row->_id['job_family']);
        array_push($data_back['total'], $row->total_user);
      }
      $loop++;
    }
    return $data_back;
  }
  public function get_top5_playend_all_ep($arr_course='',$date_start='',$date_end='',$employee_id) {
    $match = [
      'created_at' => [ '$gte' => $date_start ],
      'created_at' => [ '$lte' => $date_end ],
      'course_id' => [ '$in' => $arr_course ],
      'play_course_end_all_ep' => true
    ];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match['employee_id'] = [ '$in' => $employee_id ]; 
    }
    $member_access_by_course = Report_member_access_by_course::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [
          '$match' => $match
        ], 
        [
          '$group' => [
            '_id' => [
              "employee_id" => '$employee_id'
            ],
            'course' => ['$addToSet' => '$course_id'] 
          ]
        ],
        [
          '$project' => [
            '_id' => 1,
            'total_course' => [
              '$size' => '$course'
            ]
          ]
        ],
        [
          '$sort' => [
            'total_course' => -1
          ]
        ]
      ]);
    });
    
    $data_back['label'] = [];
    $data_back['total'] = [];
    $loop = 1;
    foreach($member_access_by_course as $row) {
      if($loop <= 5) {
        array_push($data_back['label'],Member::get_name_from_employee_id($row->_id['employee_id']));
        array_push($data_back['total'], $row->total_course);
      }
      $loop++;
    }
    return $data_back;
  }
  public function get_top5_teacher($arr_course='',$date_start='',$date_end='',$employee_id) {

  }
}