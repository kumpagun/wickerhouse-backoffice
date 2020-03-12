<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;
use ActivityLogClass;
use Auth;

// Models
use App\Models\Member;
use App\Models\Employee;
use App\Models\Employee_vip;

class EmployeeVIPController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function employee_vip_index(){
    $datas = Employee_vip::query()->where('status',1)->get();
    // Members login
    $datas = Employee::raw(function ($collection) {
      return $collection->aggregate([
        [
          '$lookup' => [
            'from' =>  "employee_vips",
            'localField' =>  "employee_id",
            'foreignField' =>  "employee_id",
            'as' =>  "employee_vips"
          ]
        ],
        [
          '$match' => [
            'employee_vips.status' => 1,
            'status' => 1
          ]
        ],
        [
          '$unwind' => '$employee_vips'
        ]
      ]);
    });
    $employees = Employee::where('status',1)->orderBy('tf_name','asc')->orderBy('tl_name','asc')->get();
    $withData = [
      'datas' => $datas,
      'employees' => $employees
    ];
    return view('employee.employee_vip_index',$withData);
  }
  public function employee_vip_create(){

  }
  public function employee_vip_store(Request $request){
    $employee_id = $request->input('employee_id');
    $employee_vip = Employee_vip::where('employee_id',$employee_id)->first();
    if(!empty($employee_vip)) {
      $check = Employee_vip::where('employee_id',$employee_id)->first(); 
      $employee = Employee_vip::find($check->_id);
      $employee->status = 1;
      $employee->save();
    } else if(empty($employee_vip)) {
      $employee = new Employee_vip();
      $employee->employee_id = $employee_id;
      $employee->status = 1;
      $employee->save();
    }
    ActivityLogClass::log('เพิ่มหรือแก้ไข Employee VIP', new ObjectId(Auth::user()->_id), $employee->getTable(), $employee->getAttributes(),Auth::user()->username);

    return redirect()->route('employee_vip_index')->with('status',200);
  }
  public function employee_vip_delete(Request $request){
    $employee_id = $request->input('employee_id');
    $check = Employee_vip::where('employee_id',$employee_id)->first(); 
    $employee = Employee_vip::find($check->_id);
    $employee->status = 2;
    $employee->save();
    ActivityLogClass::log('ลบ Employee VIP', new ObjectId(Auth::user()->_id), $employee->getTable(), $employee->getAttributes(),Auth::user()->username);

    return redirect()->route('employee_vip_index')->with('status',200);
  }
}
