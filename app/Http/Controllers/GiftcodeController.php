<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use ActivityLogClass;
use FuncClass;
use File;
use Image;
use URL;
use Excel;
// Model
use App\Models\Course;
use App\Models\Training;
use App\Models\Giftcode_group;
use App\Models\Giftcode_usage;
use App\Models\Giftcode;

class GiftcodeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function giftcode_group_index() {
    $datas = Giftcode_group::where('status',1)->get();
    $training_arr = [];
    // foreach($datas as $data) {
    //   array_push($training_arr, new ObjectId($data->training_id));
    // }
    $training = Training::where('status',1)->whereNotIn('_id',$training_arr)->get();
    $withData = [
      'training' => $training,
      'datas' => $datas
    ];
    return view('giftcode.giftcode_group_index',$withData);
  }
  public function giftcode_group_create($giftcode_group_id='') {
    $giftcode_group = Giftcode_group::find($giftcode_group_id);
    $training = Training::where('_id',new ObjectId($giftcode_group->training_id))->where('status',1)->first();
    if(empty($giftcode_group_id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->training_id = '';
      $data->course_id = '';
      $data->description = '';
      $data->total = '';
      $data->published_at = '';
      $data->expired_at = '';
      $data->status = 1;
    } else {
      $data = Giftcode_group::find($giftcode_group_id);
    }
    $withData = [
      'training' => $training,
      'data' => $data
    ];
    return view('giftcode.giftcode_group_detail',$withData);
  }

  public function giftcode_group_store(Request $request){
    $id = $request->input('id');
    $training_id = $request->input('training_id');
    $description = $request->input('description');
    $published_at = $request->input('published_at');
    // $expired_at = $request->input('expired_at');
    $training = Training::where('_id', new ObjectId($training_id))->where('status',1)->first(); 
    $course_id = $training->course_id;

    // Date
    $start = new UTCDateTime(Carbon::createFromFormat('d-m-Y', $published_at,'Asia/Bangkok')->startOfDay()->setTimezone('UTC')->timestamp * 1000);
    $published_at = $start;
    // $end = new UTCDateTime(Carbon::createFromFormat('d-m-Y', $expired_at,'Asia/Bangkok')->endOfDay()->setTimezone('UTC')->timestamp * 1000);
    // $expired_at = $end;

    if(!empty($id)) {
      $giftcode_group = Giftcode_group::find($id);
    } else {
      $giftcode_group = new Giftcode_group();
    }
    $giftcode_group->training_id = new ObjectId($training_id);
    $giftcode_group->course_id = new ObjectId($course_id);
    $giftcode_group->description = $description;
    $giftcode_group->published_at = $published_at;
    // $giftcode_group->expired_at = $expired_at;
    $giftcode_group->status = 1;
    $giftcode_group->save();

    ActivityLogClass::log('เพิ่มหรือแก้ไข Giftcode_group', new ObjectId(Auth::user()->_id), $giftcode_group->getTable(), $giftcode_group->getAttributes(),Auth::user()->username);
  
    return redirect()->route('giftcode_group_index')->with('status',200);
  }
  public function giftcode_group_delete($id){

    $giftcode = Giftcode::where('group_id', new ObjectId($id))->where('active',1)->get();

    if(count($giftcode)==0) {
      $giftcode_group = Giftcode_group::find($id);
      $giftcode_group->status = 2;
      $giftcode_group->save();

      $giftcode_update = Giftcode::where('group_id',new ObjectId($giftcode_group->_id))->update(['status' => 2]);
  
      ActivityLogClass::log('ลบ giftcode_group', new ObjectId(Auth::user()->_id), $giftcode_group->getTable(), $giftcode_group->getAttributes(),Auth::user()->username);
      return redirect()->route('giftcode_group_index')->with('status',200);
    } else {
      return redirect()->route('giftcode_group_index')->with('status',400);
    }    
  }

  public function giftcode_reward_index($giftcode_group_id) {
    $giftcode_group = Giftcode_group::where('_id',$giftcode_group_id)->where('status',1)->first();
    $training = Training::where('status',1)->where('_id',new ObjectId($giftcode_group->training_id))->first();
    $datas = Giftcode::where('group_id',new ObjectId($giftcode_group_id))->where('status',1)->get();
    $withData = [
      'giftcode_group_id' => $giftcode_group_id,
      'datas' => $datas,
      'giftcode_group' => $giftcode_group,
      'training' => $training
    ];
    return view('giftcode.giftcode_reward_index',$withData);
  }
  function generateRandomString($length = 10) {
    $characters = '123456789ABCDEFGHJKMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  public function giftcode_reward_store(Request $request){
    $giftcode_group_id = $request->input('giftcode_group_id'); 
    $title = $request->input('title');
    $total = $request->input('total');

    for($i=1;$i<=$total;$i++) {
      $code = $this->generateRandomString();
      $key = true;
      while($key){
        $check = Giftcode::where('code',$code)->where('status',1)->first();
        if(empty($check)) {
          $key = false;
        }
      }
      $giftcode = new Giftcode();
      $giftcode->group_id = new ObjectId($giftcode_group_id);
      $giftcode->title = $title;
      $giftcode->code = $code;
      $giftcode->active = 0;
      $giftcode->status = 1;
      $giftcode->save();
    }

    ActivityLogClass::log('เพิ่มหรือแก้ไข Giftcode', new ObjectId(Auth::user()->_id), $giftcode->getTable(), $giftcode->getAttributes(),Auth::user()->username);
  
    return redirect()->route('giftcode_reward_index', ['giftcode_group_id' => $giftcode_group_id])->with('status',200);
  }
  public function giftcode_reward_delete($id){
    $giftcode = Giftcode::find($id);
    $giftcode->status = 2;
    $giftcode->save();

    ActivityLogClass::log('ลบ giftcode', new ObjectId(Auth::user()->_id), $giftcode->getTable(), $giftcode->getAttributes(),Auth::user()->username);
    return redirect()->route('giftcode_reward_index', ['giftcode_group_id' => $giftcode->group_id])->with('status',200);
  }

  public function giftcode_usage(Request $request) {
    $training_id = $request->input('training_id');
    $export = $request->input('export');
    if(empty($training_id)) {
      $training_id = Training::where('status',1)->first();
      $training_id = $training_id->_id;
    }
    $training = Training::where('status',1)->get();
    $datas = Giftcode_usage::raw(function ($collection) use ($training_id) {
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
            'status' => 1,
            'training_id' => new ObjectId($training_id)
          ]
        ],
        [
          '$unwind' => '$employees'
        ]
      ]);
    });
    $withData = [
      'training_id' => $training_id,
      'training' => $training,
      'datas' => $datas
    ];
    if($export=='excel') {
      return Excel::download(new Giftcode_Export($datas), Carbon::now()->timestamp.'.xlsx');
    }
    return view('giftcode.giftcode_usage', $withData);
  }
}
