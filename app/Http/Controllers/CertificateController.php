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
// Model
use App\Models\Certificate;

class CertificateController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_certificate() {
    $datas = Certificate::where('status',1)->get();
    return $datas;
  }
  public function certificate_index() {
    $datas = Certificate::where('status',1)->get();
    $withData = [
      'datas' => $datas
    ];
    return view('certificate.certificate_index',$withData);
  }
  public function certificate_create($id='') {
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->title = '';
      $data->certificate_image = '';
      $data->font_position = '';
      $data->font_size = '';
      $data->font_color = '';
      $data->font_newline = '';
      $data->course_position = '';
      $data->course_size = '';
      $data->course_color = '';
      $data->status = 1;
    } else {
      $data = Certificate::find($id);
    }
    $withData = [
      'data' => $data,
    ]; 
    return view('certificate.certificate_detail',$withData);
  }
  public function certificate_store(Request $request){
    $this->update($request->all());
    return redirect()->route('certificate_index')->with('status',200);
  }
  
  protected function update(array $data)
  {
    if(!empty($data['id'])) {
      $teacher = Certificate::find($data['id']);
    } else {
      $teacher = new Certificate();
    }
    if($data['font_newline']=="true") {
      $data['font_newline']=true;
    } else {
      $data['font_newline']=false;
    }
    $teacher->title = $data['title'];
    $teacher->font_position = $data['font_position'];
    $teacher->font_size = $data['font_size'];
    $teacher->font_color = $data['font_color'];
    $teacher->font_newline = $data['font_newline'];
    $teacher->course_position = $data['course_position'];
    $teacher->course_size = $data['course_size'];
    $teacher->course_color = $data['course_color'];
    $teacher->status = 1;
    $teacher->save();

    if(!empty($data['certificate_image']) && !empty($data['img_final'])) {
      $img_final = $data['img_final'];
      $input_path = $data['input_path'];
      $imgWidth = 1024;
      $imgHeight = 724;
      // open file a image resource
      $img = Image::make(public_path($img_final));
      // crop image
      $img->resize($imgWidth, $imgHeight); // width, height
      // Save file
      $name = Carbon::now()->timestamp.'.png';
      $path_file = "images/$input_path/$teacher->_id/";
      $public_path = storage_path('app/public/'.$path_file);
      $path_for_db = $path_file.$name;

      $filename = $public_path.'/'.$name;
      File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
      $img->save($filename);

      $teacher->certificate_image = $path_for_db;
      $teacher->save();
    }
    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข Certificate', new ObjectId($current_user->_id), $teacher->getTable(), $teacher->getAttributes(),$current_user->username);
  }
  public function certificate_delete($certificate_id){
    $certificate = Certificate::find($certificate_id);
    $certificate->status = 2;
    $certificate->save();
    ActivityLogClass::log('ลบ Certificate', new ObjectId(Auth::user()->_id), $certificate->getTable(), $certificate->getAttributes(),Auth::user()->username);
    return redirect()->route('certificate_index')->with('status',200);
  }
}
