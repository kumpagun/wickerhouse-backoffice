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
use App\Models\Banner;

class BannerController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function banner_index(){
    $datas = Banner::where('status',1)->orderBy('position','asc')->get();
    $withData = [
      'datas' => $datas
    ];
    return view('banner.banner_index',$withData);
  }
  public function banner_create($id='') {
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->image_path = '';
      $data->position = '';
      $data->status = 1;
    } else {
      $data = Banner::find($id);
    }
    $withData = [
      'data' => $data,
    ]; 
    return view('banner.banner_detail',$withData);
  }
  public function banner_store(Request $request){
    $id = $request->input('id');
    $image_path = $request->input('image_path');
    $position = $request->input('position');

    $this->update($request->all());

    return redirect()->route('banner_index')->with('status',200);
  }
  
  protected function update(array $data)
  {
    if(empty($data['position'])) {
      $banner_count = Banner::where('status',1)->get();
      $position = $banner_count = $banner_count->count() + 1;
    } else {
      $position = $data['position'];
    }
    if(!empty($data['id'])) {
      $banner = Banner::find($data['id']);
    } else {
      $banner = new Banner();
    }
    $banner->position = $position;
    $banner->status = 1;
    $banner->save();

    if(!empty($data['image_path']) && !empty($data['img_final'])) {
      $img_final = $data['img_final'];
      $input_path = $data['input_path'];
      $imgWidth = 1938;
      $imgHeight = 300;
      // open file a image resource
      $img = Image::make(public_path($img_final));
      // crop image
      $img->resize($imgWidth, $imgHeight); // width, height
      // Save file
      $name = Carbon::now()->timestamp.'.png';
      $path_file = "images/$input_path/$banner->_id/";
      $public_path = storage_path('app/public/'.$path_file);
      $path_for_db = $path_file.$name;

      $filename = $public_path.'/'.$name;
      File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
      $img->save($filename);

      $banner->image_path = $path_for_db;
      $banner->save();
    }
    ActivityLogClass::log('เพิ่มหรือแก้ไข Banner', new ObjectId(Auth::user()->_id), $banner->getTable(), $banner->getAttributes(),Auth::user()->username);
  }
  public function banner_sort(Request $request) {
    $list = $request->input('list');

    $count = 0;
    foreach($list as $row) {
      if(!empty($row)) {
        $update = Banner::find($row);
        $update->position = $count;
        $update->save();
        $count++;
      }
    }

    return response()->json([
      'status' => 200,
      'message' => 'Success.',
      'data' => $list
    ]); 
  }
  public function banner_delete($banner_id){
    $banner = Banner::find($banner_id);
    $banner->status = 2;
    $banner->save();
    ActivityLogClass::log('ลบ banner', new ObjectId(Auth::user()->_id), $banner->getTable(), $banner->getAttributes(),Auth::user()->username);
    return redirect()->route('banner_index')->with('status',200);
  }
}
