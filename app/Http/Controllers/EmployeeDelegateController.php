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
use App\Models\Delegate;

class EmployeeDelegateController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {
    $datas = Delegate::where('status',1)->get();
    $withData = [
      'datas' => $datas
    ];
    return view('employee.delegate_index',$withData);
  }

  public function create() {
    
  }

  public function store(Request $request) {
    $employee_executive_id = $request->input('employee_executive_id');
    $employee_delegate_id = $request->input('employee_delegate_id');

    $delegate = new Delegate();
    $delegate->employee_executive_id = $employee_executive_id;
    $delegate->employee_delegate_id = $employee_delegate_id;
    $delegate->status = 1;
    $delegate->save();
  
    ActivityLogClass::log('เพิ่ม Employee Delegate', new ObjectId(Auth::user()->_id), $delegate->getTable(), $delegate->getAttributes(),Auth::user()->username);

    return redirect()->route('employee_delegate_index')->with('status',200);
  }

  public function delete($id) {
    $delegate = Delegate::find($id);
    $delegate->status = 2;
    $delegate->save();

    ActivityLogClass::log('ลบ Employee Delegate', new ObjectId(Auth::user()->_id), $delegate->getTable(), $delegate->getAttributes(),Auth::user()->username);

    return redirect()->route('employee_delegate_index')->with('status',200);
  }
}
