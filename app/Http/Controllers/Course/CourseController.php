<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
// Controller
use  App\Http\Controllers\Course\HomeworkController;
use  App\Http\Controllers\Course\EpisodeController;
// Model
use App\Models\Course;
use App\Models\Category;
use App\Models\Teacher;
use Carbon\Carbon;
use Auth;
use ActivityLogClass;
use FuncClass;
use File;
use Image;

class CourseController extends Controller
{
  private $type = [
    'standard' => 'Standard',
    'general' => 'General',
  ];
  public function __construct()
  {
    $this->middleware('auth');
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
  public function get_category(){
    $result = [];
    $datas = Category::query()->where('status',1)->get();
    if(!empty($datas)){
      foreach($datas as $each){
        array_push($result,new ObjectId($each->_id));
      }
    }
    return  $result;
  }
  public function get_teacher(){
    $result = [];
    $datas = Teacher::query()->where('status',1)->get();
    if(!empty($datas)){
      foreach($datas as $each){
        array_push($result,new ObjectId($each->_id));
      }
    }
    return  $result;
  }
  public function course_index(){
    $datas = Course::query()->where('status','!=',0)->get();
    return view('course.course_index',['datas' => $datas]);
  }
  public function course_create($id=''){
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->title = '';
      $data->description = '';
      $data->code = '';
      $data->slug = '';
      $data->type = '';
      $data->benefits = [];
      $data->appropriates = [];
      $data->teacher_id = '';
      $data->require_course = '';
      $data->thumbnail = '';
      $data->category_id = '';
      $data->tag = [];
      $data->thumbnail = '';
      $data->test_status = 0;
      $data->status = 1;
    } else {
      $data = Course::find($id);
    }
    $teacher = $this->get_teacher();
    $course = $this->get_course();
    $category = $this->get_category();
    $episode_group_controller = new EpisodeController;
    $episode_group = $episode_group_controller->get_episode_group($id);
    $homework_controller = new HomeworkController;
    $homework = $homework_controller->get_homework($id);
    $withData = [
      'data' => $data,
      'teacher' => $teacher,
      'type'  => $this->type,
      'course' => $course,
      'category' => $category,
      'episode_group' => $episode_group,
      'homework' => $homework
    ]; 
    return view('course.course_detail',$withData);
  }
  public function course_store(Request $request){
      $current_user   = Auth::user();
      $id = $request->input('id');
      $title = $request->input('title');
      $require_course = $request->input('require_course');
      $category_id = $request->input('category_id');
      $type = $request->input('type');
      $slug = $request->input('slug');
      $teacher_id = $request->input('teacher_id');
      $tag = $request->input('tag');
      $benefits = $request->input('benefits');
      $appropriates = $request->input('appropriates');
      $comming_soon = $request->input('comming_soon');
      $description = $request->input('description');
      $thumbnail = $request->input('thumbnail');
      $img_final = $request->input('img_final');
      $input_path = $request->input('input_path');
      $imgWidth = 1200;
      $imgHeight = 675;

      // benefits
      $arr_benefits = [];
      if(!empty($benefits)) {
        foreach($benefits as $row) {
          array_push($arr_benefits, $row['benefits']);
        }
      }
      
      // appropriates
      $arr_appropriates = [];
      if(!empty($appropriates)) {
        foreach($appropriates as $row) {
          array_push($arr_appropriates, $row['appropriates']);
        }
      }

      $tag = explode(",",$tag);

      if(!empty($id)) {
        $rules = [
          'title' => 'required',
          'category_id' => 'required',
          'type' => 'required',
          'slug' => 'required',
          'teacher_id' => 'required'
        ];
      } else {
        $rules = [
          'title' => 'required',
          'category_id' => 'required',
          'type' => 'required',
          'slug' => 'required',
          'teacher_id' => 'required',
          'thumbnail' => 'required',
          'img_final' => 'required',
          'input_path' => 'required',
        ];
      }
      
      $validator = Validator::make($request->all(), $rules);
      if($validator->fails()) {
        return redirect()->back()->withErrors($validator, 'course')->withInput();
      }
      $require_course_array = [];
      if(!empty($require_course)){
        array_push($require_course_array,new ObjectId($require_course));
      }
      
      if(!empty($id)) {
        $get_data = Course::find($id);
        $code = $get_data->code;
      }else{
        $get_cate = Category::find($category_id);
        $get_code = $get_cate->seq+1;
        $update_code = $get_code;
        $str_count = (string)$get_code;
        $str_format = sprintf('%04d',$str_count);
        $code     =  $get_cate->code.'-'.$str_format;
        FuncClass::update_seq_cate();
      }
      $datas = [
        'title' => $title,
        'description' => $description,
        'code' => $code,
        'slug' => strtolower($slug),
        'type' => $type,
        'tag'  => $tag,
        'coming_soon'  => 0,
        'require_course' => $require_course_array,
        'benefits' => $arr_benefits,
        'appropriates' => $arr_appropriates,
        'teacher_id' => new ObjectId($teacher_id),
        'category_id'  => new ObjectId($category_id),
        'status' => 2
      ];
      $find = [
        '_id' => $id
      ];
      $store = Course::UpdateOrCreate($find, $datas);
      if(!empty($thumbnail)) {
        $course = Course::find($store->_id);
        // open file a image resource
        $img = Image::make(public_path($img_final));
        // crop image
        $img->resize($imgWidth, $imgHeight); // width, height
        // Save file
        $name = Carbon::now()->timestamp.'.png';
        $path_file = "images/$input_path/$course->_id/";
        $public_path = public_path($path_file);
        $filename = $public_path.$name;
        File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
        $img->save($filename);
  
        $course->thumbnail = $path_file.$name;
        $course->save();
      }
      ActivityLogClass::log('เพิ่มหรือแก้ไข Course', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
      return redirect()->route('course_index');
  }
}
