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
use  App\Http\Controllers\Course\ExaminationController;
use  App\Http\Controllers\Course\DocumentController;
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
    'standard' => 'หลักสูตรมาตรฐาน',
    'general' => 'หลักสูตรทั่วไป',
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
    $teacher = $this->get_teacher();
    $course = $this->get_course();
    $category = $this->get_category();
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
      $episode_group = '';
      $episode = '';
      $episode_list = '';
      $homework = '';
      $examination = '';
      $examination_type = '';
      $document = '';
    } else {
      $data = Course::find($id);
      $episode_group_controller = new EpisodeController;
      $episode_group = $episode_group_controller->get_episode_group($id);
      $episode = $episode_group_controller->get_episode($id);
      $episode_list = $episode_group_controller->get_episode_list($id);
      $homework_controller = new HomeworkController;
      $homework = $homework_controller->get_homework($id);
      $examination_controller = new ExaminationController;
      $examination = $examination_controller->get_examination_group($id);
      $document_controller = new DocumentController;
      $document = $document_controller->get_document($id);
      $examination_type = ['pretest & posttest','pretest','posttest'];
      if(!empty($examination)) {
        // ลบ Type ที่มีแล้วออกจาก select list
        foreach($examination as $row) {
          foreach($row->type as $type) {
            if (($key = array_search($type, $examination_type)) !== false) {
              unset($examination_type[$key]);
            }
          }
        } 
        if(count($examination_type)<3) {
          unset($examination_type[0]);
        }
      }
    }
    
    $withData = [
      'data' => $data,
      'teacher' => $teacher,
      'type'  => $this->type,
      'course' => $course,
      'category' => $category,
      'episode_group' => $episode_group,
      'episode' => $episode,
      'episode_list' => $episode_list,
      'homework' => $homework,
      'examination' => $examination,
      'examination_type' => $examination_type,
      'document' => $document
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
        $str_format = sprintf('%05d',$str_count);
        $code     =  $get_cate->code.'-'.$str_format;
        FuncClass::update_seq_cate($category_id);
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
        'category_id'  => new ObjectId($category_id)
      ];
      if(empty($id)) {
        $datas['status'] = 2;
      }
      $find = [
        '_id' => $id
      ];
      $store = Course::UpdateOrCreate($find, $datas);
      if(!empty($thumbnail) && !empty($img_final)) {
        $course = Course::find($store->_id);
        // open file a image resource
        $img = Image::make(public_path($img_final));
        // crop image
        $img->resize($imgWidth, $imgHeight); // width, height
        // Save file
        $name = Carbon::now()->timestamp.'.png';
        $path_file = "images/$input_path/$course->_id";
        // $public_path = public_path($path_file);
        $public_path = storage_path('app/public/'.$path_file);
        $path_for_db = $path_file."/".$name;

        $filename = $public_path.'/'.$name;
        File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
        $img->save($filename);
  
        $course->thumbnail = $path_for_db;
        $course->save();
      }
      ActivityLogClass::log('เพิ่มหรือแก้ไข Course', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
      return redirect()->route('course_index');
  }
  public function course_review_url_store(Request $request){
    $course_id = $request->input('course_id');
    $review_url = $request->input('review_url');
    $rules = [
      'review_url' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'review_url')->withInput();
    }
    $course = Course::find($course_id);
    $course->review_url = $review_url;
    $course->save();

    ActivityLogClass::log('แก้ไข review_url', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#review_url']);
  }
  public function course_review_url_delete($course_id){
    $course = Course::find($course_id);
    $clear_ep = Course::where('_id',new ObjectId($course_id))->unset('review_url');
    ActivityLogClass::log('ลบ review_url', new ObjectId(Auth::user()->_id), $course->getTable(), $course->getAttributes(),Auth::user()->username);
    return redirect()->route('course_create', ['id' => $course_id, '#review_url']);
  }
}
