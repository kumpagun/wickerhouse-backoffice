<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
// Controller
use App\Http\Controllers\Course\HomeworkController;
use App\Http\Controllers\Course\EpisodeController;
use App\Http\Controllers\Course\ExaminationController;
use App\Http\Controllers\Course\QuizController;
use App\Http\Controllers\Course\DocumentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CertificateController;
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
  public function course_index(Request $request){
    $search = $request->input('search');
    $query = Course::query()->where('status','!=',0);
    if(!empty($search)) {
      $query->where('title','like',"%$search%");
    }
    $datas = $query->get();
    return view('course.course_index',['datas' => $datas, 'search' => $search]);
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
      $data->teachers = '';
      $data->require_course = '';
      $data->thumbnail = '';
      $data->category_id = '';
      $data->certificate_id = '';
      $data->tag = [];
      $data->thumbnail = '';
      $data->test_status = 0;
      $data->status = 1;
      $data->training_only = false;
      $episode_group = '';
      $episode = '';
      $episode_list = '';
      $homework = '';
      $examination = '';
      $examination_type = '';
      $document = '';
      $review_group = '';
      $quiz = '';
      $episode_not_selected = '';
      $certificate = '';
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
      $review_controller = new ReviewController;
      $review_group = $review_controller->get_review_group($id);
      $quiz_controller = new QuizController;
      $quiz = $quiz_controller->get_quiz_group($id);
      $episode_not_selected = $quiz_controller->get_episode_not_selected($id);
      $certificate_controller = new CertificateController;
      $certificate = $certificate_controller->get_certificate($id);
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
      'document' => $document,
      'review_group' => $review_group,
      'quiz' => $quiz,
      'episode_not_selected' => $episode_not_selected,
      'certificate' => $certificate
    ]; 
    return view('course.course_detail',$withData);
  }
  public function course_store(Request $request){
      $current_user   = Auth::user();
      $id = $request->input('id');
      $title = $request->input('title');
      $require_course = $request->input('require_course');
      $category_id = $request->input('category_id');
      $certificate_id = $request->input('certificate_id');
      $type = $request->input('type');
      $slug = $request->input('slug');
      $teachers = $request->input('teachers');
      $tag = $request->input('tag');
      $benefits = $request->input('benefits');
      $appropriates = $request->input('appropriates');
      $comming_soon = $request->input('comming_soon');
      $description = $request->input('description');
      $thumbnail = $request->input('thumbnail');
      $img_final = $request->input('img_final');
      $input_path = $request->input('input_path');
      $status = $request->input('status');
      $training_only = $request->input('training_only');
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
          'teachers' => 'required'
        ];
      } else {
        $rules = [
          'title' => 'required',
          'category_id' => 'required',
          'type' => 'required',
          'slug' => 'required',
          'teachers' => 'required',
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
      $teachers_array = [];
      if(!empty($teachers)) {
        foreach($teachers as $teacher) {
          array_push($teachers_array,new ObjectId($teacher));
        }
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
      if(!empty($training_only)) {
        $training_only = true;
      } else {
        $training_only = false;
      }

      if(!empty($certificate_id)) {
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
          'teachers' => $teachers_array,
          'category_id'  => new ObjectId($category_id),
          'have_certificate' => true,
          'certificate_id' => new ObjectId($certificate_id),
          'training_only' => $training_only
        ];
      } else {
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
          'teachers' => $teachers_array,
          'category_id'  => new ObjectId($category_id),
          'have_certificate' => false,
          'certificate_id' => '',
          'training_only' => $training_only
        ];
      }
      
      if(empty($id)) {
        $datas['status'] = 2;
      } else {
        $datas['status'] = intval($status);
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
      return redirect()->route('course_index')->with('status',200);
  }
}
