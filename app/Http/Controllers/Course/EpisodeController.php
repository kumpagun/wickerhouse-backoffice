<?php

namespace App\Http\Controllers\Course;

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
// Controller
use  App\Http\Controllers\Course\HomeworkController;
// Model
use App\Models\Course;
use App\Models\Episode_group;

class EpisodeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function get_episode_group($course_id)
  {
    $data = Episode_group::where('course_id',new ObjectId($course_id))->where('status',1)->orderBy('position')->get();
    return $data;
  }
  public function episode_group_updatelist(Request $request) 
  {
    $course_id = $request->input('course_id');
    $episode_group = $request->input('episode_group');

    $count = 0;
    foreach($episode_group as $row) {
      $update = Episode_group::find($row);
      $update->position = $count;
      $update->save();
      $count++;
    }

    return response()->json([
      'status' => 200,
      'message' => 'Success.'
    ]); 
  }
  public function episode_group_store(Request $request)
  {
    $course_id = $request->input('course_id');
    $title = $request->input('title');

    $position = Episode_group::where('course_id',new ObjectId($course_id))->where('status',1)->count();

    if(!empty($data['id'])) {
      $episode_group = Episode_group::find($id);
    } else {
      $episode_group = new Episode_group();
    }
    $episode_group->title = $title;
    $episode_group->course_id = new ObjectId($course_id);
    $episode_group->status = 1;
    $episode_group->position = $position;
    $episode_group->save();

    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข episode_group', new ObjectId($current_user->_id), $episode_group->getTable(), $episode_group->getAttributes(),$current_user->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#episodegroup']);
  }

  public function episode_create($course_id, $id='')
  {
    $datas = [
      'id' => $id,
      'course_id' => $course_id
    ];
    return view('course.episode_detail', $datas);
  }
}