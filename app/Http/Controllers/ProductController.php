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
// Model
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
  private $type = [
    'standard' => 'หลักสูตรมาตรฐาน',
    'general' => 'หลักสูตรทั่วไป',
  ];
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function product_index(Request $request){
    $search = $request->input('search');
    $query = Product::query()->where('status','!=',0);
    if(!empty($search)) {
      $query->where('title','like',"%$search%");
    }
    $datas = $query->get();
    return view('product.product_index',['datas' => $datas, 'search' => $search]);
  }
  public function product_create($id=''){
    $teacher = $this->get_teacher();
    $course = $this->get_course();
    $category = $this->get_category();
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
    } else {
      $data = Product::find($id);
    }

    $withData = [
      'data' => $data
    ]; 
    return view('product.product_detail',$withData);
  }
  public function product_store(Request $request){
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
      $require_product_array = [];
      if(!empty($require_course)){
        array_push($require_product_array,new ObjectId($require_course));
      }
      $teachers_array = [];
      if(!empty($teachers)) {
        foreach($teachers as $teacher) {
          array_push($teachers_array,new ObjectId($teacher));
        }
      }
      
      if(!empty($id)) {
        $get_data = Product::find($id);
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
          'require_course' => $require_product_array,
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
          'require_course' => $require_product_array,
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
      $store = Product::UpdateOrCreate($find, $datas);
      if(!empty($thumbnail) && !empty($img_final)) {
        $course = Product::find($store->_id);
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
      return redirect()->route('product_index')->with('status',200);
  }
}
