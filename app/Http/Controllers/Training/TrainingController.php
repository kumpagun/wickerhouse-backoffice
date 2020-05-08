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

use File;
use User;
use Auth;
use ActivityLogClass;
use CourseClass;

// Model
use App\Models\Category;
use App\Models\Member;
use App\Models\TrainingUser;
use App\Models\Training;
use App\Models\Member_jasmine;
use App\Models\Company;
use App\Models\Department;
use App\Models\Course;
use App\Models\MyTraining;
use App\Models\Employee;
use App\Models\Report_member_access;

class TrainingController extends Controller
{
    //
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function update_training_total_employee($training_id) {
      $training_user = TrainingUser::where('training_id',new ObjectId($training_id))->where('status',1)->count();
      $training = Training::find($training_id);
      $training->total_employee = $training_user;
      $training->save();
      return true;
    }
    public function training_index(Request $request) {
      $search = $request->input('search');
      $query = Training::query()->where('status',1);
      if(!empty($search)) {
        $query->where('title','like',"%$search%");
      }
      $datas = $query->get();
      return view('training.training_index',['datas' => $datas, 'search' => $search]);
    }
    public function traingin_user_list(Request $request,$training_id){
      $search = $request->input('search');

      $arr_employee_id = [];
      if(!empty($search)) {
        $member_jasmine = Member_jasmine::where('status',1);
        $member_jasmine->where(function ($q) use ($search) {
          $q->orWhere('firstname','like',"%$search%");
          $q->orWhere('lastname','like',"%$search%");
          $q->orWhere('employee_id','like',"%$search%");
        });
        $data_member = $member_jasmine->get();
        foreach($data_member as $row) {
          array_push($arr_employee_id, $row->employee_id);
        }
        
        $employee = Employee::where('status',1);
        $employee->where(function ($q) use ($search) {
          $q->orWhere('tf_name','like',"%$search%");
          $q->orWhere('tl_name','like',"%$search%");
          $q->orWhere('employee_id','like',"%$search%");
        });
        $data_member = $employee->get();
        foreach($data_member as $row) {
          array_push($arr_employee_id, $row->employee_id);
        }
      }
      
      $query = TrainingUser::query()->where('status',1);
      $query->where('training_id',new ObjectId($training_id));
      if(!empty($arr_employee_id)) {
        $query->whereIn('employee_id',$arr_employee_id);
      }
      $datas = $query->paginate(25);

      $training = Training::find($training_id);

      return view('training.user_training_index',['datas' => $datas, 'search' => $search, 'training_id' => $training_id, 'training' => $training]);
    }
    public function traingin_user_delete(Request $request) {
      $training_id = $request->input('training_id');
      $employee_id = $request->input('employee_id');
      $now = new UTCDateTime(Carbon::now()->timestamp * 1000);

      $data = TrainingUser::where('training_id',new ObjectId($training_id))->where('employee_id',$employee_id)->update(['status' => 0, 'expired_at' => $now]);
      MyTraining::where('training_id',new ObjectId($training_id))->where('employee_id',$employee_id)->update(['status' => 0, 'expired_at' => $now]);

      return response()->json([
        'status' => 200,
        'message' => 'success.'
      ]); 
    }
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
    public function get_department_by_company($company_id = '') {
      $result = [];
      if(!empty($company_id)){
        $datas = Department::query()->where('company_id',new ObjectId($company_id))->where('status',1)->get();
        foreach($datas as $each){
          array_push($result, [
            'id'            => $each->_id,
            'topic'         => $each->title,
          ]);
        }
      }
      return response()->json(
        $result
      );
    }
    public function get_department(){
      $result = [];
      $datas = Department::query()->where('status',1)->get();
      if(!empty($datas)){
        foreach($datas as $each){
          array_push($result,new ObjectId($each->_id));
        }
      }
      return  $result;
    }
    public function get_course(){
      $result = [];
      $datas = Course::query()->where('type','standard')->where('status',1)->get();
      if(!empty($datas)){
        foreach($datas as $each){
          array_push($result,new ObjectId($each->_id));
        }
      }
      return  $result;
    }
    public function training_create($id = ''){
      $department = $this->get_department();
      $company = $this->get_company();
      $course = $this->get_course();

      $query = Employee::query();
      $query->select('dept_name');
      $query->whereNotNull('dept_name');
      $query->groupBy('dept_name');
      $query->orderBy('dept_name');
      $dept_name = $query->get();

      $query = Employee::query();
      $query->select('company');
      $query->whereNotNull('company');
      $query->groupBy('company');
      $query->orderBy('company');
      $company_name = $query->get();

      $query = Employee::query();
      $query->select('job_family');
      $query->whereNotNull('job_family');
      $query->groupBy('job_family');
      $query->orderBy('job_family');
      $job_family = $query->get();

      $query = Employee::query();
      $query->select('branch_name');
      $query->whereNotNull('branch_name');
      $query->groupBy('branch_name');
      $query->orderBy('branch_name');
      $branch_name = $query->get();

      $query = Employee::query();
      $query->select('region');
      $query->whereNotNull('region');
      $query->groupBy('region');
      $query->orderBy('region');
      $region = $query->get();

      $query = Employee::query();
      $query->select('division_name');
      $query->whereNotNull('division_name');
      $query->groupBy('division_name');
      $query->orderBy('division_name');
      $division_name = $query->get();

      $query = Employee::query();
      $query->select('section_name');
      $query->whereNotNull('section_name');
      $query->groupBy('section_name');
      $query->orderBy('section_name');
      $section_name = $query->get();

      $query = Employee::query();
      $query->select('staff_grade');
      $query->whereNotNull('staff_grade');
      $query->groupBy('staff_grade');
      $query->orderBy('staff_grade');
      $staff_grade = $query->get();

      $query = Employee::query();
      $query->select('title_name');
      $query->whereNotNull('title_name');
      $query->groupBy('title_name');
      $query->orderBy('title_name');
      $title_name = $query->get();

      if(empty($id)) {
        $data = new \stdClass();
        $data->id = '';
        $data->title = '';
        $data->course_id = '';
        $data->company_id = '';
        $data->department_ids = '';
        $data->published_at = '';
        $data->expired_at = '';
      } else {
        $data = Training::find($id);
      }
      $withData = [
        'data' => $data,
        'department' => $department,
        'company' => $company,
        'course' => $course,
        'dept_name' => $dept_name,
        'company_name' => $company_name,
        'job_family' => $job_family,
        'branch_name' => $branch_name,
        'region' => $region,
        'division_name' => $division_name,
        'section_name' => $section_name,
        'staff_grade' => $staff_grade,
        'title_name' => $title_name,
      ]; 
      return view('training.training_edit',$withData);
    }
    public function training_store(Request $request){
      $current_user   = Auth::user();
      $id = $request->input('id');
      $title = $request->input('title'); 
      $course_id = $request->input('course_id'); 
      $published_at = $request->input('published_at'); 
      $expired_at = $request->input('expired_at'); 
      if ($id) {
        $rules = [
          'title' => 'required',
          'published_at' => 'required',
          'expired_at' => 'required',
        ];
      } else {
        $rules = [
          'title' => 'required',
          'published_at' => 'required',
          'expired_at' => 'required',
          'course_id' => 'required',
        ];
      }
      
      $validator = Validator::make($request->all(), $rules);
      if($validator->fails()) {
        return redirect()->back()->withErrors($validator, 'training')->withInput();
      }  
      // Date
      $start = new UTCDateTime(Carbon::createFromFormat('d-m-Y', $published_at,'Asia/Bangkok')->startOfDay()->setTimezone('UTC')->timestamp * 1000);
      $published_at = $start;
      $end = new UTCDateTime(Carbon::createFromFormat('d-m-Y', $expired_at,'Asia/Bangkok')->endOfDay()->setTimezone('UTC')->timestamp * 1000);
      $expired_at = $end;
      if ($id) {
        $query = Training::find($id);
        $course_id = new ObjectId($query->course_id);
      }else{
        $course_id = new ObjectId($course_id);
      }
      $datas = [
        'title' => $title,
        'course_id' => new ObjectId($course_id),
        'published_at' => $published_at,
        'expired_at' => $expired_at,
        'status' => 1
      ];
      $find = [
        '_id' => $id
      ];
      $store = Training::UpdateOrCreate($find, $datas);

      $this->update_training_total_employee($store->_id);

      ActivityLogClass::log('เพิ่มหรือแก้ไข Training', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
      return redirect()->route('training_create',['id' => $store->_id])->with('success','success');
    }
    public function training_delete($id=''){
      $delete = Training::find($id);
      $delete->status = 2;
      $delete->save();
      ActivityLogClass::log('ลบข้อมูล Training', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
      return redirect()->route('training_index');
    }

    public function import_excel(Request $request){
      $class_id = $request->input('class_id');
      $excel  = $request->file('excel');
      $rules  = [
        'excel'       => 'required',
      ];
      $validator = Validator::make($request->all(), $rules);
      if($validator->fails()) {
        return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel');
      }
      $training_id = $class_id;
      $training_data = Training::find($class_id);
      $course_id = $training_data->course_id;
      $user_action = Auth::user()->_id;
      $original_filename = $excel->getClientOriginalName();
      $file_path = '';
      $total_record = 0;
      $total_import = 0;
      $total_error = 0;
      $total_duplicate = 0;
      
      $path = $excel->getRealPath();
      $extension = $excel->getClientOriginalExtension();
      $current_date =  Carbon::now()->timestamp;
      $name_excel = $current_date.'_excel.'.$extension;
      $path_file ='File/excel_member/backup/'.(string)$class_id;
      
      try{
        File::isDirectory($path_file) or File::makeDirectory($path_file, 0777, true, true);
        $excel->move($path_file,$name_excel);
      } catch (\Exception  $e) {
        return redirect()->back()->with('msg', 'ไม่สามารถ เก็บ File ได้');    
      }
      $path_file_name = $path_file.'/'.$name_excel;
      $file_path = $path_file_name;
      try{
        $datas = Excel::toCollection(new MembersImport,public_path($path_file_name));
        try {
          $total_record = count($datas[0])-1;
          $id_log = ActivityLogClass::log_start_import_excel($file_path,$training_id,$user_action,$original_filename,$total_record,$course_id);
          foreach($datas[0] as $key => $value){
            if($key != 0){
              if($value[0] != null){
                if (!empty($value[1]) && !empty($value[2]) && !empty($value[3]) && !empty($value[4]) && !empty($value[15]) && !empty($value[16]) ) {
                  $check_user = TrainingUser::where('employee_id',(string)($value[1]))->where('status',1)->where('training_id',new ObjectId($class_id))->first();
                  $check_member_jasmine = Member_jasmine::where('employee_id',(string)($value[1]))->where('status',1)->first();
                  $member_jasmine_id = null;
                  $member_user = null;
                  // Add Member Jasmine);
                  if (empty($check_member_jasmine)) {
                    $datas = [
                      'employee_id'  => (string)$value[1],
                      'tinitial'   => $value[2], 
                      'firstname'   => $value[3], 
                      'lastname'   => $value[4], 
                      'emptype'   => $value[5], 
                      'joined_date'   =>  new UTCDateTime((\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[6]))), 
                      'workplace'   => $value[7], 
                      'email'   => $value[8], 
                      'sex'   => $value[9], 
                      'birthdate'   =>  new UTCDateTime((\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[10]))), 
                      'title'  => $value[12], 
                      'division'   => $value[13], 
                      'section'   => $value[14], 
                      'department'  =>  $value[15], 
                      'company'   => $value[16], 
                      'staff_grade'   => intval($value[17]), 
                      'job_family'   => $value[18], 
                      'status'  => 1,
                    ];
                    $find = [
                      '_id' => $member_jasmine_id
                    ];
                    $store = Member_jasmine::UpdateOrCreate($find, $datas);
                    $member_jasmine_id = new ObjectId((string)($store->_id));
                  } else {
                    $datas = [
                      'employee_id'  => (string)$value[1],
                      'tinitial'   => $value[2], 
                      'firstname'   => $value[3], 
                      'lastname'   => $value[4], 
                      'emptype'   => $value[5], 
                      'joined_date'   =>  new UTCDateTime((\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[6]))), 
                      'workplace'   => $value[7], 
                      'email'   => $value[8], 
                      'sex'   => $value[9], 
                      'birthdate'   =>  new UTCDateTime((\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[10]))), 
                      'title'  => $value[12], 
                      'division'   => $value[13], 
                      'section'   => $value[14], 
                      'department'  =>  $value[15], 
                      'company'   => $value[16], 
                      'staff_grade'   => intval($value[17]), 
                      'job_family'   => $value[18],
                      'status'  => 1,
                    ];
                    $find = [
                      '_id' => $check_member_jasmine->_id
                    ];
                    $store = Member_jasmine::UpdateOrCreate($find, $datas);
                    $member_jasmine_id = new ObjectId((string)($store->_id));
                  }
                  // Add Class Room
                  if (empty($check_user)) {
                    $total_import += 1;
                    $insert =  new TrainingUser();
                    $insert->training_id = new ObjectId($class_id);
                    $insert->employee_id =  (string)$value[1]; 
                    $insert->member_jasmine_id = new ObjectId($member_jasmine_id);
                    $insert->course_id = new ObjectId($course_id);
                    $insert->type = 'excel';
                    $insert->status = 1;
                    $insert->save();
                  }else{
                    $total_duplicate += 1; 
                  }
                }else{
                  $total_error += 1;
                }
              }else{
                ActivityLogClass::log_end_import_excel($id_log,$total_import,$total_error,$total_duplicate);
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่ถูกต้อง');
              }
            }else{
              if($value[0] != 'ลำดับ'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ ลำดับ');
              }
              if($value[1] != 'EmployeeID'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ EmployeeID');
              } 
              if($value[2] != 'Tinitial'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ Tinitial');
              } 
              if($value[3] != 'TFName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ TFName');
              } 
              if($value[4] != 'TLName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ TLName');
              } 
              if($value[5] != 'EmpType'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ EmpType');
              } 
              if($value[6] != 'DateJoined'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ DateJoined');
              }  
              if($value[7] != 'Workplace'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ Workplace');
              } 
              if($value[8] != 'Email'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ Email');
              } 
              if($value[9] != 'Sex'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ Sex');
              } 
              if($value[10] != 'BirthDate'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ BirthDate');
              } 
              if($value[11] != 'HealthPlanID'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ HealthPlanID');
              } 
              if($value[12] != 'TitleName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ TitleName');
              } 
              if($value[13] != 'DivisionName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ DivisionName');
              }
              if($value[14] != 'SectionName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ SectionName');
              } 
              if($value[15] != 'DeptName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ DeptName');
              } 
              if($value[16] != 'CompanyShortName'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ CompanyShortName');
              } 
              if($value[17] != 'StaffGrade'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ StaffGrade');
              } 
              if($value[18] != 'JobFamily'){
                return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่พบ หัวข้อ JobFamily');
              }
            }
          } 
      } catch (\Exception  $e) {
        ActivityLogClass::log_end_import_excel($id_log,$total_import,$total_error,$total_duplicate);
        return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่ถูกต้อง');    
      }
    } catch (\Exception  $e) {
      ActivityLogClass::log_end_import_excel($id_log,$total_import,$total_error,$total_duplicate);
      return redirect()->back()->with('msg', 'รูปแบบไฟล์ Excel ไม่ถูกต้อง');    
    }
    $this->update_training_total_employee($class_id);

    ActivityLogClass::log_end_import_excel($id_log,$total_import,$total_error,$total_duplicate);

    return redirect()->back()->with('success','success');
  }

  public function training_employee_filter(Request $request) {
    $training_id = $request->input('training_id');
    $employee_id = $request->input('employee_id');
    $employee_name = $request->input('employee_name');
    $company_name = $request->input('company_name');
    $dept_name = $request->input('dept_name');
    $job_family = $request->input('job_family');
    $branch_name = $request->input('branch_name');
    $region = $request->input('region');
    $division_name = $request->input('division_name');
    $section_name = $request->input('section_name');
    $staff_grade = $request->input('staff_grade');
    $title_name = $request->input('title_name');
    $in_dept = $request->input('in_dept');
    $longevity = $request->input('longevity');
    $longevity_condition = $request->input('longevity_condition');
    
    $head_employee_id = [];
    if(Auth()->user()->type=='jasmine') {
      array_push($head_employee_id, Auth()->user()->user_info->employee_id);
    }

    // คนที่เคยอบรมแล้ว
    $training_user = TrainingUser::where('training_id',new ObjectId($training_id))->where('status',1)->get();
    $arr_training_employee_id = [];
    if(!empty($training_user)) {
      foreach($training_user as $row) {
        array_push($arr_training_employee_id,$row->employee_id);
      }
    }

    // คนที่ผ่านคอร์สที่ Require
    $training = Training::find($training_id);
    $require_course = CourseClass::get_require_course_by_id($training->course_id);
    $arr_pass_require_course = [];
    if(!empty($require_course)) {
      $passing_score = CourseClass::get_course_passing_score($training->course_id); 
      $user_pass_require_course = Report_member_access::where('course_id',new ObjectId($training->course_id))->where('status',1)->where('posttest','>=',$passing_score)->get();
      foreach($user_pass_require_course as $row) {
        array_push($arr_pass_require_course,$row->employee_id);
      }
    }

    $query = Employee::query();
    $query->select('employee_id', 'tinitial','tf_name','tl_name');
    if(!empty($employee_id)) {
      $query->where('employee_id',$employee_id);
    }
    if(!empty($employee_name)) {
      $query->where(function ($q) use ($employee_name) {
        $q->orWhere('tf_name','like',"%$employee_name%");
        $q->orWhere('tl_name','like',"%$employee_name%");
      });
    }
    if(!empty($company_name)) {
      $company = Company::find($company_name);
      $query->where('company',$company->title);
    }
    if(!empty($dept_name)) {
      $department = Department::find($dept_name);
      $query->where('dept_name',$department->title);
    }
    if(!empty($job_family)) {
      $query->where('job_family',$job_family);
    }
    if(!empty($branch_name)) {
      $query->where('branch_name',$branch_name);
    }
    if(!empty($region)) {
      $query->where('region',$region);
    }
    if(!empty($division_name)) {
      $query->where('division_name',$division_name);
    }
    if(!empty($section_name)) {
      $query->where('section_name',$section_name);
    }
    if(!empty($staff_grade)) {
      $query->where('staff_grade',$staff_grade);
    }
    if(!empty($title_name)) {
      $query->where('title_name',$title_name);
    }
    if(!empty($arr_pass_require_course)) {
      $query->whereIn('employee_id',$arr_pass_require_course);
    }
    if(!empty($arr_training_employee_id)) {
      $query->whereNotIn('employee_id',$arr_training_employee_id);
    }
    if(count($head_employee_id)>0) {
      $query->whereIn('heads',$head_employee_id);
    }

    if(!empty($longevity)) {
      foreach($longevity as $index => $row) {
        if(!empty($longevity_condition[$index]) && !empty($longevity[$index])) {
          $condition = $longevity_condition[$index];
          if($condition=='=') {
            $date_start = Carbon::now()->subYears($longevity[$index])->startOfDay();
            $date_end = Carbon::now()->subYears($longevity[$index]+1)->startOfDay();
            // เช่นถ้าเลือกอายุงาน = 1 ปี
            // จะเป็นอายุการทำงานมากกว่าเท่ากับ 1 ปี แต่ไม่มากกว่า 2 ปี เป็นต้น
            $query->where('date_joined','<=',$date_start);
            $query->where('date_joined','>',$date_end);
          } else {
            $date = Carbon::now()->subYears($longevity[$index])->startOfDay();
            $query->where('date_joined',$condition,$date);
          }
        }
      }
    }
    $datas = $query->get();

    $data_back = [
      'status' => 200,
      'datas' => $datas,
    ];
    
    return response()->json($data_back); 
  }

  public function training_import_employees(Request $request) {
    $employees = $request->input('employees');
    $training_id = $request->input('training_id');

    $query = Training::find($training_id);
    $course_id = new ObjectId($query->course_id);

    if(!empty($employees)) {
      foreach($employees as $employee) {
        $check_user = TrainingUser::where('employee_id',(string)$employee)->where('status',1)->where('training_id',new ObjectId($training_id))->first();
        $check_member_jasmine = Employee::where('employee_id',(string)$employee)->first();
        if (empty($check_user)) {
          $insert =  new TrainingUser();
          $insert->training_id = new ObjectId($training_id);
          $insert->employee_id =  (string)$employee; 
          $insert->member_jasmine_id = new ObjectId($check_member_jasmine->_id);
          $insert->course_id = new ObjectId($course_id);
          $insert->type = 'filter';
          $insert->status = 1;
          $insert->save();
        }
      }
    }
    $this->update_training_total_employee($training_id);

    return redirect()->back()->with('success','success');
  }
}
