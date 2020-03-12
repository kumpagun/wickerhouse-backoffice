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

class CompanyController extends Controller
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

    public function company_index(){
      $datas = Company::query()->where('status',1)->orderBy('title','asc')->get();
      return view('company.company_index',['datas' => $datas]);
    }
    public function company_create($id=''){
        if(empty($id)) {
            $data = new \stdClass();
            $data->id = '';
            $data->title = '';
            $data->status = 1;
          } else {
            $data = Company::find($id);
          }
          $withData = [
            'data' => $data
          ]; 
          return view('company.company_edit',$withData);
    }
    public function company_store(Request $request){
        $current_user   = Auth::user();
        $id = $request->input('id');
        $title = $request->input('title'); 
        $rules = [
            'title' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'company')->withInput();
        }  
        $datas = [
            'title' => $title,
            'status' => 1
        ];
        $find = [
            '_id' => $id
        ];
        $store = Company::UpdateOrCreate($find, $datas);
        ActivityLogClass::log('เพิ่มหรือแก้ไข Company', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);

        return redirect()->route('company_index')->with('status',200);
    }
    public function company_delete($id=''){
        $delete = Company::find($id);
        $delete->status = 2;
        $delete->save();
        ActivityLogClass::log('ลบข้อมูล Company', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
        return redirect()->route('company_index');
    }
}
