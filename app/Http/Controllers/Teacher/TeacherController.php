<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use File;
use Image;
// Model
use App\Models\Teacher;

class TeacherController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function teacher_index(){
    $datas = Teacher::where('status',1)->get();
    return view('teacher.teacher_index',['datas' => $datas]);
  }
  public function teacher_create($id=''){
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->name = '';
      $data->subtitle = '';
      $data->slug = '';
      $data->label = '';
      $data->profile_image = '';
      $data->description = '';
      $data->history = '';
      $data->status = 1;
    } else {
      $data = Teacher::find($id);
    }
    $withData = [
      'data' => $data
    ]; 
    return view('teacher.teacher_detail',$withData);
  }
  public function teacher_store(Request $request){
    $id = $request->input('id');
    $name = $request->input('name');
    $subtitle = $request->input('subtitle');
    $slug = $request->input('slug');
    $label = $request->input('label');
    $profile_image = $request->input('profile_image');
    $description = $request->input('description');
    $history = $request->input('history');
    $status = $request->input('status');

    $this->update($request->all());

    return redirect()->route('teacher_index')->with('status',200);
  }
  
  protected function update(array $data)
  {
    if(!empty($data['id'])) {
      $teacher = Teacher::find($data['id']);
    } else {
      $teacher = new Teacher();
    }
    $teacher->name = $data['name'];
    $teacher->subtitle = $data['subtitle'];
    $teacher->slug = $data['slug'];
    $teacher->label = $data['label'];
    $teacher->description = $data['description'];
    $teacher->history = $data['history'];
    $teacher->status = 1;
    $teacher->save();

    if(!empty($data['profile_image'])) {
      $img_final = $data['img_final'];
      $input_path = $data['input_path'];
      $imgWidth = 400;
      $imgHeight = 300;
      // open file a image resource
      $img = Image::make(public_path($img_final));
      // crop image
      $img->resize($imgWidth, $imgHeight); // width, height
      // Save file
      $name = Carbon::now()->timestamp.'.png';
      $path_file = "images/$input_path/$teacher->_id/";
      $public_path = public_path($path_file);
      $filename = $public_path.$name;
      File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
      $img->save($filename);

      $teacher->profile_image = $path_file.$name;
      $teacher->save();
    }
    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข Course', new ObjectId($current_user->_id), $teacher->getTable(), $teacher->getAttributes(),$current_user->username);
  }
}
