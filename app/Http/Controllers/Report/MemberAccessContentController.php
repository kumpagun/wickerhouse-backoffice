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

class MemberAccessContentController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // ยอดคนเข้าดู ep 
  public function member_access_content_by_RO(Request $request){
    $search_group = $request->input('search_group'); 
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
      $datas_inactive = $this->user_inactive($training_id, $employee_id);
      $datas_active = $this->user_active($training_id, $employee_id);
      $datas_active_passing_score = $this->user_active_passing_score($training_id,$passing_score, $employee_id);
      $datas_active_not_passing_score = $this->user_active_not_passing_score($training_id,$passing_score, $employee_id);
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

      $data_total = [
        'user_active' => $total_user_active,
        'user_active_passing_score' => $total_user_active_passing_score,
        'user_active_not_passing_score' => $total_user_active_not_passing_score,
        'user_inactive' => $total_user_inactive,
      ];

      // PIE CHART เข้าเรียน / ไม่เข้าเรียน 
      $datas_chart = $this->get_data_chart($training_id, $employee_id);  //dd($datas_chart);
      $pie_chart_total['active'] = 0;
      $pie_chart_total['inactive'] = 0;
      foreach($new_datas as $index => $values) {
        $pie_chart_total['active'] += $values['user_active'];
        $pie_chart_total['inactive'] += $values['user_inactive'];
      }
      $pie_chart['label'] = [];
      $pie_chart['total'] = [$pie_chart_total['active'],$pie_chart_total['inactive']];
      $pie_chart['data'] = [];
      $pie_chart['outer_data'] = [];
      $pie_total = $pie_chart_total['active'] + $pie_chart_total['inactive'];
      if(!empty($pie_chart_total['active'])) {
        $percent_active = ($pie_chart_total['active']/$pie_total) * 100;
      } else {
        $percent_active = 0;
      }
      if(!empty($pie_chart_total['inactive'])) {
        $percent_inactive = ($pie_chart_total['inactive']/$pie_total) * 100;
      } else {
        $percent_inactive = 0;
      }
      
      array_push($pie_chart['data'], [
        'value' => $pie_chart_total['active'],
        'name' => number_format($percent_active,2).'%'
        // 'name' => 'เข้าเรียน'
      ]);
      array_push($pie_chart['data'], [
        'value' => $pie_chart_total['inactive'],
        'name' => number_format($percent_inactive,2).'%'
        // 'name' => 'ยังไม่เข้าเรียน'
      ]);
      if(!empty($pie_chart_total['active'])) {
        array_push($pie_chart['label'],'เข้าเรียน');
        array_push($pie_chart['outer_data'], [
          'value' => $pie_chart_total['active'],
          'name' => 'เข้าเรียน'
        ]);
      }
      if(!empty($pie_chart_total['inactive'])) {
        array_push($pie_chart['label'],'ยังไม่เข้าเรียน');
        array_push($pie_chart['outer_data'], [
          'value' => $pie_chart_total['inactive'],
          'name' => 'ยังไม่เข้าเรียน'
        ]);
      }
      // CHART เข้าเรียน / ไม่เข้าเรียน
      $chart['label'] = [];
      $chart['active'] = [];
      $chart['inactive'] = []; 
      foreach($datas_chart as $index => $values) {
        $value_active = 0;
        $value_inactive = 0;
        array_push($chart['label'], $index);
        if(!empty($values['active'])) {
          $value_active = $values['active'];
        }
        if(!empty($values['inactive'])) {
          $value_inactive = $values['inactive'];
        }
        if(!empty($value_active)) {
          array_push($chart['active'], $value_active);
        }
        if(!empty($value_inactive)) {
          array_push($chart['inactive'], $value_inactive);
        }
      }
      // CHART เข้าเรียน / ผ่าน / ไม่ผ่าน
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
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
      }
      // CHART % คนไม่เข้าเรียน
      $chart_inactive['label'] = [];
      $chart_inactive['total'] = [];
      foreach($new_datas as $index => $values) {
        if($data_total['user_inactive']==0) {
          $result = 0;
        } else {
          $result = ($values['user_inactive']*100)/$data_total['user_inactive'];
        }
        if(empty($index)) {
          $index = 'อื่นๆ';
        }
        $result = number_format((float)$result, 2, '.', '');
        array_push($chart_inactive['label'], $index);
        array_push($chart_inactive['total'], $result);
      }

      // GET LAST UPDATE
      $first_update = Report_member_access::where('training_id',$training_id)->orderBy('created_at','asc')->first(); 
      $last_update = Report_member_access::where('status',1)->where('training_id',$training_id)->first(); 
      if(!empty($first_update)) {
        $first_update = $first_update->created_at;
      }
      if(!empty($last_update)) {
        $last_update = $last_update->created_at;
        $first_date = new Carbon($first_update);
        $last_date = new Carbon($last_update);
        $diff = $first_date->diffInDays($last_date);
      } else {
        $diff = 5;
      }
    } else {
      $new_datas = [];
      $data_total = [];
      $pie_chart['label'] = [];
      $pie_chart['total'] = [];
      $pie_chart['outer_data'] = [];
      $chart['label'] = [];
      $chart['active'] = [];
      $chart['inactive'] = [];
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
      $chart_inactive['label'] = [];
      $chart_inactive['total'] = [];
      $first_update = '';
      $last_update = '';
      $diff = 5;
    }
    
    if($diff<5) {
      $diff=5;
    }

    if($platform=='excel') {
      return Excel::download(new Export_Report_member_access_by_RO($training_title,$datas,$data_total), Carbon::now()->timestamp.'.xlsx');
    } else {
      return view('report.member_access_content_by_ro',[
        'training_title' => $training_title,
        'last_update' => FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($last_update,'d/m/Y H:i:s'),
        'query_group' => $query_group,
        'search_group' => $search_group,
        'datas' =>  $new_datas,
        'data_total' =>  $data_total,
        'pie_chart' => $pie_chart,
        'chart' => $chart,
        'chart_active' => $chart_active,
        'chart_inactive' => $chart_inactive,
        'diff' => $diff
      ]);
    }
  }
  // User เข้าเรียน
  public function user_active($training_id, $employee_id){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne' => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id]
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne' => 0],
        'training_id' => $training_id
      ];
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
  public function user_active_passing_score($training_id,$passing_score, $employee_id) { 
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne'  => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
        'posttest' => [ '$gte' => $passing_score ]
      ];
    } else {
      $match = [
        'status'  => 1,
        'play_course' => ['$ne'  => 0],
        'training_id' => $training_id,
        'posttest' => [ '$gte' => $passing_score ]
      ];
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
  public function user_active_not_passing_score($training_id,$passing_score, $employee_id){
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
  public function user_inactive($training_id, $employee_id){
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

  public function get_data_chart($training_id, $employee_id) 
  {
    $group_online = Training::find($training_id); 
    $published_at = $group_online->published_at;
    $expired_at = $group_online->expired_at;

    $user_active = $this->get_data_chart_user_active($training_id,$published_at,$expired_at, $employee_id); 
    $user_inactive = $this->get_data_chart_user_inactive($training_id,$published_at,$expired_at, $employee_id); 
    
    $datas = [];
    foreach($user_inactive as $row) {
      $date = FuncClass::utc_to_carbon_format_date_no_format($row->_id->created_at)->format('Y-m-d');
      $datas[$date]['inactive'] = $row->total;
    }
    foreach($user_active as $row) {
      $date = FuncClass::utc_to_carbon_format_date_no_format($row->_id->created_at)->format('Y-m-d');
      $datas[$date]['active'] = $row->total;
    }
    return $datas;
  }
  // User เข้าเรียน
  public function get_data_chart_user_active($training_id,$published_at,$expired_at, $employee_id){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'play_course' => ['$ne'  => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
        'created_at' => [
          '$gte' => $published_at,
          '$lte' => $expired_at,
        ]
      ];
    } else {
      $match = [
        'play_course'  => ['$ne'  => 0],
        'training_id'  => $training_id,
        'created_at' => [
          '$gte' => $published_at,
          '$lte' => $expired_at,
        ]
      ];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ 
          '$match' => $match
        ],
        [
          '$group' =>[
            '_id' => [ 'created_at' => '$created_at'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            '_id.created_at' => 1
          ]
        ]
      ]);
    });
    return $query;
  }
  // User ไม่เข้าเรียน
  public function get_data_chart_user_inactive($training_id,$published_at,$expired_at, $employee_id){
    if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin')) {
      $match = [
        'play_course' => ['$eq'  => 0],
        'training_id' => $training_id,
        'employee_id' => ['$in' => $employee_id],
        'created_at' => [
          '$gte' => $published_at,
          '$lte' => $expired_at,
        ]
      ];
    } else {
      $match = [
        'play_course'  => ['$eq'  => 0],
        'training_id'  => $training_id,
        'created_at' => [
          '$gte' => $published_at,
          '$lte' => $expired_at,
        ]
      ];
    }
    $query = Report_member_access::raw(function ($collection) use ($match) {
      return $collection->aggregate([
        [ 
          '$match' => $match
        ],
        [
          '$group' => [
            '_id' => [ 'created_at' => '$created_at'],
            'total' => [ '$sum' => 1 ]
          ]
        ],
        [
          '$sort' => [
            '_id.created_at' => 1
          ]
        ]
      ]);
    });
    return $query;
  }
}