<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\ImportExcels\MembersImport;
use Maatwebsite\Excel\Facades\Excel;
use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;

// Lib

use File;
use User;
use Auth;
use ActivityLogClass;

// Models
use App\Models\Category;
use App\Models\Member;
use App\Models\TrainingUser;
use App\Models\Member_jasmine;
use App\Models\Company;
use App\Models\Department;

class DepartmentController extends Controller
{   
    public function get_company(){
        $result = [];
        $datas = Company::query()->where('status',1)->get();
        if(!empty($datas)){
          foreach($datas as $each){
            array_push($result,new ObjectId($each->_id));
          }
        }
        return  $result;
    }

    public function department_index(){
        $datas = Department::query()->where('status',1)->get();
        return view('department.department_index',['datas' => $datas]);
    }
    public function create_department($id=''){
        $company = $this->get_company();
        if(empty($id)) {
            $data = new \stdClass();
            $data->id = '';
            $data->title = '';
            $data->company_id = '';
            $data->status = 1;
          } else {
            $data = Department::find($id);
          }
          $withData = [
            'data' => $data,
            'company' => $company
          ]; 
          return view('department.department_edit',$withData);
    }
    public function store_department(Request $request){
        $current_user   = Auth::user();
        $id = $request->input('id');
        $title = $request->input('title'); 
        $company_id = $request->input('company_id'); 
        $rules = [
            'title' => 'required',
            'company_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'department')->withInput();
        } $datas = [
            'title' => $title,
            'company_id' => new ObjectId($company_id),
            'status' => 1
        ];
        $find = [
            '_id' => $id
        ];
        $store = Department::UpdateOrCreate($find, $datas);
        ActivityLogClass::log('เพิ่มหรือแก้ไข Department', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
        return redirect()->route('department_index')->with('status',200);
    }
    public function delete_department($id=''){
        $delete = Department::find($id);
        $delete->status = 0;
        $delete->save();
        ActivityLogClass::log('ลบข้อมูล Department', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
        return redirect()->route('department_index');
    }

}
