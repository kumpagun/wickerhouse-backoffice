<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Image;
// Model
use App\User;

class UploadImagesController extends Controller
{
  public function upload(Request $request) {
    $images = $request->file('file');
    $input_path = $request->input('input_path');

    $extension = $images->getClientOriginalExtension();
    $name = Carbon::now()->timestamp.'.'.$extension;
    $path_file = "images/uploads/$input_path/original/";
    $public_path = public_path($path_file);
    $filename = $public_path.$name;
    File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
    if (move_uploaded_file($images, $filename)) { 
      return response()->json([
        'status' => 200,
        'message' => $path_file.$name
      ]); 
    } else {
      return response()->json([
        'status' => 400,
        'message' => 'Error.'
      ]); 
    }
  }

  public function cropImage(Request $request) {
    $imgX = intval($request->input('imgX'));
    $imgY = intval($request->input('imgY'));
    $imgHeight = intval($request->input('imgHeight'));
    $imgWidth = intval($request->input('imgWidth'));
    $images = $request->input('images');
    $input_path = $request->input('input_path');

    // open file a image resource
    $img = Image::make(public_path($images));
    // crop image
    $img->crop($imgWidth, $imgHeight, $imgX, $imgY); // width, height, x, y
    // Save file
    $name = Carbon::now()->timestamp.'.png';
    $path_file = "images/uploads/$input_path/cropimage/";
    $public_path = public_path($path_file);

    $filename = $public_path.$name;
    File::isDirectory($public_path) or File::makeDirectory($public_path, 0777, true, true);
    $img->save($filename);

    return response()->json([
      'status' => 200,
      'message' => $path_file.$name
    ]); 
  }
}