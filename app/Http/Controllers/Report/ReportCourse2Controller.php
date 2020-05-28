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

class ReportCourse2Controller extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function get_region_from_training_id(Request $request) {
    $training_id = new ObjectId($request->input('training_id'));
    $regions = Report_member_access::where('training_id',$training_id)->whereNotNull('region')->where('region','<>','')->select('region')->groupBy('region')->orderBy('region','asc')->get();
    $arr_region = [];
    foreach($regions as $region) {
      array_push($arr_region,$region->region);
    }
    return $arr_region;
  }

  // ยอดคนเข้าดู ep 
  public function index(Request $request){
    $search_group = $request->input('search_group'); 
    $search_region = $request->input('search_region');

    if(!empty($search_region)) {
      $search_region = array_filter($search_region);
      $result = [];
      foreach($search_region as $row) {
        array_push($result,$row);
      }
      $search_region = $result;
    } else {
      $search_region = [];
    }

    $platform = $request->input('platform'); 
    $employee_id = [];
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $employee_id = Member::get_employee_id_from_head();
    }

    $query_group = Training::query()->where('status',1)->where('total_employee','>',0)->orderBy('created_at','desc')->get();
    if(empty($search_group)){
      $query = Training::query()->where('status',1)->where('total_employee','>',0)->orderBy('created_at','desc')->first();
    } else {
      $query = Training::find($search_group);
    }
    if(!empty($query)) {
      $training_id_select = (string)$query->_id;
    }

    // แผนก
    $regions = Report_member_access::where('training_id',new ObjectId($training_id_select))->whereNotNull('region')->where('region','<>','')->select('region')->groupBy('region')->orderBy('region','asc')->get();
    $arr_region = [];
    foreach($regions as $region) {
      if($region!='') {
        array_push($arr_region,$region->region);
      }
    }

    $training_title = '';
    $ep = '';
    $passing_score = '';
    if(!empty($query)) {
      $training_title = $query->title;
      $ep = CourseClass::count_ep_by_course($query->course_id);
      $passing_score = CourseClass::get_course_passing_score($query->course_id); 
    }
    if(!empty($training_id_select)) {
      $training_id = new ObjectId($training_id_select);
      $datas_inactive = $this->user_inactive($training_id, $employee_id, $search_region);
      $datas_active = $this->user_active($training_id, $employee_id, $search_region);
      $datas_active_passing_score = $this->user_active_passing_score($training_id,$passing_score, $employee_id, $search_region);
      $datas_active_not_passing_score = $this->user_active_not_passing_score($training_id,$passing_score, $employee_id, $search_region);
      $total_user_inactive = 0;
      $total_user_active = 0;
      $total_user_active_passing_score = 0;
      $total_user_active_not_passing_score = 0;
      $total_user_view_not_full_ep = 0;
      $total_user_view_full_ep = 0;
      $datas = [];
      $data_insert = [];

      foreach($datas_active as $row) {
        $datas[$row->_id->department]['user_active'] = $row->total;
        $total_user_active += $row->total;
      }
      foreach($datas_active_passing_score as $row) {
        $datas[$row->_id->department]['user_active_passing_score'] = $row->total;
        $total_user_active_passing_score += $row->total;
      }
      foreach($datas_active_not_passing_score as $row) {
        $datas[$row->_id->department]['user_active_not_passing_score'] = $row->total;
        $total_user_active_not_passing_score += $row->total;
      }
      foreach($datas_inactive as $row) {
        $datas[$row->_id->department]['user_inactive'] = $row->total;
        $total_user_inactive += $row->total;
      }

      // เรียง Data  ใหม่ เรียงตาม RO1 - RO10
      $new_datas = [];
      foreach($datas as $key => $values) {
        if(!empty($datas[$key]['user_active'])) {
          $new_datas[$key]['user_active'] = $datas[$key]['user_active'];
        } else {
          $new_datas[$key]['user_active'] = 0;
        }
        if(!empty($datas[$key]['user_active_passing_score'])) {
          $new_datas[$key]['user_active_passing_score'] = $datas[$key]['user_active_passing_score'];
        } else {
          $new_datas[$key]['user_active_passing_score'] = 0;
        }
        if(!empty($datas[$key]['user_active_not_passing_score'])) {
          $new_datas[$key]['user_active_not_passing_score'] = $datas[$key]['user_active_not_passing_score'];
        } else {
          $new_datas[$key]['user_active_not_passing_score'] = 0;
        }
        if(!empty($datas[$key]['user_inactive'])) {
          $new_datas[$key]['user_inactive'] = $datas[$key]['user_inactive'];
        } else {
          $new_datas[$key]['user_inactive'] = 0;
        }
      }

      // CHART เข้าเรียน / ผ่าน / ไม่ผ่าน
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
      $chart_active['total'] = 0;
      foreach($new_datas as $index => $values) {
        if(empty($index)) {
          $index = 'อื่นๆ';
        }
        array_push($chart_active['label'], $index);
        if(!empty($values['user_inactive'])) {
          array_push($chart_active['inactive'], $values['user_inactive']);
        }
        if(!empty($values['user_active_passing_score'])) {
          array_push($chart_active['pass'], $values['user_active_passing_score']);
        }
        if(!empty($values['user_active_not_passing_score'])) {
          array_push($chart_active['not_pass'], $values['user_active_not_passing_score']);
        }
        $chart_active['total'] += $values['user_inactive'];
        $chart_active['total'] += $values['user_active_passing_score'];
        $chart_active['total'] += $values['user_active_not_passing_score'];
      }
    } else {
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
      $chart_active['total'] = 0;
      $first_update = '';
    }

    return view('report.dashboard_course2',[
      'training_title' => $training_title,
      'query_group' => $query_group,
      'search_group' => $search_group,
      'arr_region' => $arr_region,
      'search_region' => $search_region,
      'chart_active' => $chart_active
    ]);
  }

  // User เข้าเรียน
  public function user_active($training_id, $employee_id, $search_region){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne' => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne' => 0],
        'training_id' => $training_id,
      ];
    }
    if(!empty($search_region)) {
      $match['region'] = ['$in' => $search_region];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ 
          '$match' => $match
        ],
        [
          '$group' =>[
            '_id' => [ 'department'  => '$department'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            'total' => -1
          ]
        ]
      ]);
    });
    return $query;
  }
  // User เข้าเรียน และสอบผ่าน
  public function user_active_passing_score($training_id,$passing_score, $employee_id, $search_region) { 
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne'  => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
        'posttest' => [ '$gte' => $passing_score ],
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne'  => 0],
        'training_id' => $training_id,
        'posttest' => [ '$gte' => $passing_score ],
      ];
    }
    if(!empty($search_region)) {
      $match['region'] = ['$in' => $search_region];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ 
          '$match' => $match
        ],
        [
          '$group' =>[
            '_id' => [ 'department'  => '$department'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            'total' => -1
          ]
        ]
      ]);
    });
    return $query;
  }
  // User เข้าเรียน และไม่สอบผ่าน
  public function user_active_not_passing_score($training_id,$passing_score, $employee_id, $search_region){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'play_course'  => ['$ne'  => 0],
        'training_id'  => $training_id,
        'employee_id' => ['$in' => $employee_id],
        '$or' => [
          ['posttest' => [ '$lt' => $passing_score ]], 
          ['posttest' => [ '$eq' => null ]], 
          ['posttest' => [ '$eq' => '' ]]
        ] 
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course'  => ['$ne'  => 0],
        'training_id'  => $training_id,
        '$or' => [
          ['posttest' => [ '$lt' => $passing_score ]], 
          ['posttest' => [ '$eq' => null ]], 
          ['posttest' => [ '$eq' => '' ]]
        ] 
      ];
    }
    if(!empty($search_region)) {
      $match['region'] = ['$in' => $search_region];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ '$match' => $match
        ],
        [
          '$group' =>[
            '_id' => [ 'department'  => '$department'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            'total' => -1
          ]
        ]
      ]);
    });
    return $query;
  }
  // User ไม่เข้าเรียน
  public function user_inactive($training_id, $employee_id, $search_region){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
        '$or' => [
          ['play_course' => ['$eq'  => 0]], 
          ['play_course' => ['$eq'  => '']]
        ] 
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course' => ['$eq'  => 0],
        'training_id' => $training_id,
        '$or' => [
          ['play_course' => ['$eq'  => 0]], 
          ['play_course' => ['$eq'  => '']]
        ] 
      ];
    }
    if(!empty($search_region)) {
      $match['region'] = ['$in' => $search_region];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ 
          '$match' => $match
        ],
        [
          '$group' => [
            '_id' => [ 'department'  => '$department'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            'department' => 1
            // 'total' => -1
          ]
        ]
      ]);
    });
    return $query;
  }
}