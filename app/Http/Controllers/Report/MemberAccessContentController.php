<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use MongoDB\BSON\ObjectId as ObjectId;
use DB;
use CourseClass;
use FuncClass;
// Model
use App\Models\Report_member_access;
use App\Models\Training;

class MemberAccessContentController extends Controller
{
  public function __construct()
  {
    $this->deptname = [
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
  }

  // ยอดคนเข้าดู ep 
  public function member_access_content_by_RO(Request $request){
    $search_group = $request->input('search_group'); 
    $query_group = Training::query()->where('status',1)->where('total_employee','>',0)->get();
    if(empty($search_group)){
      $query = Training::query()->where('status',1)->where('total_employee','>',0)->first();
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
      $datas_inactive = $this->user_inactive($training_id);
      $datas_active = $this->user_active($training_id);
      $datas_active_passing_score = $this->user_active_passing_score($training_id,$passing_score);
      $datas_active_not_passing_score = $this->user_active_not_passing_score($training_id,$passing_score);
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
      foreach($this->deptname as $row) {
        if(!empty($datas[$row]['user_active'])) {
          $new_datas[$row]['user_active'] = $datas[$row]['user_active'];
        } else {
          $new_datas[$row]['user_active'] = 0;
        }
        if(!empty($datas[$row]['user_active_passing_score'])) {
          $new_datas[$row]['user_active_passing_score'] = $datas[$row]['user_active_passing_score'];
        } else {
          $new_datas[$row]['user_active_passing_score'] = 0;
        }
        if(!empty($datas[$row]['user_active_not_passing_score'])) {
          $new_datas[$row]['user_active_not_passing_score'] = $datas[$row]['user_active_not_passing_score'];
        } else {
          $new_datas[$row]['user_active_not_passing_score'] = 0;
        }
        if(!empty($datas[$row]['user_inactive'])) {
          $new_datas[$row]['user_inactive'] = $datas[$row]['user_inactive'];
        } else {
          $new_datas[$row]['user_inactive'] = 0;
        }
      }

      $data_total = [
        'user_active' => $total_user_active,
        'user_active_passing_score' => $total_user_active_passing_score,
        'user_active_not_passing_score' => $total_user_active_not_passing_score,
        'user_inactive' => $total_user_inactive,
      ];

      // PIE CHART เข้าเรียน / ไม่เข้าเรียน 
      $datas_chart = $this->get_data_chart($training_id); 
      $pie_chart_total['active'] = 0;
      $pie_chart_total['inactive'] = 0;
      foreach($new_datas as $index => $values) {
        $pie_chart_total['active'] += $values['user_active'];
        $pie_chart_total['inactive'] += $values['user_inactive'];
      }
      $pie_chart['label'] = ['เข้าเรียน','ยังไม่เข้าเรียน'];
      $pie_chart['total'] = [$pie_chart_total['active'],$pie_chart_total['inactive']];
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
        array_push($chart['active'], $value_active);
        
        array_push($chart['inactive'], $value_inactive);
      }
      // CHART เข้าเรียน / ผ่าน / ไม่ผ่าน
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
      foreach($new_datas as $index => $values) {
        array_push($chart_active['label'], $index);
        array_push($chart_active['inactive'], $values['user_inactive']);
        array_push($chart_active['pass'], $values['user_active_passing_score']);
        array_push($chart_active['not_pass'], $values['user_active_not_passing_score']);
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
        
        $result = number_format((float)$result, 2, '.', '');
        array_push($chart_inactive['label'], $index);
        array_push($chart_inactive['total'], $result);
      }

      // GET LAST UPDATE
      $last_update = Report_member_access::where('status',1)->first(); 
      $last_update = $last_update->created_at;
    } else {
      $new_datas = [];
      $data_total = [];
      $pie_chart['label'] = [];
      $pie_chart['total'] = [];
      $chart['label'] = [];
      $chart['active'] = [];
      $chart['inactive'] = [];
      $chart_active['label'] = [];
      $chart_active['inactive'] = [];
      $chart_active['pass'] = [];
      $chart_active['not_pass'] = [];
      $chart_inactive['label'] = [];
      $chart_inactive['total'] = [];
      $last_update = '';
    }
    
    return view('report.member_access_content_by_ro',[
      'training_title' => $training_title,
      'last_update' => Carbon::parse($last_update)->format('d/m/Y H:i:s'),
      'query_group' => $query_group,
      'search_group' => $search_group,
      'datas' =>  $new_datas,
      'data_total' =>  $data_total,
      'pie_chart' => $pie_chart,
      'chart' => $chart,
      'chart_active' => $chart_active,
      'chart_inactive' => $chart_inactive
    ]);
  }
  // User เข้าเรียน
  public function user_active($training_id){
    $query = Report_member_access::raw(function ($collection) use ($training_id) {
      return $collection->aggregate([
        [ '$match' => [
            'status'  => 1,
            'play_course'  => ['$ne'  => 0],
            'training_id'  => $training_id,
            'department' => ['$in' => $this->deptname]
          ]
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
  public function user_active_passing_score($training_id,$passing_score){ 
    $query = Report_member_access::raw(function ($collection) use ($training_id,$passing_score) {
      return $collection->aggregate([
        [ '$match' => [
            'status'  => 1,
            'play_course'  => ['$ne'  => 0],
            'training_id'  => $training_id,
            'posttest' => [ '$gte' => $passing_score ],
            'department' => ['$in' => $this->deptname]
          ]
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
  public function user_active_not_passing_score($training_id,$passing_score){
    $query = Report_member_access::raw(function ($collection) use ($training_id,$passing_score) {
      return $collection->aggregate([
        [ '$match' => [
            'status'  => 1,
            'play_course'  => ['$ne'  => 0],
            'training_id'  => $training_id,
            '$or' => [
              ['posttest' => [ '$lt' => $passing_score ]], 
              ['posttest' => [ '$eq' => null ]]
            ] ,
            'department' => ['$in' => $this->deptname]
          ]
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
  public function user_inactive($training_id){
    $query = Report_member_access::raw(function ($collection) use ($training_id) {
      return $collection->aggregate([
        [ 
          '$match' => [
            'status'  => 1,
            'play_course'  => ['$eq'  => 0],
            'training_id'  => $training_id,
            'department' => ['$in' => $this->deptname]
          ]
        ],
        [
          '$group' => [
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

  public function get_data_chart($training_id) 
  {
    $group_online = Training::find($training_id); 
    $published_at = $group_online->published_at;
    $expired_at = $group_online->expired_at;

    $user_active = $this->get_data_chart_user_active($training_id,$published_at,$expired_at);
    $user_inactive = $this->get_data_chart_user_inactive($training_id,$published_at,$expired_at); 
    
    $datas = [];
    foreach($user_active as $row) {
      $date = FuncClass::utc_to_carbon_format_date_no_format($row->_id->created_at)->format('Y-m-d');
      $datas[$date]['active'] = $row->total;
    }
    foreach($user_inactive as $row) {
      $date = FuncClass::utc_to_carbon_format_date_no_format($row->_id->created_at)->format('Y-m-d');
      $datas[$date]['inactive'] = $row->total;
    }
    return $datas;
  }
  // User เข้าเรียน
  public function get_data_chart_user_active($training_id,$published_at,$expired_at){
    $query = Report_member_access::raw(function ($collection) use ($training_id,$published_at,$expired_at) {
      return $collection->aggregate([
        [ '$match' => [
            'play_course'  => ['$ne'  => 0],
            'training_id'  => $training_id,
            'department' => ['$in' => $this->deptname],
            'created_at' => [
              '$gte' => $published_at,
              '$lte' => $expired_at,
            ],
          ]
        ],
        [
          '$group' =>[
            '_id' => [ 'created_at' => '$created_at'],
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
  public function get_data_chart_user_inactive($training_id,$published_at,$expired_at){
    $query = Report_member_access::raw(function ($collection) use ($training_id,$published_at,$expired_at) {
      return $collection->aggregate([
        [ 
          '$match' => [
            'play_course'  => ['$eq'  => 0],
            'training_id'  => $training_id,
            'department' => ['$in' => $this->deptname],
            'created_at' => [
              '$gte' => $published_at,
              '$lte' => $expired_at,
            ],
          ]
        ],
        [
          '$group' => [
            '_id' => [ 'created_at' => '$created_at'],
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
}